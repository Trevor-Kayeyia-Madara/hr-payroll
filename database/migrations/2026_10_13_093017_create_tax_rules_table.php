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
        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country_code', 2);
            $table->string('currency', 3)->default('USD');
            $table->json('paye_bands')->nullable();
            $table->decimal('personal_relief', 10, 2)->default(0);
            $table->decimal('insurance_relief_rate', 5, 4)->default(0);
            $table->decimal('insurance_relief_cap', 10, 2)->default(0);
            $table->decimal('pension_cap', 10, 2)->default(0);
            $table->decimal('mortgage_cap', 10, 2)->default(0);
            $table->decimal('nssf_rate', 5, 4)->default(0);
            $table->decimal('nssf_tier1_limit', 10, 2)->default(0);
            $table->decimal('nssf_tier2_limit', 10, 2)->default(0);
            $table->decimal('nssf_max', 10, 2)->default(0);
            $table->decimal('shif_rate', 5, 4)->default(0);
            $table->decimal('shif_min', 10, 2)->default(0);
            $table->decimal('housing_levy_rate', 5, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rules');
    }
};
