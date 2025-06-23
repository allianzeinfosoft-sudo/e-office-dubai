<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackReport extends Model
{
    use HasFactory;

    protected $fillable = [
            'feedback_assign_id',
            'question',
            'mark',
            'comment',
        ];

    public function feedbackInfo()
    {
        return $this->belongsTo(FeedbackAssign::class,'feedback_assign_id','id');
    }
}
