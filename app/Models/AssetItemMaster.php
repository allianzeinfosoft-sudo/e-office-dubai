<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetItemMaster extends Model
{
    use HasFactory;
    protected $fillable = ['item_code','name','description','brand','status'];
}
