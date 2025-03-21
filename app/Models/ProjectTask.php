<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'task_name',
        'pr_task_id',
        'pr_sub_task_id'
    ];
}
