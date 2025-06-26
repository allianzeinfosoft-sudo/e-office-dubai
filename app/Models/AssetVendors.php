<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetVendors extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_code',
        'vendor_name',
        'vendor_category',
        'vendor_address',
        'email',
        'website',
        'contact_person',
        'contact_number',
        'mobile_number',
        'status',
    ];
    public function category(){
        return $this->belongsTo(VendorCategory::class, 'vendor_category', 'id');
    }   
}
