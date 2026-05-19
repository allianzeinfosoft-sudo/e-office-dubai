<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'payroll_entry_id',
        'amount',
        'repayment_date',
        'repayment_method'
    ];

    protected $casts = [
        'repayment_date' => 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function payrollEntry()
    {
        return $this->belongsTo(PayrollEntry::class);
    }
}
