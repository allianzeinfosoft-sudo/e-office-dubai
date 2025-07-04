<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapItemLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'scrap_register_id', 'scrap_item_id', 'serial_no',
        'unit', 'quantity', 'rate', 'amount', 'remarks'
    ];

    public function register(){
        return $this->belongsTo(ScrapRegister::class);
    }

    public function item(){
        return $this->belongsTo(AssetItemMaster::class, 'scrap_item_id');
    }
}
