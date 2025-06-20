<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyReport extends Model
{
    use HasFactory;

     protected $fillable = [
            'survey_id',
            'question',
            'answer_type',
            'answer',
        ];

    public function parInfo()
    {
        return $this->belongsTo(SurveyUserAssign::class,'par_id','id');
    }
}
