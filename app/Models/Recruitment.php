<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;
    protected $fillable = [
        'empId',
        'rrfDate',
        'branchId',
        'departmentId',
        'positionId',
        'projectId',
        'shiftId',
        'salaryRange',
        'jobType',
        'interviewer',
        'sittingArragement',
        'minimumQualification',
        'skillRequired',
        'experience',
        'schoolingMedium',
        'graduation',
        'ageGroup',
        'location',
        'interviewPlace',
        'priority',
        'referral',
        'referralIncentive',
        'requireToAndFroCharge',
        'keyword',
        'seekApproval',
        'jobTitle',
        'jobDescription',
        'remarks',
        'noOfPersons',
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'projectId');
    }
    
    public function interViewer() {
        return $this->belongsTo(Employee::class, 'interviewer');
    }

    public function designation() {
        return $this->belongsTo(Designation::class, 'positionId');
    }
}
