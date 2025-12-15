<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [

        'training_title',
        'department_id',
        'start_date_time',
        'end_date_time',
        'training_details',
        'document',
        'status',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function trainingUsers()
    {
        return $this->hasMany(TrainingUser::class, 'training_id');
    }

}
