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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('period'); // e.g., "2026-10"
            $table->decimal('gross_pay', 10, 2)->default(0);
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('overtime_pay', 10, 2)->default(0);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('paye_tax', 10, 2)->default(0);
            $table->decimal('nssf', 10, 2)->default(0);
            $table->decimal('shif', 10, 2)->default(0);
            $table->decimal('housing_levy', 10, 2)->default(0);
            $table->decimal('net_pay', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
