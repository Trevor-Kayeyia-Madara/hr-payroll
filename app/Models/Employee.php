<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'name',
        'email',
        'phone',
        'department_id',
        'position',
        'salary',
        'hire_date',
        'status',
        'tax_rule_id',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'hire_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function taxRule()
    {
        return $this->belongsTo(TaxRule::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }
}
