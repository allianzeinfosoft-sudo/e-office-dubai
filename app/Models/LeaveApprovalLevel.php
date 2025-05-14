<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovalLevel extends Model
{
    use HasFactory;

    protected $fillable = ['department','approver','approval_level','approve_count'];

   public function employee()
    {
        return $this->belongsTo(Employee::class, 'approver', 'user_id');
    }

    public function dept()
    {
        return $this->belongsTo(Department::class, 'department','id');
    }

}
