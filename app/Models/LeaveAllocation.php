<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','total_leaves','used_leaves', 'remaining_leaves', 'year'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id');
    }
}
