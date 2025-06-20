<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParQuestion extends Model
{

    use HasFactory;
    protected $fillable = [
        'template_id',
        'question',
    ];

    public function template()
    {
        return $this->belongsTo(ParTemplate::class, 'template_id','id');
    }
}
