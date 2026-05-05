<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshiftDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshift_id',
        'day',
        'shift_start_time',
        'shift_end_time',
        'mini_break_time',
        'max_break_time',
    ];

    public function workshift()
    {
        return $this->belongsTo(Workshift::class);
    }
}
