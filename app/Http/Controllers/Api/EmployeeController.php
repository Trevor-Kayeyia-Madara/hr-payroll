<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function index(): JsonResponse
    {
        $employees = Employee::with(['department', 'taxRule'])->get();
        return response()->json($employees);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_number' => 'required|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'tax_rule_id' => 'nullable|exists:tax_rules,id',
        ]);

        $employee = Employee::create($validated);
        return response()->json($employee->load(['department', 'taxRule']), 201);
    }

    public function show(string $id): JsonResponse
    {
        $employee = Employee::with(['department', 'taxRule', 'attendances', 'payrolls'])->findOrFail($id);
        return response()->json($employee);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'employee_number' => 'sometimes|required|unique:employees,employee_number,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'nullable|string|max:255',
            'salary' => 'sometimes|required|numeric|min:0',
            'hire_date' => 'nullable|date',
            'status' => 'sometimes|required|in:active,inactive',
            'tax_rule_id' => 'nullable|exists:tax_rules,id',
        ]);

        $employee->update($validated);
        return response()->json($employee->load(['department', 'taxRule']));
    }

    public function destroy(string $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(null, 204);
    }
}
