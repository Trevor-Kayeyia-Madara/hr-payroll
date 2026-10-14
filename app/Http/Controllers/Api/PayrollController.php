<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PayrollController extends Controller
{
    protected $payrollCalculator;

    public function __construct(PayrollCalculator $payrollCalculator)
    {
        $this->payrollCalculator = $payrollCalculator;
    }

    public function index(Request $request): JsonResponse
    {
        $query = Payroll::with('employee.taxRule');

        if ($request->has('period')) {
            $query->where('period', $request->period);
        }

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('period', 'desc')->orderBy('created_at', 'desc')->get();
        return response()->json($payrolls);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string', // Format: YYYY-MM
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $payrollData = $this->payrollCalculator->calculate($employee, $validated);

        $payroll = Payroll::create($payrollData);
        return response()->json($payroll->load('employee.taxRule'), 201);
    }

    public function show(string $id): JsonResponse
    {
        $payroll = Payroll::with('employee.taxRule')->findOrFail($id);
        return response()->json($payroll);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $payroll = Payroll::findOrFail($id);

        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,processed',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'processed') {
            $validated['processed_at'] = now();
        }

        $payroll->update($validated);
        return response()->json($payroll->load('employee.taxRule'));
    }

    public function destroy(string $id): JsonResponse
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return response()->json(null, 204);
    }

    public function generatePeriod(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'period' => 'required|string', // Format: YYYY-MM
        ]);

        $employees = Employee::where('status', 'active')->get();
        $payrolls = [];

        foreach ($employees as $employee) {
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                ->where('period', $validated['period'])
                ->first();

            if (!$existingPayroll) {
                $payrollData = $this->payrollCalculator->calculate($employee, [
                    'period' => $validated['period'],
                ]);
                $payroll = Payroll::create($payrollData);
                $payrolls[] = $payroll->load('employee.taxRule');
            }
        }

        return response()->json([
            'message' => 'Payroll generated successfully',
            'payrolls' => $payrolls,
        ]);
    }
}
