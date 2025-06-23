<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\Template\Template;

class SarUserAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'assigned_by',
        'sar_start_date',
        'sar_end_date',
        'sar_submit_date',
        'status',
        'total_score',
        'maximum_score',
        'score_percentage',
        'grade',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

    public function template()
    {
        return $this->belongsTo(SarTemplate::class,'template_id','id');
    }
    public function assigned_user()
    {
        return $this->belongsTo(Employee::class, 'assigned_by','user_id');
    }
}
