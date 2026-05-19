<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'loan_type',
        'amount',
        'emi_amount',
        'total_installments',
        'paid_installments',
        'disbursement_date',
        'status',
        'reason'
    ];

    protected $casts = [
        'disbursement_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function repayments()
    {
        return $this->hasMany(LoanRepayment::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->repayments()->sum('amount');
    }
}
