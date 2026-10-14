<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\TaxRule;

class PayrollCalculator
{
    public function calculate(Employee $employee, array $data): array
    {
        $taxRule = $employee->taxRule ?? TaxRule::where('is_active', true)->first();

        $basicSalary = $employee->salary;
        $overtimePay = ($data['overtime_hours'] ?? 0) * ($data['overtime_rate'] ?? $employee->salary / 22 / 8 * 1.5);
        $allowances = $data['allowances'] ?? 0;
        $grossPay = $basicSalary + $overtimePay + $allowances;

        $deductions = $data['other_deductions'] ?? 0;

        // Calculate tax deductions
        $payeTax = $this->calculatePAYE($grossPay, $taxRule);
        $nssf = $this->calculateNSSF($basicSalary, $taxRule);
        $shif = $this->calculateSHIF($basicSalary, $taxRule);
        $housingLevy = $this->calculateHousingLevy($basicSalary, $taxRule);

        $totalDeductions = $deductions + $payeTax + $nssf + $shif + $housingLevy;
        $netPay = $grossPay - $totalDeductions;

        return [
            'employee_id' => $employee->id,
            'period' => $data['period'],
            'gross_pay' => $grossPay,
            'basic_salary' => $basicSalary,
            'overtime_pay' => $overtimePay,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'paye_tax' => $payeTax,
            'nssf' => $nssf,
            'shif' => $shif,
            'housing_levy' => $housingLevy,
            'net_pay' => $netPay,
            'status' => 'pending',
        ];
    }

    protected function calculatePAYE(float $taxableIncome, ?TaxRule $taxRule): float
    {
        if (!$taxRule || !$taxRule->paye_bands) {
            return 0;
        }

        $paye = 0;
        $previousLimit = 0;
        $bands = $taxRule->paye_bands;

        foreach ($bands as $band) {
            if ($band['limit'] === null) {
                // This is the final band - apply rate to remaining income
                $paye += ($taxableIncome - $previousLimit) * $band['rate'];
                break;
            }

            if ($taxableIncome > $previousLimit + $band['limit']) {
                $paye += $band['limit'] * $band['rate'];
                $previousLimit += $band['limit'];
            } else {
                $paye += ($taxableIncome - $previousLimit) * $band['rate'];
                break;
            }
        }

        // Apply personal relief
        $paye = max(0, $paye - $taxRule->personal_relief);

        return round($paye, 2);
    }

    protected function calculateNSSF(float $salary, ?TaxRule $taxRule): float
    {
        if (!$taxRule || $taxRule->nssf_rate == 0) {
            return 0;
        }

        $nssf = $salary * $taxRule->nssf_rate;

        // Apply tier limits if configured
        if ($taxRule->nssf_tier1_limit > 0 && $salary <= $taxRule->nssf_tier1_limit) {
            return min($nssf, $taxRule->nssf_max);
        }

        if ($taxRule->nssf_max > 0) {
            return min($nssf, $taxRule->nssf_max);
        }

        return round($nssf, 2);
    }

    protected function calculateSHIF(float $salary, ?TaxRule $taxRule): float
    {
        if (!$taxRule || $taxRule->shif_rate == 0) {
            return 0;
        }

        $shif = $salary * $taxRule->shif_rate;

        // Apply minimum if configured
        if ($taxRule->shif_min > 0) {
            $shif = max($shif, $taxRule->shif_min);
        }

        return round($shif, 2);
    }

    protected function calculateHousingLevy(float $salary, ?TaxRule $taxRule): float
    {
        if (!$taxRule || $taxRule->housing_levy_rate == 0) {
            return 0;
        }

        return round($salary * $taxRule->housing_levy_rate, 2);
    }
}
