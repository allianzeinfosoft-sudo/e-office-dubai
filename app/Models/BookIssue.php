<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{   
    use HasFactory;
    protected $fillable = ['book_id', 'issued_to', 'issue_date', 'return_date', 'status'];

    public function book() {
        return $this->belongsTo(Books::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class, 'issued_to', 'user_id');
    }
}
