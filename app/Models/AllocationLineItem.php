<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationLineItem extends Model
{
    use HasFactory;
    protected $fillable = [
                            'allocation_id',
                            'item',
                            'model',
                            'serial_number',
                            'asset_id',
                            'project',
                            'qty',
                            'specification',
                            'status',
                            'return_date_time',
                            'comment',
                            'asset_itemline_id'
                        ];


    public function itemAllocation()
    {
        return $this->belongsTo(AssetAllocation::class,'allocation_id','id');
    }

    public function masterItem()
    {
        return $this->belongsTo(AssetItemMaster::class,'item','id');
    }

    public function project_info()
    {
        return $this->belongsTo(Project::class,'project','id');
    }
    public function classification()
    {
        return $this->belongsTo(AssetClassification::class,'asset_classification_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(AssetCategory::class,'asset_category_id','id');
    }

    public function assetType()
    {
        return $this->belongsTo(AssetType::class,'asset_type','id');
    }

}


