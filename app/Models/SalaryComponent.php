<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryComponent extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'is_statutory', 'is_variable', 'is_ctc_variable', 'is_attendance_based', 'status'];

    public function structures()
    {
        return $this->belongsToMany(SalaryStructure::class, 'salary_structure_component')
            ->withPivot('calculation_type', 'value')
            ->withTimestamps();
    }
}
