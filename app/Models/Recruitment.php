<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by',
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
        'status',
        'status_reason',
        'draft_status'
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'projectId');
    }

    public function interViewer() {
        return $this->belongsTo(Employee::class, 'interviewer');
    }

    public function postedBy() {
        return $this->belongsTo(Employee::class, 'empId');
    }

    public function designation() {
        return $this->belongsTo(Designation::class, 'positionId');
    }
    public function department() {
        return $this->belongsTo(Department::class, 'departmentId');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branchId');
    }
    public function seekApprover() {
        return $this->belongsTo(Employee::class, 'seekApproval');
    }

    public function workShift() {
        return $this->belongsTo(Workshift::class, 'shiftId');
    }

    public function minimumQualifications() {
        return $this->belongsTo(MinimumQualification::class, 'minimumQualification');
    }

    public function graduationMedium() {
        return $this->belongsTo(Graduation::class, 'graduation');
    }

    public function getSkillNamesAttribute(){
        if (!$this->skillRequired) {
            return [];
        }
        $ids = explode(',', $this->skillRequired);
        return Skills::whereIn('id', $ids)->pluck('name')->toArray();
    }
    public function getKeywordsAttribute(){
        if (!$this->keyword) {
            return [];
        }
        $ids = explode(',', $this->keyword);
        return KeyworsRrf::whereIn('id', $ids)->pluck('name')->toArray();
    }

}
