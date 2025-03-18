<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';
    protected $fillable = ['leave_from','leave_to','user_id','leave_type','reason'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class,'user_id','user_id');
    }

}
