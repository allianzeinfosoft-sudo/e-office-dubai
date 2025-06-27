<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshift extends Model
{
    use HasFactory;
    protected $fillable= [
        'shift_id','department','shift_start_time','shift_end_time','login_limited_time','mini_break_time', 'max_break_time'
    ];

    public function shift_department()
    {
         return $this->belongsTo(Department::class, 'department', 'id');
    }
}
