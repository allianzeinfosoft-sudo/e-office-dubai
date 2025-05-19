<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEntryBlockList extends Model
{
    use HasFactory;
    protected $fillable = [
        'block_date',
        'user_id',
        'username',
        'full_name',
        'status',
    ];
}
