<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class AssetType extends Model
{
    use HasFactory;

    protected $fillable = ['asset_category_id','name'];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

}
