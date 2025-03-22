<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'emp_id',
        'picktime',
        'reason',
        'signin_date',
        'status',
        'approved_by',
    ];
}

