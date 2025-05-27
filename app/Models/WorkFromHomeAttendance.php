<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkFromHomeAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'emp_id',
        'signin_date',
        'signin_time',
        'signout_date',
        'signout_time',
        'working_hours',
        'break_time',
        'status',
        'ipaddress',
        'is_incomplete',
        'created_by',
    ];
}
