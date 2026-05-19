<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 
        'salary_structure_id', 
        'base_amount', 
        'effective_date', 
        'status',
        'monthly_ctc',
        'annual_ctc',
        'pf_eligible',
        'esi_eligible'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'pf_eligible' => 'boolean',
        'esi_eligible' => 'boolean',
    ];

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
        return $this->belongsToMany(SalaryComponent::class, 'employee_salary_assignment_components')
            ->withPivot('amount', 'is_editable', 'sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order', 'asc');
    }
}
