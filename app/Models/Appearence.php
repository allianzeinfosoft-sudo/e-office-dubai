<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appearence extends Model
{
    use HasFactory;

    protected $fillable = ['background_type','image'];
}
