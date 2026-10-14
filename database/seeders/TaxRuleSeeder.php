<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kenya PAYE example
        \App\Models\TaxRule::create([
            'name' => 'Kenya PAYE (Monthly)',
            'country_code' => 'KE',
            'currency' => 'KES',
            'paye_bands' => [
                ['limit' => 24000, 'rate' => 0.10],
                ['limit' => 8333, 'rate' => 0.25],
                ['limit' => 467667, 'rate' => 0.30],
                ['limit' => 300000, 'rate' => 0.325],
                ['limit' => null, 'rate' => 0.35],
            ],
            'personal_relief' => 2400,
            'insurance_relief_rate' => 0.15,
            'insurance_relief_cap' => 5000,
            'pension_cap' => 30000,
            'mortgage_cap' => 30000,
            'nssf_rate' => 0.06,
            'nssf_tier1_limit' => 9000,
            'nssf_tier2_limit' => 108000,
            'nssf_max' => 6480,
            'shif_rate' => 0.0275,
            'shif_min' => 300,
            'housing_levy_rate' => 0.015,
            'is_active' => true,
        ]);

        // US example (simplified)
        \App\Models\TaxRule::create([
            'name' => 'US Federal Tax (Annual)',
            'country_code' => 'US',
            'currency' => 'USD',
            'paye_bands' => json_encode([
                ['limit' => 11000, 'rate' => 0.10],
                ['limit' => 33725, 'rate' => 0.12],
                ['limit' => 44725, 'rate' => 0.22],
                ['limit' => 95375, 'rate' => 0.24],
                ['limit' => 182100, 'rate' => 0.32],
                ['limit' => 231250, 'rate' => 0.35],
                ['limit' => 578125, 'rate' => 0.37],
                ['limit' => null, 'rate' => 0.40],
            ]),
            'personal_relief' => 0,
            'insurance_relief_rate' => 0,
            'insurance_relief_cap' => 0,
            'pension_cap' => 0,
            'mortgage_cap' => 0,
            'nssf_rate' => 0,
            'nssf_tier1_limit' => 0,
            'nssf_tier2_limit' => 0,
            'nssf_max' => 0,
            'shif_rate' => 0,
            'shif_min' => 0,
            'housing_levy_rate' => 0,
            'is_active' => false,
        ]);
    }
}
