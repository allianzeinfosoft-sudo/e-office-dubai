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
        return $this->belongsTo(Project::class, 'project_id'); 
    }


    public function employee(){
        return $this->belongsTo(Employee::class, 'reporting_to', 'id'); 
    }

    public function tasks(){
        return $this->belongsTo(Tasks::class, 'task_name', 'id'); 
    }
}
