<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Leave::with(['employee', 'approver']);

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();
        return response()->json($leaves);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|max:50',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        // Calculate days
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $validated['days'] = $start->diffInDays($end) + 1;
        $validated['status'] = 'pending';

        $leave = Leave::create($validated);
        return response()->json($leave->load(['employee', 'approver']), 201);
    }

    public function show(string $id): JsonResponse
    {
        $leave = Leave::with(['employee', 'approver'])->findOrFail($id);
        return response()->json($leave);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $leave = Leave::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|required|string|max:50',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string',
        ]);

        if (isset($validated['start_date']) && isset($validated['end_date'])) {
            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);
            $validated['days'] = $start->diffInDays($end) + 1;
        }

        $leave->update($validated);
        return response()->json($leave->load(['employee', 'approver']));
    }

    public function destroy(string $id): JsonResponse
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();
        return response()->json(null, 204);
    }

    public function approve(Request $request, string $id): JsonResponse
    {
        $leave = Leave::findOrFail($id);

        $validated = $request->validate([
            'approved_by' => 'required|exists:users,id',
        ]);

        $leave->update([
            'status' => 'approved',
            'approved_by' => $validated['approved_by'],
            'approved_at' => now(),
        ]);

        return response()->json($leave->load(['employee', 'approver']));
    }

    public function reject(Request $request, string $id): JsonResponse
    {
        $leave = Leave::findOrFail($id);

        $validated = $request->validate([
            'approved_by' => 'required|exists:users,id',
            'rejection_reason' => 'required|string',
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => $validated['approved_by'],
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return response()->json($leave->load(['employee', 'approver']));
    }
}
