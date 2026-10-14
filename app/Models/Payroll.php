<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'period',
        'gross_pay',
        'basic_salary',
        'overtime_pay',
        'allowances',
        'deductions',
        'paye_tax',
        'nssf',
        'shif',
        'housing_levy',
        'net_pay',
        'status',
        'notes',
        'processed_at',
    ];

    protected $casts = [
        'gross_pay' => 'decimal:2',
        'basic_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'paye_tax' => 'decimal:2',
        'nssf' => 'decimal:2',
        'shif' => 'decimal:2',
        'housing_levy' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
