<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipSetting extends Model
{
    protected $fillable = [
        // Company Information
        'company_name',
        'company_description',
        'company_address',
        'company_email',
        'company_phone',
        'company_tax_id',
        'company_logo_url',

        // Payslip Display Options
        'show_ytd',
        'show_leave_balances',
        'show_tax_id',
        'show_payment_date',
        'show_department',
        'show_position',
        'show_tax_rule',

        // Payroll Calculation Labels
        'basic_salary_label',
        'overtime_label',
        'allowances_label',
        'gross_pay_label',
        'paye_tax_label',
        'nssf_label',
        'shif_label',
        'housing_levy_label',
        'other_deductions_label',
        'total_deductions_label',
        'net_pay_label',

        // Section Titles
        'earnings_section_title',
        'deductions_section_title',
        'ytd_section_title',
        'leave_section_title',
        'employee_section_title',

        // Footer Information
        'footer_text',
        'footer_contact',

        // Styling Options
        'primary_color',
        'secondary_color',
        'accent_color',

        // Layout Options
        'paper_size',
        'margin',
    ];

    protected $casts = [
        'show_ytd' => 'boolean',
        'show_leave_balances' => 'boolean',
        'show_tax_id' => 'boolean',
        'show_payment_date' => 'boolean',
        'show_department' => 'boolean',
        'show_position' => 'boolean',
        'show_tax_rule' => 'boolean',
    ];

    /**
     * Get the single payslip setting record
     */
    public static function getSettings()
    {
        return self::first() ?? self::createDefault();
    }

    /**
     * Create default payslip settings
     */
    public static function createDefault()
    {
        return self::create([
            'company_name' => 'SecureGuard HR',
            'company_description' => 'Security Company Management System',
            'company_address' => 'Nairobi, Kenya',
            'company_email' => 'hr@secureguard.co.ke',
            'company_phone' => '+254 700 000 000',
            'company_tax_id' => 'PVT-001234567',

            'show_ytd' => true,
            'show_leave_balances' => true,
            'show_tax_id' => true,
            'show_payment_date' => true,
            'show_department' => true,
            'show_position' => true,
            'show_tax_rule' => true,

            'basic_salary_label' => 'Basic Salary',
            'overtime_label' => 'Overtime Pay',
            'allowances_label' => 'Allowances',
            'gross_pay_label' => 'Gross Pay',
            'paye_tax_label' => 'PAYE Tax (Income Tax)',
            'nssf_label' => 'NSSF Contribution (Social Security)',
            'shif_label' => 'SHIF Contribution (Health Insurance)',
            'housing_levy_label' => 'Housing Levy',
            'other_deductions_label' => 'Other Deductions (Loans/Advances)',
            'total_deductions_label' => 'Total Deductions',
            'net_pay_label' => 'Net Pay (Take-Home)',

            'earnings_section_title' => 'Earnings (Gross Pay)',
            'deductions_section_title' => 'Deductions',
            'ytd_section_title' => 'Year-to-Date (YTD) Totals',
            'leave_section_title' => 'Leave Balances',
            'employee_section_title' => 'Employee Information',

            'footer_text' => 'This is a computer-generated payslip. No signature required.',
            'footer_contact' => 'For any queries regarding this payslip, please contact the HR Department.',

            'primary_color' => '#1e40af',
            'secondary_color' => '#7c3aed',
            'accent_color' => '#2563eb',

            'paper_size' => 'A4',
            'margin' => '15mm',
        ]);
    }
}
