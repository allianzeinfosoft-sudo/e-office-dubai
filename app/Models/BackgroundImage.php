<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundImage extends Model
{
    use HasFactory;

    protected $fillable = ['background_type','image_id'];

     public function appearance()
    {
        return $this->belongsTo(Appearence::class, 'image_id', 'id');
    }
}
