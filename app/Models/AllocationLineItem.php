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
                            'status'
                        ];
}
