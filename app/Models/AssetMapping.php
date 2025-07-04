<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMapping extends Model
{
    use HasFactory;
    protected $fillable = [
        'master_item_id',
        'register_lineitem_id', 
        'item_number', 
        'allocation_id', 
        'scrap_id', 
        'repair_id', 
        'allocation_status', 
        'status'];

}
