<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_test_id','question',
        'option_a','option_b','option_c','option_d',
        'correct_option','marks'
    ];
}
