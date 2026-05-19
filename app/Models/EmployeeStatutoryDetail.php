<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatutoryDetail extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'pf_no', 'esi_no', 'pan_no', 'aadhaar_no', 'pf_applicable', 'esi_applicable'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
