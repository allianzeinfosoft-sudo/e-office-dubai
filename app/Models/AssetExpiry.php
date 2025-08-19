<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetExpiry extends Model
{
    use HasFactory;

     protected $fillable = [
        'service_name',
        'asset_categories_id',
        'asset_vendors_id',
        'licence_id',
        'licence_count',
        'start_date',
        'last_updated_date',
        'expiry_date',
        'cost',
        'remarks'
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class,'asset_categories_id');
    }

    public function vendor()
    {
        return $this->belongsTo(AssetVendors::class,'asset_vendors_id');
    }

}
