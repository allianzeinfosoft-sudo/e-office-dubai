<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRegister extends Model
{
    use HasFactory;
    public $fillable = ['repair_no', 'repair_date', 'vendor_id', 'status', 'return_date', 'total_amount', 'remarks'];

    public function items(){
        return $this->hasMany(RepairItemLine::class, 'repair_register_id', 'id');
    }

    public function vendor(){
        return $this->belongsTo(AssetVendors::class, 'vendor_id', 'id'); 
    }
    
}
