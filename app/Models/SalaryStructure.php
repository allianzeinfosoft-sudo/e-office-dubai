<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function assignments()
    {
        return $this->hasMany(EmployeeSalaryAssignment::class);
    }

    public function components()
    {
        return $this->belongsToMany(SalaryComponent::class, 'salary_structure_component')
            ->withPivot('calculation_type', 'value', 'is_editable')
            ->withTimestamps();
    }
}
