<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'task_name',
        'reporting_to',
        'members'
    ];

    public function project(){
        return $this->belongsTo(Project::class, 'project_id'); // Ensure 'project_id' exists in 'project_tasks' table
    }
}
