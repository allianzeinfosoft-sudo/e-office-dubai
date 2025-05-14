<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';
    protected $fillable = ['leave_from','leave_to','user_id','leave_type','reason'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class,'user_id','user_id');
    }

    public static function getTotalLeavesTakenInCurrentMonth()
    {
        $userId = auth()->id();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Fetch approved leaves overlapping with current month
        $leaves = Leave::where('user_id', $userId)
            ->where('status', 2) // status = 2 => approved
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('leave_from', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('leave_to', [$startOfMonth, $endOfMonth])
                    ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('leave_from', '<=', $startOfMonth)
                            ->where('leave_to', '>=', $endOfMonth);
                    });
            })
            ->get();

        $totalLeaveDays = 0;

        foreach ($leaves as $leave) {
            // Adjust start and end to be within the current month range
            $leaveStart = Carbon::parse($leave->start_date)->greaterThan($startOfMonth) ? Carbon::parse($leave->start_date) : $startOfMonth;
            $leaveEnd = Carbon::parse($leave->end_date)->lessThan($endOfMonth) ? Carbon::parse($leave->end_date) : $endOfMonth;

            // Calculate number of days including both ends
            $totalLeaveDays += $leaveStart->diffInDaysFiltered(function ($date) {
                return true; // or return !$date->isWeekend(); if you want to exclude weekends
            }, $leaveEnd->copy()->addDay());
        }

        return $totalLeaveDays;
    }

    public static function calculateDaysBetween($startDate, $endDate, $excludeWeekends = false, $includeEndDate = true)
    {
        if (!$startDate || !$endDate) {
            return 0;
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Include the end date in the range if requested
        if ($includeEndDate) {
            $end = $end->copy()->addDay();
        }

        // if ($excludeWeekends) {
        //     return $start->diffInDaysFiltered(function (Carbon $date) {
        //         return !$date->isWeekend();
        //     }, $end);
        // }

        return $start->diffInDays($end);
    }

    public function leaveApprover()
    {
        return $this->belongsTo(LeaveApprover::class,'id','leave_id');
    }


}
