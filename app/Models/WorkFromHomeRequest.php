<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkFromHomeRequest extends Model
{
    protected $fillable = [
        'emp_id',
        'from_date',
        'to_date',
        'request_type',
        'attendance_option',
        'reason',
        'status',
        'approved_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'user_id');
    }
}
