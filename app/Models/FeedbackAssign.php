<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feedback_id',
        'assigned_by',
        'feedback_start_date',
        'feedback_end_date',
        'feedback_submit_date',
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

    public function feedback()
    {
        return $this->belongsTo(Feedback::class,'feedback_id','id');
    }
    public function assigned_user()
    {
        return $this->belongsTo(Employee::class, 'assigned_by','user_id');
    }
}
