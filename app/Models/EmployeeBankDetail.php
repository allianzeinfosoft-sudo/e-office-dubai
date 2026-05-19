<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBankDetail extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'bank_name', 'account_number', 'ifsc', 'branch_name', 'beneficiary_name'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
