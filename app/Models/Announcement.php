<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_announcement',
        'display_start_date',
        'display_end_date',
        'description',
        'picture',
        'readers'
    ];

     protected $casts = [
        'readers' => 'array', // 👈 Ensure JSON casting
    ];


}
