<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshift extends Model
{
    use HasFactory;
    protected $fillable= [
        'shift_id','shift_start_time','shift_end_time','mini_break_time', 'max_break_time'
    ];
}
