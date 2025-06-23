<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyUserAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'assigned_by',
        'survey_start_date',
        'survey_end_date',
        'survey_submit_date',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class,'template_id','id');
    }
    public function assigned_user()
    {
        return $this->belongsTo(Employee::class, 'assigned_by','user_id');
    }
}
