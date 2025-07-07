<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'scrap_no', 'scrap_date', 'scrap_vendor_id',
        'total_weight', 'total_amount', 'remarks'
    ];

    public function items()
    {
        return $this->hasMany(ScrapItemLine::class);
    }

    public function vendor()
    {
        return $this->belongsTo(AssetVendors::class, 'scrap_vendor_id', 'id');
    }
    public function mapping(){
        return $this->belongsTo(AssetMapping::class, 'asset_mapping_id', 'id');
    }
    
}
