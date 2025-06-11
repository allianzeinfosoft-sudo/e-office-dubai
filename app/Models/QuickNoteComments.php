<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickNoteComments extends Model
{
    use HasFactory;
    protected $fillable = ['quick_note_id','commented_by', 'comment'];

    public function employee(){
        return $this->belongsTo(Employee::class, 'commented_by', 'user_id');
    }
}
