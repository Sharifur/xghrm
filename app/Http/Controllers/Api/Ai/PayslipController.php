<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SalarySlip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);

        $query = SalarySlip::with('employee')
            ->whereYear('month', Carbon::parse($request->month)->year)
            ->whereMonth('month', Carbon::parse($request->month)->month);

        $slips = $query->get();

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

        $employees = Employee::where('status', 1)->get();
        $generated = 0;
        $skipped = 0;
        $slips = [];

        foreach ($employees as $employee) {
            $existing = SalarySlip::where('employee_id', $employee->id)
                ->whereYear('month', Carbon::parse($request->month)->year)
                ->whereMonth('month', Carbon::parse($request->month)->month)
                ->first();

            if ($existing) {
                $skipped++;
                $slips[] = $existing->load('employee');
                continue;
            }

            $slip = SalarySlip::create([
                'employee_id' => $employee->id,
                'month' => Carbon::parse($request->month)->startOfMonth(),
                'salary' => $employee->salary,
                'extraEarningFields' => null,
                'extraDeductionFields' => null,
            ]);

            $generated++;
            $slips[] = $slip->load('employee');
        }

        return response()->json([
            'generated' => $generated,
            'skipped' => $skipped,
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

        if ($request->has('bonus')) {
            $earnings['bonus'] = (float) $request->bonus;
        }
        if ($request->has('deductions')) {
            $deductions['deductions'] = (float) $request->deductions;
        }

        $slip->update([
            'extraEarningFields' => json_encode($earnings),
            'extraDeductionFields' => json_encode($deductions),
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
            $bonus = $this->getBonus($slip);
            $deductions = $this->getDeductions($slip);
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
        $bonus = $this->getBonus($slip);
        $deductions = $this->getDeductions($slip);

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
        return $slip->salary + $this->getBonus($slip) - $this->getDeductions($slip);
    }

    private function getBonus(SalarySlip $slip): float
    {
        if (!$slip->extraEarningFields) return 0;
        $data = json_decode($slip->extraEarningFields, true);
        return (float) ($data['bonus'] ?? 0);
    }

    private function getDeductions(SalarySlip $slip): float
    {
        if (!$slip->extraDeductionFields) return 0;
        $data = json_decode($slip->extraDeductionFields, true);
        return (float) ($data['deductions'] ?? 0);
    }
}
