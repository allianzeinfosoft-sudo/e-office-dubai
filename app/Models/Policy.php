<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $fillable = [
        'policyTitle',
        'policyStartDate',
        'pollicyEndDate',
        'department_id',
        'project_id',
        'role_id',
        'descriptions',
        'attachments',
    ];

    public function department(){
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }

}
