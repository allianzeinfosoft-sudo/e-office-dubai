<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetAllocation extends Model
{
    use HasFactory;
    protected $fillable = ['user_type','user','department','remarks','status'];

    public function items()
    {
        return $this->hasMany(AllocationLineItem::class, 'allocation_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user','user_id');
    }

     public function location()
    {
        return $this->belongsTo(AssetLocation::class,'user','id');
    }

    public function department_name()
    {
        return $this->belongsTo(Department::class,'department','id');
    }




}
