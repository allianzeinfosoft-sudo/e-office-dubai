<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'employee_id',
        'salary_structure_id',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'total_employer_contribution',
        'ctc',
        'lop_days',
        'lop_amount',
        'attendance_days',
        'ot_amount',
        'is_part_wise',
        'part1_gross',
        'part1_deductions',
        'part1_net',
        'part2_gross',
        'part2_deductions',
        'part2_net',
        'remarks'
    ];

    public function batch()
    {
        return $this->belongsTo(PayrollBatch::class, 'batch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function structure()
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    public function components()
    {
        return $this->hasMany(PayrollComponent::class, 'payroll_entry_id');
    }

    public function part1Components()
    {
        return $this->hasMany(PayrollComponent::class, 'payroll_entry_id')->where('part_number', 1);
    }

    public function part2Components()
    {
        return $this->hasMany(PayrollComponent::class, 'payroll_entry_id')->where('part_number', 2);
    }

    /**
     * Get the amount for a specific component name.
     */
    public function getComponentAmount($name)
    {
        return $this->components->where('component_name', $name)->sum('amount');
    }

    /**
     * Virtual attributes to support older view logic or specific component lookups
     */
    public function getPfAmountAttribute()
    {
        // Try various common names for PF
        return $this->components
            ->where('type', 'deduction')
            ->filter(function ($c) {
                return stripos($c->component_name, 'PF') !== false || stripos($c->component_name, 'Provident') !== false;
            })->sum('amount');
    }

    public function getEsiAmountAttribute()
    {
        return $this->components
            ->where('type', 'deduction')
            ->filter(function ($c) {
                return stripos($c->component_name, 'ESI') !== false || stripos($c->component_name, 'Insurance') !== false;
            })->sum('amount');
    }

    public function getTdsAmountAttribute()
    {
        return $this->components
            ->where('type', 'deduction')
            ->filter(function ($c) {
                return stripos($c->component_name, 'TDS') !== false || stripos($c->component_name, 'Tax Deducted') !== false;
            })->sum('amount');
    }
}
