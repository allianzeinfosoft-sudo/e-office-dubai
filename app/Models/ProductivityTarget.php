<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductivityTarget extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'project_task_id',
        'assignedBy',
        'target_month',
        'target_year',
        'rph',
    ];

    // Relationship with Project
    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Relationship with ProjectTask
    public function projectTask(){
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    // Relationship with Employee (assignedBy)
    public function employee(){
        return $this->belongsTo(Employee::class, 'assignedBy');
    }
}
