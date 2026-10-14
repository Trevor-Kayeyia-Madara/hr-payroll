<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with('employee');

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        return response()->json($attendances);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        $attendance = Attendance::create($validated);
        return response()->json($attendance->load('employee'), 201);
    }

    public function show(string $id): JsonResponse
    {
        $attendance = Attendance::with('employee')->findOrFail($id);
        return response()->json($attendance);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'sometimes|required|in:present,absent,late,half_day',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        // Calculate hours worked if both check_in and check_out are present
        if ($attendance->check_in && $attendance->check_out) {
            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = Carbon::parse($attendance->check_out);
            $attendance->hours_worked = $checkOut->diffInMinutes($checkIn) / 60;
            $attendance->save();
        }

        return response()->json($attendance->load('employee'));
    }

    public function destroy(string $id): JsonResponse
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json(null, 204);
    }

    public function clockIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $today = now()->toDateString();
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $today,
            ],
            [
                'check_in' => now()->format('H:i'),
                'status' => 'present',
            ]
        );

        return response()->json($attendance->load('employee'));
    }

    public function clockOut(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $today = now()->toDateString();
        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $today)
            ->firstOrFail();

        $attendance->check_out = now()->format('H:i');

        if ($attendance->check_in) {
            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = Carbon::parse($attendance->check_out);
            $attendance->hours_worked = $checkOut->diffInMinutes($checkIn) / 60;
        }

        $attendance->save();

        return response()->json($attendance->load('employee'));
    }
}
