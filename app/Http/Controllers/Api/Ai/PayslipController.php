<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\AdvanceSalary;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\SalarySlip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PayslipController extends Controller
{
    private const LUNCH_RATE = 50;

    public function index(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);

        $slips = SalarySlip::with('employee')
            ->whereYear('month', Carbon::parse($request->month)->year)
            ->whereMonth('month', Carbon::parse($request->month)->month)
            ->get();

        $totalNet = $slips->sum(fn($s) => $this->calcNet($s));

        return response()->json([
            'data' => $slips->map(fn($s) => $this->format($s)),
            'total' => $slips->count(),
            'summary' => [
                'month' => $request->month,
                'totalNet' => $totalNet,
                'currency' => 'BDT',
                'approvedCount' => $slips->count(),
                'draftCount' => 0,
            ],
        ]);
    }

    public function show($employeeId, $month)
    {
        $slip = SalarySlip::with('employee')
            ->where('employee_id', $employeeId)
            ->whereYear('month', Carbon::parse($month)->year)
            ->whereMonth('month', Carbon::parse($month)->month)
            ->first();

        if (!$slip) {
            return response()->json(['error' => 'Payslip not found'], 404);
        }

        return response()->json($this->format($slip));
    }

    public function generate(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);

        $monthDate = Carbon::parse($request->month);
        $employees = Employee::where('status', 1)->get();
        $generated = 0;
        $skipped = 0;
        $noAttendance = 0;
        $slips = [];

        foreach ($employees as $employee) {
            $existing = SalarySlip::where('employee_id', $employee->id)
                ->whereYear('month', $monthDate->year)
                ->whereMonth('month', $monthDate->month)
                ->first();

            if ($existing) {
                $skipped++;
                $slips[] = $existing->load('employee');
                continue;
            }

            $inCount = AttendanceLog::where('employee_id', $employee->id)
                ->whereYear('date_time', $monthDate->year)
                ->whereMonth('date_time', $monthDate->month)
                ->where('type', 'C/In')
                ->count();

            $outCount = AttendanceLog::where('employee_id', $employee->id)
                ->whereYear('date_time', $monthDate->year)
                ->whereMonth('date_time', $monthDate->month)
                ->where('type', 'C/Out')
                ->count();

            $presentDays = max($inCount, $outCount);

            // Skip employees with no attendance — they were on leave the entire month
            if ($presentDays === 0) {
                $noAttendance++;
                continue;
            }

            $earnings = [];
            $deductions = [];

            $lunchAmount = $presentDays * self::LUNCH_RATE;
            $earnings[] = ['description' => $presentDays . ' Days Lunch', 'amount' => $lunchAmount];

            $advanceTotal = AdvanceSalary::where('employee_id', $employee->id)
                ->whereYear('month', $monthDate->year)
                ->whereMonth('month', $monthDate->month)
                ->sum('amount');

            if ($advanceTotal > 0) {
                $deductions[] = ['description' => 'Advance Salary', 'amount' => (float) $advanceTotal];
            }

            $slip = SalarySlip::create([
                'employee_id' => $employee->id,
                'month' => $monthDate->copy()->startOfMonth(),
                'salary' => $employee->salary,
                'extraEarningFields' => json_encode($earnings),
                'extraDeductionFields' => count($deductions) ? json_encode($deductions) : null,
            ]);

            $generated++;
            $slips[] = $slip->load('employee');
        }

        return response()->json([
            'generated' => $generated,
            'skipped' => $skipped,
            'noAttendance' => $noAttendance,
            'data' => array_map(fn($s) => $this->format($s), $slips),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $slip = SalarySlip::find($id);

        if (!$slip) {
            return response()->json(['error' => 'Payslip not found'], 404);
        }

        $earnings = $slip->extraEarningFields ? json_decode($slip->extraEarningFields, true) : [];
        $deductions = $slip->extraDeductionFields ? json_decode($slip->extraDeductionFields, true) : [];

        if (!is_array($earnings) || !isset($earnings[0])) $earnings = [];
        if (!is_array($deductions) || !isset($deductions[0])) $deductions = [];

        if ($request->has('bonus')) {
            $idx = array_search('Bonus', array_column($earnings, 'description'));
            if ($idx !== false) {
                $earnings[$idx]['amount'] = (float) $request->bonus;
            } else {
                $earnings[] = ['description' => 'Bonus', 'amount' => (float) $request->bonus];
            }
        }

        if ($request->has('deductions')) {
            $idx = array_search('Manual Deduction', array_column($deductions, 'description'));
            if ($idx !== false) {
                $deductions[$idx]['amount'] = (float) $request->deductions;
            } else {
                $deductions[] = ['description' => 'Manual Deduction', 'amount' => (float) $request->deductions];
            }
        }

        $slip->update([
            'extraEarningFields' => json_encode($earnings),
            'extraDeductionFields' => count($deductions) ? json_encode($deductions) : null,
        ]);

        return response()->json($this->format($slip->load('employee')));
    }

    public function approve($id)
    {
        $slip = SalarySlip::find($id);

        if (!$slip) {
            return response()->json(['error' => 'Payslip not found'], 404);
        }

        return response()->json($this->format($slip->load('employee')));
    }

    public function markPaid(Request $request, $id)
    {
        $slip = SalarySlip::find($id);

        if (!$slip) {
            return response()->json(['error' => 'Payslip not found'], 404);
        }

        return response()->json($this->format($slip->load('employee')));
    }

    public function export($month)
    {
        $slips = SalarySlip::with('employee')
            ->whereYear('month', Carbon::parse($month)->year)
            ->whereMonth('month', Carbon::parse($month)->month)
            ->get();

        $rows = ["employee_id,name,department,role,base_salary,bonus,deductions,net_salary,currency,status"];

        foreach ($slips as $slip) {
            $bonus = $this->sumFields($slip->extraEarningFields);
            $deductions = $this->sumFields($slip->extraDeductionFields);
            $net = $slip->salary + $bonus - $deductions;
            $rows[] = implode(',', [
                $slip->employee_id,
                '"' . ($slip->employee?->name ?? '') . '"',
                '"' . ($slip->employee?->category?->title ?? '') . '"',
                '"' . ($slip->employee?->category?->title ?? '') . '"',
                $slip->salary,
                $bonus,
                $deductions,
                $net,
                'BDT',
                'approved',
            ]);
        }

        return Response::make(implode("\n", $rows), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"payslips-{$month}.csv\"",
        ]);
    }

    private function format(SalarySlip $slip): array
    {
        $bonus = $this->sumFields($slip->extraEarningFields);
        $deductions = $this->sumFields($slip->extraDeductionFields);

        return [
            'id' => (string) $slip->id,
            'employeeId' => (string) $slip->employee_id,
            'employeeName' => $slip->employee?->name,
            'month' => Carbon::parse($slip->month)->format('Y-m'),
            'baseSalary' => (float) $slip->salary,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'netSalary' => $slip->salary + $bonus - $deductions,
            'currency' => 'BDT',
            'workingDays' => null,
            'presentDays' => null,
            'status' => 'approved',
            'approvedAt' => $slip->updated_at->toISOString(),
            'paidAt' => null,
            'createdAt' => $slip->created_at->toISOString(),
        ];
    }

    private function calcNet(SalarySlip $slip): float
    {
        return $slip->salary + $this->sumFields($slip->extraEarningFields) - $this->sumFields($slip->extraDeductionFields);
    }

    private function sumFields(?string $json): float
    {
        if (!$json) return 0;
        $data = json_decode($json, true);
        if (!is_array($data)) return 0;
        // Array-of-objects format: [{"description":"...","amount":X}]
        if (isset($data[0]) && is_array($data[0])) {
            return (float) array_sum(array_column($data, 'amount'));
        }
        // Legacy flat format fallback: {"bonus": X}
        return (float) array_sum(array_values($data));
    }
}
