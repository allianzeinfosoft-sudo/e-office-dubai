<?php

namespace App\Traits;
use Carbon\Carbon;

trait DateFormatter
{
    public function formatDateTime12Hour(?string $timestamp): ?string
    {
        if (!$timestamp) {
            return null;
        }

        return Carbon::parse($timestamp)->format('F d, Y, h:i:s A'); // e.g., "March 17, 2025, 05:14:06 PM"
    }

    public function formatDateDayMonthYear(?string $date): ?string
    {
        return $date ? Carbon::parse($date)->format('d F Y') : null;
    }

    public function getDaysBetween(string $startDate, string $endDate): ?int
    {
        try {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            // Difference in days (inclusive)
            return $start->diffInDays($end) + 1;
        } catch (\Exception $e) {
            return null; // Return null if parsing fails
        }
    }
}


