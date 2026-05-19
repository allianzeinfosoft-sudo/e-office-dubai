<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollBatch extends Model
{
    use HasFactory;

    protected $fillable = ['month', 'year', 'department_id', 'salary_structure_id', 'is_part_wise', 'status', 'processed_by', 'processed_at'];

    protected $casts = [
        'processed_at' => 'datetime',
        'is_part_wise' => 'boolean',
    ];

    public function entries()
    {
        return $this->hasMany(PayrollEntry::class, 'batch_id');
    }

    public function structure()
    {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
