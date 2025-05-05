<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mom extends Model
{
    use HasFactory;
    protected $fillable = [
        'mom_title',
        'mom_date',
        'created_by',
        'assigned_to',
        'mom_details',
        'attachments',
        'status',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class, 'created_by', 'user_id');
    }

    public function getAssignedToEmployeeAttribute(){
        if (!$this->assigned_to) {
            return [];
        }
        $ids = explode(',', $this->assigned_to);
        return Employee::whereIn('user_id', $ids)->pluck('full_name')->toArray();
    }
}
