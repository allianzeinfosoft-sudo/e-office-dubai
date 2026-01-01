<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestUser extends Model
{
    use HasFactory;

      protected $fillable = [
        'training_test_id',
        'user_id',
        'acceptance_status',
        'attempt_status',
        'status',
        'score',
        'result'
    ];

    protected $attributes = [
        'acceptance_status' => 'pending',
        'attempt_status'    => 'not_started',
    ];

    public function answers() {
        return $this->hasMany(TrainingTestAnswer::class);
    }


     public function test()
    {
        return $this->belongsTo(
            TrainingTest::class,
            'training_test_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
