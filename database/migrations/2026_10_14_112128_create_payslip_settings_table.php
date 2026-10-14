<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payslip_settings', function (Blueprint $table) {
            $table->id();

            // Company Information
            $table->string('company_name')->default('SecureGuard HR');
            $table->string('company_description')->default('Security Company Management System');
            $table->string('company_address')->default('Nairobi, Kenya');
            $table->string('company_email')->default('hr@secureguard.co.ke');
            $table->string('company_phone')->default('+254 700 000 000');
            $table->string('company_tax_id')->nullable();
            $table->string('company_logo_url')->nullable();

            // Payslip Display Options
            $table->boolean('show_ytd')->default(true);
            $table->boolean('show_leave_balances')->default(true);
            $table->boolean('show_tax_id')->default(true);
            $table->boolean('show_payment_date')->default(true);
            $table->boolean('show_department')->default(true);
            $table->boolean('show_position')->default(true);
            $table->boolean('show_tax_rule')->default(true);

            // Payroll Calculation Labels
            $table->string('basic_salary_label')->default('Basic Salary');
            $table->string('overtime_label')->default('Overtime Pay');
            $table->string('allowances_label')->default('Allowances');
            $table->string('gross_pay_label')->default('Gross Pay');
            $table->string('paye_tax_label')->default('PAYE Tax (Income Tax)');
            $table->string('nssf_label')->default('NSSF Contribution (Social Security)');
            $table->string('shif_label')->default('SHIF Contribution (Health Insurance)');
            $table->string('housing_levy_label')->default('Housing Levy');
            $table->string('other_deductions_label')->default('Other Deductions (Loans/Advances)');
            $table->string('total_deductions_label')->default('Total Deductions');
            $table->string('net_pay_label')->default('Net Pay (Take-Home)');

            // Section Titles
            $table->string('earnings_section_title')->default('Earnings (Gross Pay)');
            $table->string('deductions_section_title')->default('Deductions');
            $table->string('ytd_section_title')->default('Year-to-Date (YTD) Totals');
            $table->string('leave_section_title')->default('Leave Balances');
            $table->string('employee_section_title')->default('Employee Information');

            // Footer Information
            $table->text('footer_text')->default('This is a computer-generated payslip. No signature required.');
            $table->text('footer_contact')->default('For any queries regarding this payslip, please contact the HR Department.');

            // Styling Options
            $table->string('primary_color')->default('#1e40af');
            $table->string('secondary_color')->default('#7c3aed');
            $table->string('accent_color')->default('#2563eb');

            // Layout Options
            $table->string('paper_size')->default('A4');
            $table->string('margin')->default('15mm');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslip_settings');
    }
};
