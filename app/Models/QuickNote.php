<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickNote extends Model{
    use HasFactory;
    protected $fillable = ['title','assigned_to','note_description','created_by'];

    public function assignedTo(){
        return $this->belongsTo(Employee::class,'assigned_to', 'user_id');
    }
    public function createdBy(){
        return $this->belongsTo(Employee::class,'created_by', 'user_id');
    }
    public function comments(){
        return $this->hasMany(QuickNoteComments::class, 'quick_note_id', 'id');
    }
}
