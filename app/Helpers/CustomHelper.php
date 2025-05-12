<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\workReport;
use App\Models\LeaveAllocation;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class CustomHelper
{
    public static function calculateTotalWorkingTime($signin_date, $signin_time, $signout_date, $signout_time, $break_time = null) {
        try {
            // Validate inputs
            if (empty($signin_date) || empty($signin_time) || empty($signout_date) || empty($signout_time)) {
                throw new \Exception("Invalid date or time values provided.");
            }

            // Convert to Carbon objects
            $signIn = Carbon::parse("$signin_date $signin_time");
            $signOut = Carbon::parse("$signout_date $signout_time");

            // Ensure sign-out is after sign-in
            if ($signOut->lessThanOrEqualTo($signIn)) {
                return [
                    'total_working_time' => '00:00:00',
                    'break_time' => '00:00:00',
                    'error' => 'Sign-out time must be after sign-in time',
                ];
            }

            // Calculate total work duration in seconds
            $totalSeconds = $signOut->diffInSeconds($signIn);

            // Ensure break time is numeric and convert to seconds
            $breakSeconds = (is_numeric($break_time) && $break_time >= 0) ? ($break_time * 60) : 3600; // Default: 1 hour (3600 seconds)

            // Calculate actual working seconds
            $actualWorkSeconds = max($totalSeconds - $breakSeconds, 0);

            // Convert to HH:MM:SS format
            $formattedWorkTime  = gmdate("H:i:s", $actualWorkSeconds);
            $formattedBreakTime = gmdate("H:i:s", $breakSeconds);

            return [
                'total_working_time' => $formattedWorkTime,
                'break_time' => $formattedBreakTime,
            ];
        } catch (\Exception $e) {
            return [
                'total_working_time' => '00:00:00',
                'break_time' => '00:00:00',
                'error' => $e->getMessage(),
            ];
        }
    }


    public static function calculateGrade($productivity_hour) {
        if ($productivity_hour >= 10) return 'A';
        if ($productivity_hour >= 7) return 'B';
        if ($productivity_hour >= 5) return 'C';
        return 'D';
    }

    public static function calculatePerformance($productivity_hour) {
        if ($productivity_hour >= 10) return 'Excellent';
        if ($productivity_hour >= 7) return 'Good';
        if ($productivity_hour >= 5) return 'Average';
        return 'Needs Improvement';
    }

    public function getMonthNames($month_id)
    {
        return DateTime::createFromFormat('!m', $month_id)->format('F');

    }

    
    /* get monthly avarage working hours */
    public static function getMonthlyAverageHours($empId, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
    
        $monthlyData = Attendance::select(
                DB::raw('MONTH(signin_date) as month'),
                DB::raw('AVG(working_hours) as avg_hours')
            )
            ->whereYear('signin_date', $year)
            ->where('emp_id', $empId)
            ->groupBy(DB::raw('MONTH(signin_date)'))
            ->orderBy(DB::raw('MONTH(signin_date)'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [intval($item->month) => round($item->avg_hours, 2)];
            });
    
        // Fill in months with 0 if no data
        $currentMonth = Carbon::now()->month;

        $allMonths = collect(range(1, $currentMonth))->mapWithKeys(function ($month) use ($monthlyData) {
            return [$month => $monthlyData[$month] ?? 0];
        });
    
        return [
            'months' => $allMonths->keys()->map(fn($m) => date("F", mktime(0, 0, 0, $m, 1)))->toArray(),
            'average_hours' => $allMonths->values()->toArray()
        ];
    }

    public static function getMonthlyWorkReport($empId, $year = null)
    {
        $year = $year ?? now()->year;
        $currentMonth = now()->month;

        $report = [];

        for ($month = 1; $month <= $currentMonth; $month++) {
            $attendance = Attendance::where('emp_id', $empId)
                ->whereYear('signin_date', $year)
                ->whereMonth('signin_date', $month)
                ->selectRaw('AVG(working_hours) as avg_hours, SUM(working_hours) as total_hours, COUNT(*) as days')
                ->first();

            $leaveCount = Leave::where('user_id', $empId)
                ->whereYear('leave_from', $year)
                ->whereMonth('leave_from', $month)
                ->get()
                ->sum(function ($leave) {
                    $from = Carbon::parse($leave->leave_from);
                    $to = Carbon::parse($leave->leave_to);
                    return $from->diffInDaysFiltered(fn(Carbon $date) => $date->isWeekday(), $to) + 1;
                });

            $report[] = [
                'month' => Carbon::create()->month($month)->format('F'),
                'avg_hours' => round($attendance->avg_hours ?? 0, 2),
                'total_hours' => round($attendance->total_hours ?? 0, 2),
                'working_days' => $attendance->days ?? 0,
                'leaves' => $leaveCount,
                'year' => $year
            ];
        }

        return $report;
    }

    public static function getMonthlyTotalHours($empId, $year = null){

        $year = $year ?? Carbon::now()->year;

        $monthlyData = Attendance::select(
                DB::raw('MONTH(signin_date) as month'),
                DB::raw('SUM(working_hours) as total_hours')
            )
            ->whereYear('signin_date', $year)
            ->where('emp_id', $empId)
            ->groupBy(DB::raw('MONTH(signin_date)'))
            ->orderBy(DB::raw('MONTH(signin_date)'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [intval($item->month) => round($item->total_hours, 2)];
            });

        $currentMonth = Carbon::now()->month;

        $allMonths = collect(range(1, $currentMonth))->mapWithKeys(function ($month) use ($monthlyData) {
            return [$month => $monthlyData[$month] ?? 0];
        });

        return [
            'months' => $allMonths->keys()->map(fn($m) => date("F", mktime(0, 0, 0, $m, 1)))->toArray(),
            'total_hours' => $allMonths->values()->toArray()
        ];
    }

    public static function getWorkRatingAnalysis($empId, $year = null){
        $year = $year ?? Carbon::now()->year;

        $records = WorkReport::where('emp_id', $empId)
            ->whereYear('report_date', $year)
            ->get();

        $analysis = [
            'Outstanding' => 0,
            'Very Good' => 0,
            'Good' => 0,
            'Above Average' => 0,
            'Average' => 0,
            'Poor' => 0,
        ];

        foreach ($records as $record) {
            $totalTime = floatval($record->total_time);
            $productiveTime = floatval($record->productivity_hour);

            if ($totalTime <= 0) {
                continue; // skip invalid entries
            }

            $percentage = ($productiveTime / $totalTime) * 100;

            if ($percentage >= 90) {
                $analysis['Outstanding']++;
            } elseif ($percentage >= 75) {
                $analysis['Very Good']++;
            } elseif ($percentage >= 60) {
                $analysis['Good']++;
            } elseif ($percentage >= 50) {
                $analysis['Above Average']++;
            } elseif ($percentage >= 30) {
                $analysis['Average']++;
            } else {
                $analysis['Poor']++;
            }
        }

        return $analysis;
    }

    public static function currentAttendanceAnalytics($empId, $month = null) {
        $month = $month ?? Carbon::now()->month; // Default to current month if not provided
        $year = Carbon::now()->year; // Always use the current year
    
        // Fetch the attendance data for the specified month and current year
        $attendances = Attendance::where('emp_id', $empId)
            ->whereYear('signin_date', $year)
            ->whereMonth('signin_date', $month)
            ->get();
    
        if ($attendances->isEmpty()) {
            return [
                'emp_id' => $empId,
                'year' => $year,
                'month' => $month,
                'message' => 'No attendance data found for this employee and month.',
            ];
        }
    
        $username = $attendances->first()->username;
    
        $completedDays = $attendances->filter(fn($e) =>
            $e->signin_time && $e->signout_time && !$e->is_incomplete
        )->count();
    
        $incompleteOrHalfDays = $attendances->filter(fn($e) =>
            $e->is_incomplete || !$e->signout_time
        )->count();
    
        $offDays = $attendances->where('status', 'Off')->count();
    
        $customDays = $attendances->whereNotNull('custom_status')->count();
    
        $totalHolidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->count();
    
        $totalLeaves = Leave::where('user_id', $empId)
            ->where('status', 'Approved')
            ->whereYear('leave_from', $year)
            ->whereMonth('leave_from', $month)
            ->count();
    
        return [
            'emp_id'            => $empId,
            'username'          => $username,
            'year'              => $year,
            'month'             => $month,
            'completed_days'    => $completedDays,
            'incomplete_or_half_days'   => $incompleteOrHalfDays,
            'off_days'          => $offDays,
            'custom_days'       => $customDays,
            'total_holidays'    => $totalHolidays,
            'total_leaves'      => $totalLeaves,
        ];
    }

    public static function getEmployeeLeaveStats($userId)
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;
        $pastYear = $currentYear - 1;

        // Leave Requests
        $thisMonthLeaves = Leave::where('user_id', $userId)
            ->whereMonth('leave_from', $currentMonth)
            ->whereYear('leave_from', $currentYear)
            ->where('status', 'Approved')
            ->count();

        $totalLeavesTaken = Leave::where('user_id', $userId)
            ->where('status', 'Approved')
            ->count();

        $pastYearLeaves = Leave::where('user_id', $userId)
            ->whereYear('leave_from', $pastYear)
            ->where('status', 'Approved')
            ->count();

        $pendingLeaves = Leave::where('user_id', $userId)
            ->where('status', 'Pending')
            ->count();

        // Paid Leaves (assuming 'Paid' is a leave_type)
        $paidLeaves = Leave::where('user_id', $userId)
            ->where('leave_type', 'Paid')
            ->where('status', 'Approved')
            ->count();

        // Category wise current year
        $categoryWise = Leave::select('leave_type')
            ->where('user_id', $userId)
            ->whereYear('leave_from', $currentYear)
            ->where('status', 'Approved')
            ->get()
            ->groupBy('leave_type')
            ->map(fn($group) => $group->count());

        // Allocation
        $leaveAllocation = LeaveAllocation::where('user_id', $userId)
            ->where('year', $currentYear)
            ->first();

        return [
            'this_month_leaves' => $thisMonthLeaves,
            'total_leaves_taken' => $totalLeavesTaken,
            'total_leaves_allotted' => $leaveAllocation->total_leaves ?? 0,
            'total_paid_leaves' => $paidLeaves,
            'past_year_leaves' => $pastYearLeaves,
            'total_pending_leaves' => $pendingLeaves,
            'used_leaves' => $leaveAllocation->used_leaves ?? 0,
            'remaining_leaves' => $leaveAllocation->remaining_leaves ?? 0,
            'category_wise_leaves' => $categoryWise,
        ];
    }

    public static function getMonthlyWorkBreakData($userId = null)
{
    $monthlyData = [];

    for ($month = 1; $month <= 12; $month++) {
        $startOfMonth = Carbon::create(null, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create(null, $month, 1)->endOfMonth();

        // Query for attendance within the month range
        $query = Attendance::whereBetween('signin_date', [$startOfMonth, $endOfMonth]);

        if ($userId) {
            $query->where('emp_id', $userId);
        }

        $attendanceRecords = $query->get();

        // Query for leaves within the month range
        $leaveRecords = Leave::whereBetween('leave_from', [$startOfMonth, $endOfMonth])
            ->orWhereBetween('leave_to', [$startOfMonth, $endOfMonth])
            ->where('user_id', $userId)
            ->get();

        // Query for holidays in the month
        $holidays = Holiday::whereMonth('date', $month)->get();

        $totalWorkingHours = 0;
        $totalBreakTime = 0;
        $workingDays = 0;
        $leaves = 0;
        $offDays = count($holidays);
        $workingHours = [];
        $breakHours = [];

        foreach ($attendanceRecords as $attendance) {
            $signinTime = Carbon::parse($attendance->signin_time);
            $signoutTime = Carbon::parse($attendance->signout_time);
            $workedDuration = $signinTime->diffInMinutes($signoutTime);
            $breakDuration = is_numeric($attendance->break_time) ? $attendance->break_time : 0;

            $totalWorkingHours += $workedDuration - $breakDuration;
            $totalBreakTime += $breakDuration;

            // Increment working days if both signin and signout times are present
            if ($attendance->signin_time && $attendance->signout_time) {
                $workingDays++;
            }

            // Count leaves taken
            if ($attendance->status == 'Leave') {
                $leaves++;
            }

            // Store working hours and break hours for each day
            $workingHours[] = round(($workedDuration - $breakDuration) / 60, 2);
            $breakHours[] = round($breakDuration / 60, 2);
        }

        // Calculate average working hours
        $averageWorkingHours = $workingDays > 0 ? round(($totalWorkingHours / $workingDays) / 60, 2) : 0;

        // Store monthly data for each month
        $monthlyData[] = [
            'month' => Carbon::createFromFormat('m', $month)->format('F'),
            'year' => $startOfMonth->year,
            'avg_working_hours' => $averageWorkingHours,
            'total_working_hours' => round($totalWorkingHours / 60, 2),
            'working_days' => $workingDays,
            'leaves' => $leaves,
            'off_days' => $offDays,
            'working_hours' => $workingHours,
            'break_hours' => $breakHours,
        ];
    }

    return $monthlyData;
}
    
   
}