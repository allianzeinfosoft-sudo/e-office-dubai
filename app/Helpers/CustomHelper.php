<?php

namespace App\Helpers;

use Carbon\Carbon;

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
                    'total_working_time' => '00:00',
                    'break_time' => '00:00',
                    'error' => 'Sign-out time must be after sign-in time',
                ];
            }

            // Calculate total work duration in minutes
            $totalMinutes = $signOut->diffInMinutes($signIn);

            // Ensure break time is numeric and convert to minutes
            $breakMinutes = (is_numeric($break_time) && $break_time >= 0) ? $break_time : 60; // Default break: 1 hour

            // Calculate actual working minutes
            $actualWorkMinutes = max($totalMinutes - $breakMinutes, 0);

            // Convert minutes to HH:MM format
            $hours = floor($actualWorkMinutes / 60);
            $minutes = $actualWorkMinutes % 60;

            // Round up if minutes are 59 or more
            if ($minutes >= 59) {
                $hours += 1;
                $minutes = 0;
            }

            // Format HH:MM
            $formattedWorkTime = sprintf('%02d:%02d', $hours, $minutes);
            $formattedBreakTime = sprintf('%02d:%02d', floor($breakMinutes / 60), $breakMinutes % 60);

            return [
                'total_working_time' => $formattedWorkTime,
                'break_time' => $formattedBreakTime,
            ];
        } catch (\Exception $e) {
            return [
                'total_working_time' => '00:00',
                'break_time' => '00:00',
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
    
}
