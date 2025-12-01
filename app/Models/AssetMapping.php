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
        'model',
        'serial_number',
        'allocation_id',
        'scrap_id',
        'repair_id',
        'allocation_status',
        'status'];

    protected $casts = [
            'allocation_id' => 'array',
        ];

    public function masteritem()
    {
        return $this->belongsTo(AssetItemMaster::class,'master_item_id','id');
    }

    // public function register_lineitems()
    // {
    //     return $this->belongsToMany(AssetItemLine::class,'register_lineitem_id','id');
    // }
    public function register_lineitem()
    {
        return $this->belongsTo(AssetItemLine::class, 'register_lineitem_id', 'id');
    }

    public function allocation_lineitems()
    {
        return $this->hasMany(AllocationLineItem::class, 'asset_mapping_id')
                    ->where('status', 1);
    }

    public function allocations()
    {
        return AllocationLineItem::where(function ($query) {
            foreach ($this->allocation_id ?? [] as $id) {
                $query->orWhere('id', $id);
            }
        });
    }

    public function repair_lineitems()
    {
        return $this->hasMany(RepairItemLine::class, 'asset_map_id')
                     ->where('status', [1]);
    }

}
