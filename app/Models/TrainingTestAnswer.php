<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_test_user_id',
        'training_test_question_id',
        'selected_option',
        'is_correct'
    ];

    public function testUser()
    {
        return $this->belongsTo(TrainingTestUser::class, 'training_test_user_id');
    }

    public function question()
    {
        return $this->belongsTo(TrainingTestQuestion::class, 'training_test_question_id');
    }
}
