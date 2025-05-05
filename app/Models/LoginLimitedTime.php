<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLimitedTime extends Model
{
    use HasFactory;

    protected $fillable = ['limited_time'];
}
