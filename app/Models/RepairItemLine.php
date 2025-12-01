<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairItemLine extends Model
{
    use HasFactory;
    public $fillable = [
        'repair_register_id',
        'item_master_id',
        'item_model',
        'serial_no',
        'asset_map_id',
        'unit',
        'quantity',
        'rate',
        'amount',
        'repair_date',
        'return_amount',
        'remarks',
        'status',
    ];

    public function register(){
        return $this->belongsTo(RepairRegister::class, 'repair_register_id', 'id');
    }

    public function item(){
        return $this->belongsTo(AssetItemMaster::class, 'item_master_id', 'id');
    }

    public function assetMapping(){
        return $this->belongsTo(AssetMapping::class, 'asset_map_id', 'id');
    }

}
