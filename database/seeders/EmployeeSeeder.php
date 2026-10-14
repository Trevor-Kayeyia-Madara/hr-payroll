<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = \App\Models\Department::pluck('id', 'name');
        $taxRule = \App\Models\TaxRule::where('country_code', 'KE')->first();

        $employees = [
            // Management
            [
                'employee_number' => 'EMP001',
                'name' => 'John Kamau',
                'email' => 'john.kamau@security.co.ke',
                'phone' => '+254712345678',
                'department_id' => $departments['Operations'] ?? $departments->first(),
                'position' => 'Operations Manager',
                'salary' => 250000,
                'hire_date' => now()->subMonths(24),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            [
                'employee_number' => 'EMP002',
                'name' => 'Mary Wanjiku',
                'email' => 'mary.wanjiku@security.co.ke',
                'phone' => '+254712345679',
                'department_id' => $departments['Human Resources'] ?? $departments->first(),
                'position' => 'HR Manager',
                'salary' => 200000,
                'hire_date' => now()->subMonths(18),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            [
                'employee_number' => 'EMP003',
                'name' => 'David Ochieng',
                'email' => 'david.ochieng@security.co.ke',
                'phone' => '+254712345680',
                'department_id' => $departments['Finance'] ?? $departments->first(),
                'position' => 'Finance Manager',
                'salary' => 220000,
                'hire_date' => now()->subMonths(20),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            // Patrol Services
            [
                'employee_number' => 'EMP004',
                'name' => 'Peter Njoroge',
                'email' => 'peter.njoroge@security.co.ke',
                'phone' => '+254712345681',
                'department_id' => $departments['Patrol Services'] ?? $departments->first(),
                'position' => 'Patrol Supervisor',
                'salary' => 120000,
                'hire_date' => now()->subMonths(15),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            [
                'employee_number' => 'EMP005',
                'name' => 'James Mwangi',
                'email' => 'james.mwangi@security.co.ke',
                'phone' => '+254712345682',
                'department_id' => $departments['Patrol Services'] ?? $departments->first(),
                'position' => 'Patrol Officer',
                'salary' => 65000,
                'hire_date' => now()->subMonths(8),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            // Static Guarding (Askaris)
            [
                'employee_number' => 'EMP006',
                'name' => 'Samuel Kipkorir',
                'email' => 'samuel.kipkorir@security.co.ke',
                'phone' => '+254712345683',
                'department_id' => $departments['Static Guarding'] ?? $departments->first(),
                'position' => 'Senior Guard (Askari)',
                'salary' => 55000,
                'hire_date' => now()->subMonths(12),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            [
                'employee_number' => 'EMP007',
                'name' => 'Joseph Kiplagat',
                'email' => 'joseph.kiplagat@security.co.ke',
                'phone' => '+254712345684',
                'department_id' => $departments['Static Guarding'] ?? $departments->first(),
                'position' => 'Guard (Askari)',
                'salary' => 45000,
                'hire_date' => now()->subMonths(6),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            [
                'employee_number' => 'EMP008',
                'name' => 'Benard Kipchoge',
                'email' => 'benard.kipchoge@security.co.ke',
                'phone' => '+254712345685',
                'department_id' => $departments['Static Guarding'] ?? $departments->first(),
                'position' => 'Guard (Askari)',
                'salary' => 45000,
                'hire_date' => now()->subMonths(4),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            // Control Room
            [
                'employee_number' => 'EMP009',
                'name' => 'Grace Akinyi',
                'email' => 'grace.akinyi@security.co.ke',
                'phone' => '+254712345686',
                'department_id' => $departments['Control Room'] ?? $departments->first(),
                'position' => 'Control Room Operator',
                'salary' => 70000,
                'hire_date' => now()->subMonths(10),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            // Training
            [
                'employee_number' => 'EMP010',
                'name' => 'Anthony Omondi',
                'email' => 'anthony.omondi@security.co.ke',
                'phone' => '+254712345687',
                'department_id' => $departments['Training'] ?? $departments->first(),
                'position' => 'Training Instructor',
                'salary' => 90000,
                'hire_date' => now()->subMonths(14),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
            // Logistics
            [
                'employee_number' => 'EMP011',
                'name' => 'Francis Mutua',
                'email' => 'francis.mutua@security.co.ke',
                'phone' => '+254712345688',
                'department_id' => $departments['Logistics'] ?? $departments->first(),
                'position' => 'Logistics Coordinator',
                'salary' => 80000,
                'hire_date' => now()->subMonths(11),
                'status' => 'active',
                'tax_rule_id' => $taxRule->id,
            ],
        ];

        foreach ($employees as $employee) {
            \App\Models\Employee::create($employee);
        }
    }
}
