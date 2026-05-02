<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WfhController extends Controller
{
    public function pending()
    {
        $logs = AttendanceLog::with('employee')
            ->where('type', 'work-from-home')
            ->where('status', 0)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'data' => $logs->map(fn($l) => $this->format($l)),
            'total' => $logs->count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'employeeId' => 'required|exists:employees,id',
            'date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string',
        ]);

        $date = Carbon::parse($request->date)->startOfDay();
        $employee = Employee::findOrFail($request->employeeId);

        $duplicate = AttendanceLog::where('employee_id', $request->employeeId)
            ->where('type', 'work-from-home')
            ->whereDate('date_time', $date)
            ->exists();

        if ($duplicate) {
            return response()->json(['error' => 'WFH request already exists for this date'], 409);
        }

        $log = AttendanceLog::create([
            'employee_id' => $employee->id,
            'name' => $employee->name,
            'type' => 'work-from-home',
            'date_time' => $date,
            'status' => 0,
        ]);

        return response()->json($this->format($log->load('employee')), 201);
    }

    public function approve(Request $request, $id)
    {
        $log = AttendanceLog::where('type', 'work-from-home')->find($id);

        if (!$log) {
            return response()->json(['error' => 'WFH request not found'], 404);
        }

        if ($log->status !== 0) {
            return response()->json(['error' => 'WFH request is not in pending status'], 409);
        }

        $log->update(['status' => 1]);

        return response()->json($this->format($log->load('employee')));
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);

        $log = AttendanceLog::where('type', 'work-from-home')->find($id);

        if (!$log) {
            return response()->json(['error' => 'WFH request not found'], 404);
        }

        if ($log->status !== 0) {
            return response()->json(['error' => 'WFH request is not in pending status'], 409);
        }

        $log->update(['status' => 2]);

        return response()->json($this->format($log->load('employee')));
    }

    private function format(AttendanceLog $log): array
    {
        return [
            'id' => (string) $log->id,
            'employeeId' => (string) $log->employee_id,
            'employeeName' => $log->employee?->name ?? $log->name,
            'date' => Carbon::parse($log->date_time)->toDateString(),
            'reason' => null,
            'status' => match ($log->status) {
                1 => 'approved',
                2 => 'rejected',
                default => 'pending',
            },
            'decisionReason' => null,
            'decidedAt' => null,
            'createdAt' => $log->created_at->toISOString(),
        ];
    }
}
