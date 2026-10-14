<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PayslipSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PayslipSettingController extends Controller
{
    /**
     * Get payslip settings
     */
    public function index(): JsonResponse
    {
        $settings = PayslipSetting::getSettings();
        return response()->json($settings);
    }

    /**
     * Update payslip settings
     */
    public function update(Request $request): JsonResponse
    {
        $settings = PayslipSetting::getSettings();

        $validated = $request->validate([
            // Company Information
            'company_name' => 'sometimes|required|string|max:255',
            'company_description' => 'sometimes|required|string|max:255',
            'company_address' => 'sometimes|required|string|max:255',
            'company_email' => 'sometimes|required|email|max:255',
            'company_phone' => 'sometimes|required|string|max:20',
            'company_tax_id' => 'nullable|string|max:50',
            'company_logo_url' => 'nullable|string|max:500',

            // Payslip Display Options
            'show_ytd' => 'sometimes|required|boolean',
            'show_leave_balances' => 'sometimes|required|boolean',
            'show_tax_id' => 'sometimes|required|boolean',
            'show_payment_date' => 'sometimes|required|boolean',
            'show_department' => 'sometimes|required|boolean',
            'show_position' => 'sometimes|required|boolean',
            'show_tax_rule' => 'sometimes|required|boolean',

            // Payroll Calculation Labels
            'basic_salary_label' => 'sometimes|required|string|max:100',
            'overtime_label' => 'sometimes|required|string|max:100',
            'allowances_label' => 'sometimes|required|string|max:100',
            'gross_pay_label' => 'sometimes|required|string|max:100',
            'paye_tax_label' => 'sometimes|required|string|max:100',
            'nssf_label' => 'sometimes|required|string|max:100',
            'shif_label' => 'sometimes|required|string|max:100',
            'housing_levy_label' => 'sometimes|required|string|max:100',
            'other_deductions_label' => 'sometimes|required|string|max:100',
            'total_deductions_label' => 'sometimes|required|string|max:100',
            'net_pay_label' => 'sometimes|required|string|max:100',

            // Section Titles
            'earnings_section_title' => 'sometimes|required|string|max:100',
            'deductions_section_title' => 'sometimes|required|string|max:100',
            'ytd_section_title' => 'sometimes|required|string|max:100',
            'leave_section_title' => 'sometimes|required|string|max:100',
            'employee_section_title' => 'sometimes|required|string|max:100',

            // Footer Information
            'footer_text' => 'sometimes|required|string',
            'footer_contact' => 'sometimes|required|string',

            // Styling Options
            'primary_color' => 'sometimes|required|string|max:20',
            'secondary_color' => 'sometimes|required|string|max:20',
            'accent_color' => 'sometimes|required|string|max:20',

            // Layout Options
            'paper_size' => 'sometimes|required|string|max:10',
            'margin' => 'sometimes|required|string|max:10',
        ]);

        $settings->update($validated);
        return response()->json($settings);
    }

    /**
     * Reset payslip settings to default
     */
    public function reset(): JsonResponse
    {
        $settings = PayslipSetting::getSettings();
        $default = PayslipSetting::createDefault();
        $settings->update($default->toArray());
        return response()->json($settings);
    }
}
