<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\workReport;

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
   
}