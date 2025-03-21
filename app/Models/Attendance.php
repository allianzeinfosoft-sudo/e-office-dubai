<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'emp_id',
        'signin_date',
        'signin_time',
        'signin_late_note',
        'signout_time',
        'signout_late_note',
        'working_hours',
        'break_time',
        'status',
        'pre_experience',
        'punchin_type',
        'punchout_type',
        'custom_status',
        'signout_date',
        'actual_signout_date',
        'pending',
        'ipaddress',
    ];
}
