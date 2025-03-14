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
        $this->hasOne(User::class,'user_id','id');
    }

}
