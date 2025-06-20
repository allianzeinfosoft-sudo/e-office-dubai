<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCalendar extends Model
{
    use HasFactory;
     protected $fillable = ['title', 'label', 'start_date', 'end_date', 'all_day','url', 'guests', 'location', 'description'];
}
