<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickNote extends Model{
    use HasFactory;
    protected $fillable = ['title','assigned_to','note_description','created_by'];
}
