<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SarQuestion extends Model
{
    use HasFactory;
    protected $casts = [
        'options' => 'array',
    ];
    protected $fillable = [
        'template_id',
        'question',
    ];

    public function template()
    {
        return $this->belongsTo(SarTemplate::class, 'template_id','id');
    }
}
