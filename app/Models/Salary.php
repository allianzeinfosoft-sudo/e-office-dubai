<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable=['user_id','salary_slip','salary_slip_year','salary_slip_month'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }
}
