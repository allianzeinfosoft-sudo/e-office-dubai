<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAppraisalReport extends Model
{
    use HasFactory;

     protected $fillable = [
            'par_id',
            'question',
            'comment',
            'mark',
        ];

    public function parInfo()
    {
        return $this->belongsTo(ParUserAssign::class,'par_id','id');
    }
}
