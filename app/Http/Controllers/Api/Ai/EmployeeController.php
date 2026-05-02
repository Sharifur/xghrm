<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('category');

        $active = $request->query('active', 'true');
        if ($active === 'true') {
            $query->where('status', 1);
        } elseif ($active === 'false') {
            $query->where('status', 0);
        }

        if ($request->filled('department')) {
            $query->whereHas('category', fn($q) => $q->where('title', $request->department));
        }

        $employees = $query->get()->map(fn($e) => $this->formatEmployee($e));

        return response()->json(['data' => $employees, 'total' => $employees->count()]);
    }

    public function show($id)
    {
        $employee = Employee::with('category')->find($id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        return response()->json($this->formatEmployee($employee));
    }

    public function todayOnLeave()
    {
        $today = Carbon::today();

        $logs = AttendanceLog::with('employee')
            ->whereIn('type', ['leave', 'sick-leave', 'paid-leave'])
            ->where('status', 1)
            ->whereDate('date_time', $today)
            ->get();

        $data = $logs->map(fn($log) => [
            'employeeId' => (string) $log->employee_id,
            'employeeName' => $log->employee?->name ?? $log->name,
            'leaveType' => $this->mapLeaveType($log->type),
            'fromDate' => Carbon::parse($log->date_time)->toDateString(),
            'toDate' => Carbon::parse($log->date_time)->toDateString(),
        ]);

        return response()->json(['data' => $data]);
    }

    public function todayWfh()
    {
        $today = Carbon::today();

        $logs = AttendanceLog::with('employee')
            ->where('type', 'work-from-home')
            ->where('status', 1)
            ->whereDate('date_time', $today)
            ->get();

        $data = $logs->map(fn($log) => [
            'employeeId' => (string) $log->employee_id,
            'employeeName' => $log->employee?->name ?? $log->name,
            'date' => Carbon::parse($log->date_time)->toDateString(),
        ]);

        return response()->json(['data' => $data]);
    }

    public function alerts(Request $request)
    {
        $withinDays = (int) $request->query('withinDays', 7);
        $until = Carbon::today()->addDays($withinDays);

        $employees = Employee::with('category')->where('status', 1)->get();

        $probationEnding = $employees->filter(fn($e) =>
            $e->incrementMonth && Carbon::parse($e->incrementMonth)->between(Carbon::today(), $until)
        )->map(fn($e) => [
            'employeeId' => (string) $e->id,
            'employeeName' => $e->name,
            'probationUntil' => Carbon::parse($e->incrementMonth)->toDateString(),
        ])->values();

        $birthdays = $employees->filter(function ($e) use ($until) {
            if (!$e->dateOfBirth) return false;
            $bday = Carbon::parse($e->dateOfBirth)->setYear(Carbon::today()->year);
            return $bday->between(Carbon::today(), $until);
        })->map(fn($e) => [
            'employeeId' => (string) $e->id,
            'employeeName' => $e->name,
            'date' => Carbon::parse($e->dateOfBirth)->setYear(Carbon::today()->year)->toDateString(),
        ])->values();

        $workAnniversaries = $employees->filter(function ($e) use ($until) {
            if (!$e->joinDate) return false;
            $anniv = Carbon::parse($e->joinDate)->setYear(Carbon::today()->year);
            return $anniv->between(Carbon::today(), $until);
        })->map(fn($e) => [
            'employeeId' => (string) $e->id,
            'employeeName' => $e->name,
            'years' => (int) Carbon::parse($e->joinDate)->diffInYears(Carbon::today()),
            'date' => Carbon::parse($e->joinDate)->setYear(Carbon::today()->year)->toDateString(),
        ])->values();

        return response()->json([
            'probationEnding' => $probationEnding,
            'contractExpiring' => [],
            'birthdays' => $birthdays,
            'workAnniversaries' => $workAnniversaries,
        ]);
    }

    private function formatEmployee(Employee $e): array
    {
        return [
            'id' => (string) $e->id,
            'name' => $e->name,
            'email' => $e->email,
            'phone' => $e->mobile,
            'role' => $e->category?->title,
            'department' => $e->category?->title,
            'salary' => (float) $e->salary,
            'currency' => 'BDT',
            'joinedAt' => $e->joinDate ? Carbon::parse($e->joinDate)->toDateString() : null,
            'probationUntil' => $e->incrementMonth ? Carbon::parse($e->incrementMonth)->toDateString() : null,
            'contractEndsAt' => null,
            'leaveBalance' => null,
            'bankAccount' => $e->paymentInfo,
            'active' => $e->status === 1,
            'photoUrl' => null,
        ];
    }

    private function mapLeaveType(string $type): string
    {
        return match ($type) {
            'sick-leave' => 'sick',
            'paid-leave' => 'annual',
            default => 'annual',
        };
    }
}
