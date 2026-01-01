<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id','title','total_marks','start_at','end_at','status'
    ];

    public function training() {
        return $this->belongsTo(Training::class);
    }

    public function questions() {
        return $this->hasMany(TrainingTestQuestion::class);
    }

    public function trainingTestUsers() {
        return $this->hasMany(TrainingTestUser::class);
    }
}
