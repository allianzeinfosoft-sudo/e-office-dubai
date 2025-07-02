<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParUserAssign extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'par_name',
        'par_code',
        'assigned_by',
        'par_start_date',
        'par_end_date',
        'par_submit_date',
        'status',
        'total_score',
        'maximum_score',
        'score_percentage',
        'grade',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function template()
    {
        return $this->belongsTo(ParTemplate::class,'template_id','id');
    }
    public function assigned_user()
    {
        return $this->belongsTo(Employee::class, 'assigned_by','user_id');
    }

     public function par_report()
    {
        return $this->hasMany(PerformanceAppraisalReport::class, 'par_id','id');
    }
}
