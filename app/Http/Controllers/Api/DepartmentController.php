<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function index(): JsonResponse
    {
        $departments = Department::with('employees')->get();
        return response()->json($departments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
        ]);

        $department = Department::create($validated);
        return response()->json($department, 201);
    }

    public function show(string $id): JsonResponse
    {
        $department = Department::with('employees')->findOrFail($id);
        return response()->json($department);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);
        return response()->json($department);
    }

    public function destroy(string $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return response()->json(null, 204);
    }
}
