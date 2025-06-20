<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;
    protected $casts = [
        'options' => 'array',
    ];
    protected $fillable = [
        'feedback_id',
        'question',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class, 'feedback_id','id');
    }
}
