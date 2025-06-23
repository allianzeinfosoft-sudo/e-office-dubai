<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Books extends Model
{
    use HasFactory;
    protected $fillable = ['reg_no', 'title', 'author', 'category_id', 'description','cover', 'status'];

    public function category(){
        return $this->belongsTo(BooksCategory::class, 'category_id', 'id');
    }
}
