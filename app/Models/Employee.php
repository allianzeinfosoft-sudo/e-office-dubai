<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable= [
        'user_id','employeeID','full_name','phonenumber','reporting_to','personal_email','gender',
        'blood_group','qualification','esi_no','aadhaar','pf_no','electoral_id','pan','dob','group','address','profile_image',
        'mobile_number','mobile_relationship','landline','landline_relationship','department_id','designation_id','join_date','shift_id',
        'role','status','login_limited_time','appointment_status','team_lead','bank_name','bank_branch','beneficiary_name',
        'account_number'

    ];

    public function reportingToEmployee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'reporting_to');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }
    public function workshift()
    {
        return $this->belongsTo(Workshift::class, 'shift_id','id');
    }
    public function login_limited_time_info()
    {
        return $this->belongsTo(LoginLimitedTime::class,'login_limited_time','id');
    }
    public function userStatus()
    {
        return $this->belongsTo(UserStatus::class, 'status','id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
