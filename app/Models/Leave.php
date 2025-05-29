<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';
    protected $fillable = ['leave_from','leave_to','user_id','leave_type','leave_day_count','reason','initial_approver_id','initial_approve_status'];

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
            ->whereIn('status', [1,2]) // status = 2 => approved, 1=>pending
            ->where('leave_type','!=','off_day')
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

             // Half-day leave counts as 0.5
            if ($leave->leave_type === 'half_day') {
                $totalLeaveDays += 0.5;
                continue;
            }
                // Adjust start and end to be within the current month range
                $leaveStart = Carbon::parse($leave->leave_from)->greaterThan($startOfMonth)
                ? Carbon::parse($leave->leave_from)
                : $startOfMonth;

                $leaveEnd = Carbon::parse($leave->leave_to)->lessThan($endOfMonth)
                ? Carbon::parse($leave->leave_to)
                : $endOfMonth;

                // Calculate number of days (inclusive)
                $totalLeaveDays += $leaveStart->diffInDays($leaveEnd) + 1;

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

    public function initialApprover()
    {
        return $this->belongsTo(Employee::class,'initial_approver_id','user_id');
    }


}
