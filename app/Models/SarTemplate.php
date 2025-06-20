<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SarTemplate extends Model
{
    use HasFactory;
    protected $fillable = [

        'template_name',
        'department_id',
        'created_by'
    ];

    public function department_info()
    {
        return $this->belongsTo(Department::class, 'department_id','id');
    }

    public function questions()
    {
        return $this->hasMany(SarQuestion::class, 'template_id');
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by','user_id');
    }

    public function userAssignments()
    {
        return $this->hasMany(SarUserAssign::class, 'template_id');
    }


}
