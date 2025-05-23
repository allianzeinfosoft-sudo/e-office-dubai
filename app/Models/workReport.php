<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workReport extends Model
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
        'emergency',
        'break_time'
    ];

    // Relationship with Employee
    public function user()
    {
        return $this->belongsTo(User::class, 'emp_id');
    }

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_name');
    }

    // Relationship with Type of Work
    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class, 'type_of_work'); // Assuming type_of_work stores an ID
    }

    public function tasks()
    {
        return $this->belongsTo(Tasks::class, 'type_of_work'); // Assuming type_of_work stores an ID
    }
    public function employee()  {
        return $this->belongsTo(Employee::class, 'emp_id', 'user_id');
    }    

}
