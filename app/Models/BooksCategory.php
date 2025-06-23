<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksCategory extends Model
{
    use HasFactory;
    
    protected $table = 'books_categories';
    protected $fillable = ['name', 'parent_id'];
    public function parent()
    {
        return $this->belongsTo(BooksCategory::class, 'parent_id');
    }
    public function books()
    {
        return $this->hasMany(Books::class);
    }
}
