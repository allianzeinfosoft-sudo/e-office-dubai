<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollComponent extends Model
{
    use HasFactory;

    protected $fillable = ['payroll_entry_id', 'component_name', 'type', 'is_ctc_variable', 'is_attendance_based', 'standard_amount', 'amount', 'part_number'];

    public function entry()
    {
        return $this->belongsTo(PayrollEntry::class, 'payroll_entry_id');
    }
}
