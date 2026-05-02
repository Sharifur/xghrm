<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    // status: 0 = pending, 1 = approved, 2 = rejected

    public function index(Request $request)
    {
        $query = AttendanceLog::with('employee')
            ->whereIn('type', ['leave', 'sick-leave', 'paid-leave']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $this->statusToInt($request->status));
        }

        if ($request->filled('employeeId')) {
            $query->where('employee_id', $request->employeeId);
        }

        if ($request->filled('month')) {
            $query->whereYear('date_time', Carbon::parse($request->month)->year)
                  ->whereMonth('date_time', Carbon::parse($request->month)->month);
        }

        $logs = $query->orderBy('id', 'desc')->get();
        $pendingCount = $logs->where('status', 0)->count();

        return response()->json([
            'data' => $logs->map(fn($l) => $this->format($l)),
            'total' => $logs->count(),
            'pendingCount' => $pendingCount,
        ]);
    }

    public function pending()
    {
        $logs = AttendanceLog::with('employee')
            ->whereIn('type', ['leave', 'sick-leave', 'paid-leave'])
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
            'type' => 'required|in:annual,sick,unpaid,maternity,paternity,other',
            'fromDate' => 'required|date|after_or_equal:today',
            'toDate' => 'required|date|after_or_equal:fromDate',
            'reason' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($request->employeeId);

        $log = AttendanceLog::create([
            'employee_id' => $employee->id,
            'name' => $employee->name,
            'type' => $this->specTypeToInternal($request->type),
            'date_time' => Carbon::parse($request->fromDate)->startOfDay(),
            'status' => 0,
        ]);

        return response()->json($this->format($log->load('employee')), 201);
    }

    public function approve(Request $request, $id)
    {
        $log = AttendanceLog::whereIn('type', ['leave', 'sick-leave', 'paid-leave'])->find($id);

        if (!$log) {
            return response()->json(['error' => 'Leave request not found'], 404);
        }

        if ($log->status !== 0) {
            return response()->json(['error' => 'Leave request is not in pending status'], 409);
        }

        $log->update(['status' => 1]);

        return response()->json($this->format($log->load('employee')));
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);

        $log = AttendanceLog::whereIn('type', ['leave', 'sick-leave', 'paid-leave'])->find($id);

        if (!$log) {
            return response()->json(['error' => 'Leave request not found'], 404);
        }

        if ($log->status !== 0) {
            return response()->json(['error' => 'Leave request is not in pending status'], 409);
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
            'type' => $this->internalTypeToSpec($log->type),
            'fromDate' => Carbon::parse($log->date_time)->toDateString(),
            'toDate' => Carbon::parse($log->date_time)->toDateString(),
            'totalDays' => 1,
            'reason' => null,
            'status' => $this->intToStatus($log->status),
            'decisionReason' => null,
            'decidedAt' => null,
            'createdAt' => $log->created_at->toISOString(),
        ];
    }

    private function statusToInt(string $status): int
    {
        return match ($status) {
            'approved' => 1,
            'rejected' => 2,
            default => 0,
        };
    }

    private function intToStatus(int $status): string
    {
        return match ($status) {
            1 => 'approved',
            2 => 'rejected',
            default => 'pending',
        };
    }

    private function specTypeToInternal(string $type): string
    {
        return match ($type) {
            'sick' => 'sick-leave',
            'annual', 'unpaid', 'maternity', 'paternity', 'other' => 'leave',
            default => 'leave',
        };
    }

    private function internalTypeToSpec(string $type): string
    {
        return match ($type) {
            'sick-leave' => 'sick',
            'paid-leave' => 'annual',
            default => 'annual',
        };
    }
}
