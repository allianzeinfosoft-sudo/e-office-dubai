<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
     use HasFactory;
    protected $casts = [
        'options' => 'array',
    ];
    protected $fillable = [
        'template_id',
        'question',
        'answer_type',
        'options',
    ];

    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class, 'template_id','id');
    }
}
