<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetItemLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_register_id',
        'asset_item_id',
        'item_model',
        'asset_description',
        'asset_classification_id',
        'asset_category_id',
        'asset_type_id',
        'asset_quantity',
        'asset_price',
        'asset_total',
        'serial_number',
        'warranty',
    ];
    public function asset_register()
    {
        return $this->belongsTo(AssetRegister::class, 'asset_register_id', 'id');
    }
    public function asset_item()
    {
        return $this->belongsTo(AssetItemMaster::class, 'asset_item_id', 'id');
    }
    public function asset_classification()
    {
        return $this->belongsTo(AssetClassification::class, 'asset_classification_id', 'id');
    }
    public function asset_category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id', 'id');
    }
    public function asset_type()
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'id');
    }
   public function mapping()
    {
        return $this->hasMany(AssetMapping::class, 'register_lineitem_id', 'id');
    }
}
