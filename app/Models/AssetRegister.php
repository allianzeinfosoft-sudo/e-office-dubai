<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRegister extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_date',
        'asset_number',
        'company_name',
        'purchase_date',
        'vendor_id',
        'invoice_number',
        'total_amount',
        'upload_invoice',
        'remarks',
    ];

    public function vendor()
    {
        return $this->belongsTo(AssetVendors::class);
    }
    public function items()
    {
        return $this->hasMany(AssetItemLine::class, 'asset_register_id', 'id');
    }
}
