<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thoughts extends Model
{
    use HasFactory;

    protected $fillable = [

        'thoughts_title',
        'display_date',
        'thoughts_details',
        'picture',
    ];
}
