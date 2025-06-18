<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkFromHomeReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'emp_id',
        'project_name',
        'type_of_work',
        'time_of_work',
        'total_time',
        'comments',
        'report_date',
        'total_records',
        'productivity_hour',
        'wfh_attendance_id',
    ];

   
}
