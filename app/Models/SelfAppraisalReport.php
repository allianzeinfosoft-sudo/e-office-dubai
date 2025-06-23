<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Template\Template;

class SelfAppraisalReport extends Model
{
    use HasFactory;

    protected $fillable = [
            'sar_id',
            'question',
            'mark',
            'comment',
        ];

    public function sarInfo()
    {
        return $this->belongsTo(SarUserAssign::class,'sar_id','id');
    }
}
