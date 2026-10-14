<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Staff recruitment, training, and welfare'],
            ['name' => 'Finance', 'description' => 'Financial management and payroll'],
            ['name' => 'Operations', 'description' => 'Daily operations and logistics'],
            ['name' => 'Patrol Services', 'description' => 'Mobile patrol and response teams'],
            ['name' => 'Static Guarding', 'description' => 'Fixed post security services'],
            ['name' => 'Control Room', 'description' => 'Monitoring and communications center'],
            ['name' => 'Training', 'description' => 'Security training and certification'],
            ['name' => 'Logistics', 'description' => 'Equipment and vehicle management'],
        ];

        foreach ($departments as $department) {
            \App\Models\Department::create($department);
        }
    }
}
