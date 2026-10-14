<?php

namespace Database\Seeders;

use App\Models\PayslipSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayslipSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PayslipSetting::createDefault();
    }
}
