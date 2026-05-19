<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBankConfiguration extends Model
{
    protected $fillable = ['bank_name', 'branch', 'ifsc', 'account_no'];
}
