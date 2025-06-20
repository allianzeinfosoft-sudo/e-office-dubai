<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ksp extends Model
{
    use HasFactory;
    protected $fillable = ['ksp_title', 'ksp_featured_image', 'ksp_category', 'ksp_description', 'created_by'];
    public function category(){
        return $this->belongsTo(KspCategory::class, 'ksp_category', 'id');
    }
    public function createdBy(){
        return $this->belongsTo(Employee::class, 'created_by', 'user_id');
    }
}
