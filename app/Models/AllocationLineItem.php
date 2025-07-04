<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationLineItem extends Model
{
    use HasFactory;
    protected $fillable = ['allocation_id','item','model','item_line_id','serial_number','project','asset_id','qty','specification','status'];
}
