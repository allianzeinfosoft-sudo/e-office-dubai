<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\workReport;
use App\Models\LeaveAllocation;
use App\Models\UserEntryBlockList;
use App\Models\CustomAttendance;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;
use DateTime;

class CustomHelper
{
    public static function calculateTotalWorkingTime($signin_date, $signin_time, $signout_date, $signout_time, $break_time = null) {
        $timezone = 'Asia/Kolkata';
        try {
            // Validate inputs
            if (empty($signin_date) || empty($signin_time) || empty($signout_date) || empty($signout_time)) {
                throw new \Exception("Invalid date or time values provided.");
            }

            // Convert to Carbon objects
            $signIn = Carbon::parse("$signin_date $signin_time", $timezone);
            $signOut = Carbon::parse("$signout_date $signout_time", $timezone);

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
            $breakSeconds = 3600; // Default 1 hour
            if (!empty($break_time)) {
                if (strpos($break_time, ':') !== false) {
                    [$h, $m, $s] = array_pad(explode(':', $break_time), 3, 0);
                    $breakSeconds = ($h * 3600) + ($m * 60) + $s;
                } elseif (is_numeric($break_time)) {
                    $breakSeconds = max(0, $break_time * 60);
                }
            }

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
            ->where('status', 'mark-out')
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
                ->where('status', 'mark-out')
                ->selectRaw('AVG(working_hours) as avg_hours, SUM(working_hours) as total_hours, COUNT(*) as days')
                ->first();

            $leaveCount = Leave::where('user_id', $empId)
                ->whereYear('leave_from', $year)
                ->whereMonth('leave_from', $month)
                ->where('status', 2)
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
            ->where('status', 'mark-out')
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

    public static function currentAttendanceAnalytics($empId, $year = null, $month = null){
        $now = Carbon::now();
        $month = $month ?? $now->month;
        $year = $year ?? $now->year;

        // Fetch attendance records for the employee
        $attendances = Attendance::where('emp_id', $empId)
            ->when($year, fn($q) => $q->whereYear('signin_date', $year))
            ->when($month, fn($q) => $q->whereMonth('signin_date', $month))
            ->get();

        if ($attendances->isEmpty()) {
            return [
                'emp_id' => $empId,
                'year' => $year,
                'month' => $month,
                'message' => 'No attendance data found for this employee.',
            ];
        }

        $username = $attendances->first()->username;

        // Count all attendances
        $totalAttendances = $attendances->count();

        // Count mark-out statuses
        $markOutCount = $attendances->where('status', 'mark-out')->count();

        // 1. completed_days = total attendance - mark-out count
        $completedDays = $totalAttendances - $markOutCount;

        // 2. incomplete or half days (only mark-out + is_incomplete = 1)
        $incompleteOrHalfDays = $attendances->filter(function ($att) {
            return $att->status === 'mark-out' && $att->is_incomplete;
        })->count();

        // 3. off_days = attended on Saturday or Sunday
        $offDays = $attendances->filter(function ($att) {
            $dayOfWeek = Carbon::parse($att->signin_date)->dayOfWeek;
            return in_array($dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
        })->count();

        // 4. custom_days = punch_type = custom
        $customDays = $attendances->where('punchin_type', 'custom')->count();

        // 5. holidays worked
        $holidays = Holiday::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->pluck('date')->toArray();

        $holidayWorked = $attendances->filter(function ($att) use ($holidays) {
            return in_array(Carbon::parse($att->signin_date)->toDateString(), $holidays);
        })->count();

        // 6. leaves with status = 2
        $totalLeaves = Leave::where('user_id', $empId)
            ->where('status', 2)
            ->whereYear('leave_from', $year)
            ->whereMonth('leave_from', $month)
            ->count();

        return [
            'emp_id'                    => $empId,
            'username'                  => $username,
            'year'                      => $year,
            'month'                     => $month,
            'completed_days'            => ($markOutCount - $customDays),
            'incomplete_or_half_days'   => $incompleteOrHalfDays,
            'off_days'                  => $offDays,
            'custom_days'               => $customDays,
            'total_holidays'            => $holidayWorked,
            'total_leaves'              => $totalLeaves,
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
            ->where('status', 2)
            ->count();

        $totalLeavesTaken = Leave::where('user_id', $userId)
            ->where('status', 2)
            ->count();

        $pastYearLeaves = Leave::where('user_id', $userId)
            ->whereYear('leave_from', $pastYear)
            ->where('status', 2)
            ->count();

        $pendingLeaves = Leave::where('user_id', $userId)
            ->where('status', 1)
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

    public static function getMonthlyWorkBreakData($userId = null){
    $monthlyData = [];

    $today = Carbon::now();
    $startOfMonth = $today->copy()->startOfMonth();
    $endOfMonth = $today->copy()->endOfDay(); // current day till now

    // Attendance records for current month up to today
    $query = Attendance::whereBetween('signin_date', [$startOfMonth, $endOfMonth])->where('status', 'mark-out');

    if ($userId) {
        $query->where('emp_id', $userId);
    }

    $attendanceRecords = $query->get();

    // Leaves within the current month till date
    $leaveRecords = Leave::where(function ($q) use ($startOfMonth, $endOfMonth) {
        $q->whereBetween('leave_from', [$startOfMonth, $endOfMonth])
          ->orWhereBetween('leave_to', [$startOfMonth, $endOfMonth]);
    });

    if ($userId) {
        $leaveRecords->where('user_id', $userId);
    }

    $leaveRecords = $leaveRecords->get();

    // Holidays in current month up to today
    $holidays = Holiday::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

    $totalWorkHours = Attendance::where('emp_id', $userId)
        ->whereYear('signin_date', $startOfMonth)
        ->whereBetween('signin_date', [$startOfMonth, $endOfMonth])
        ->where('status', 'mark-out')
        ->selectRaw('AVG(working_hours) as avg_hours, SUM(working_hours) as total_hours, COUNT(*) as days')
        ->first();

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

        if ($attendance->signin_time && $attendance->signout_time) {
            $workingDays++;
        }

        if ($attendance->status == 'Leave') {
            $leaves++;
        }

        $workingHours[] = round(($workedDuration - $breakDuration) / 60, 2);
        $breakHours[] = round($breakDuration / 60, 2);
    }

    $averageWorkingHours = $workingDays > 0 ? round(($totalWorkingHours / $workingDays) / 60, 2) : 0;

    $monthlyData[] = [
        'month' => $startOfMonth->format('F'),
        'year' => $startOfMonth->year,
        'avg_working_hours' => round($totalWorkHours->avg_hours ?? 0, 2),
        'total_working_hours' => round($totalWorkHours->total_hours ?? 0, 2),
        'working_days' => $workingDays,
        'leaves' => $leaves,
        'off_days' => $offDays,
        'working_hours' => $workingHours,
        'break_hours' => $breakHours,
    ];

    return $monthlyData;
}

public static function getMonthlyWorkBreakDataForBarChart($userId = null)
{
    $today = Carbon::now();
    $startOfMonth = $today->copy()->startOfMonth();
    $endOfMonth = $today->copy()->endOfDay();

    // Step 1: Get leave dates for the current user in the current month
    $leaveDates = collect();
    if ($userId) {
        $leaves = Leave::where('user_id', $userId)
            ->where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('leave_from', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('leave_to', [$startOfMonth, $endOfMonth]);
            })
            ->get();

        foreach ($leaves as $leave) {
            $from = Carbon::parse($leave->leave_from);
            $to = Carbon::parse($leave->leave_to);
            while ($from->lte($to)) {
                $leaveDates->push($from->format('Y-m-d'));
                $from->addDay();
            }
        }
    }

    // Step 2: Get attendance records for the current month
    $query = Attendance::whereBetween('signin_date', [$startOfMonth, $endOfMonth]);
    if ($userId) {
        $query->where('emp_id', $userId);
    }

    $attendanceRecords = $query->get()->groupBy(function ($item) {
        return Carbon::parse($item->signin_date)->format('Y-m-d');
    });

    $workingHours = [];
    $breakHours = [];
    $dateMap = collect();

    // Step 3: Loop through each day of the current month up to today
    for ($day = 1; $day <= $today->day; $day++) {
        $date = Carbon::createFromDate($today->year, $today->month, $day);
        $dateKey = $date->format('Y-m-d');

        // Skip Saturdays and Sundays
        if ($date->isSaturday() || $date->isSunday()) {
            continue;
        }

        $dateMap->put($dateKey, [
            'isLeave' => $leaveDates->contains($dateKey),
        ]);
    }

    // Also include leave days even if they fall on weekends
    foreach ($leaveDates as $leaveDate) {
        if (!$dateMap->has($leaveDate)) {
            $dateMap->put($leaveDate, ['isLeave' => true]);
        }
    }

    // Sort by date
    $sortedDates = $dateMap->keys()->sort()->values();

    foreach ($sortedDates as $dateKey) {
        $workingHours[$dateKey] = 0;
        $breakHours[$dateKey] = 0;



        if (isset($attendanceRecords[$dateKey])) {
            foreach ($attendanceRecords[$dateKey] as $attendance) {

                $breakTime = $attendance->break_time ?? '00:00:00';
                $breakParts = explode(':', $breakTime);
                $hours = isset($breakParts[0]) ? (int)$breakParts[0] : 0;
                $minutes = isset($breakParts[1]) ? (int)$breakParts[1] : 0;
                $seconds = isset($breakParts[2]) ? (int)$breakParts[2] : 0;
                $breakDurationMinutes = ($hours * 60) + $minutes + ($seconds / 60);

                $signinTime = Carbon::parse($attendance->signin_time);
                $signoutTime = Carbon::parse($attendance->signout_time);
                $workedDurationMinutes = $signinTime->diffInMinutes($signoutTime);

                // Convert both to hours and round
                $workingHours[$dateKey] += round(($workedDurationMinutes - $breakDurationMinutes) / 60, 2);
                $breakHours[$dateKey] += round($breakDurationMinutes / 60, 2);
            }
        }
    }

    return [
        'dates' => $sortedDates->toArray(),                              // e.g. ['2025-05-01', ..., '2025-05-13']
        'working_hours' => array_values($workingHours),       // in hours
        'break_hours' => array_values($breakHours),           // in hours
        'leave_dates' => $leaveDates->unique()->values()->toArray(),     // useful for front-end to highlight leave
    ];
}

public static function getWorkRatingAnalysisMonthly($empId)
{
    $now = Carbon::now();
    $month = $now->month;
    $year = $now->year;

    $records = WorkReport::where('emp_id', $empId)
        ->whereYear('report_date', $year)
        ->whereMonth('report_date', $month)
        ->get();

    $analysis = [
        'Outstanding'   => 0,
        'Very Good'     => 0,
        'Good'          => 0,
        'Above Average' => 0,
        'Average'       => 0,
        'Poor'          => 0,
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

    /**
         * Send notification email with HTML body
         *
         * @param string|array $to
         * @param string $subject
         * @param string $htmlBody
         * @param array $cc
         * @param array $bcc
         * @return bool
     */
    public static function sendNotificationMail($to, string $subject, string $htmlBody, array $cc = [], array $bcc = []): bool{
        try {
            Mail::send([], [], function ($message) use ($to, $subject, $htmlBody, $cc, $bcc) {

                $message->to($to)->subject($subject)->html($htmlBody); // ✅ Replaced setBody with html()

                if (!empty($cc)) {
                    $message->cc($cc);
                }

                if (!empty($bcc)) {
                    $message->bcc($bcc);
                }
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /* Create blocked user */
    public static function addToBlockList($data)
    {
        return UserEntryBlockList::updateOrCreate(
            // Conditions to check for existing user
            [
                'user_id'    => $data['user_id'],
                'block_date' => $data['block_date'] ?? Carbon::now()->toDateString(),
            ],
            // Values to insert or update
            [
                'username'   => $data['username'],
                'full_name'  => $data['full_name'] ?? null,
                'status'     => $data['status'] ?? 1,
            ]
        );
    }

    /* Total Experience */
    public static function getExperience($user)
    {
        $join = Carbon::parse($user->employee->join_date);
        $now = Carbon::now();
        if($user->employee->resigned_date != null)
        {
            $resigned_date = $user->employee->resigned_date;
            $diff = $join->diff($resigned_date);
        }else{
             $diff = $join->diff($now);
        }


        return "{$diff->y} years, {$diff->m} months, {$diff->d} days";
    }

    /* Total count of blocked user */
    public static function getBlockedUsersCount()
    {
        return UserEntryBlockList::where('status', 1)->count();
    }
    public static function pendingIncompleteWorkCount()
    {
        return Attendance::where(['is_incomplete' => 1, 'incomplete_approved' => 0])->count();
    }

    public static function customAttendanceCount(){
        return CustomAttendance::where(['status' => 0])->count();
    }

    public static function customPendingLeaveCount(){
        return Leave::where(['status' => 1])->count();
    }
    public static function formatTimeToSeconds(string $time): string
    {
        return Carbon::createFromFormat(strlen($time) === 5 ? 'H:i' : 'H:i:s', $time)->format('H:i:s');
    }

    public static function getCurrentWorkingTime($userId)
    {
        $timezone = 'Asia/Kolkata';

        $attendance = Attendance::where('emp_id', $userId)
            ->whereDate('signin_date', Carbon::now($timezone)->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        if (!$attendance) {
            return '00:00:00';
        }

        // Parse signin and signout with timezone
        $signin = Carbon::parse($attendance->signin_date . ' ' . $attendance->signin_time, $timezone);

        $signout = ($attendance->status === 'mark-out' && $attendance->signout_time)
            ? Carbon::parse($attendance->signout_date . ' ' . $attendance->signout_time, $timezone)
            : Carbon::now($timezone);

        // Convert break_time from H:i:s to seconds
        $breakTime = $attendance->break_time ?? '00:00:00';

        $breakParts = explode(':', $breakTime);
        $hours   = isset($breakParts[0]) ? (int) $breakParts[0] : 0;
        $minutes = isset($breakParts[1]) ? (int) $breakParts[1] : 0;
        $seconds = isset($breakParts[2]) ? (int) $breakParts[2] : 0;

        $breakSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

        $totalSeconds = $signout->diffInSeconds($signin) - $breakSeconds;
        if ($totalSeconds < 0) {
            $totalSeconds = 0;
        }

        return gmdate('H:i:s', $totalSeconds);
    }
    public static function timeToSeconds($time){
        list($h, $m, $s) = array_pad(explode(':', $time), 3, 0);
        return ($h * 3600) + ($m * 60) + $s;
    }

    public static function storeMail($data, $files = [])
    {
        try {
            $mail = new MailBox();
            $mail->from_user_id = Auth::id();
            $mail->to_user_ids = is_string($data['to_user_ids']) ? json_decode($data['to_user_ids'], true) : $data['to_user_ids'];
            $mail->cc_user_ids = isset($data['cc_user_ids']) ? (is_string($data['cc_user_ids']) ? json_decode($data['cc_user_ids'], true) : $data['cc_user_ids']) : [];
            $mail->bcc_user_ids = isset($data['bcc_user_ids']) ? (is_string($data['bcc_user_ids']) ? json_decode($data['bcc_user_ids'], true) : $data['bcc_user_ids']) : [];
            $mail->subject = $data['subject'];
            $mail->message = $data['message'];
            $mail->status = $data['status'] ?? 0;

            if ($mail->status >= 0 && $mail->status <= 7) {
                $folders = MailBox::folders();
                $mail->folder = $folders[$mail->status] ?? 'inbox';
            }

            // Handle attachments
            if (!empty($files)) {
                $attachments = [];
                foreach ($files as $file) {
                    $fileName = $file->hashName();
                    $file->storeAs('mail_attachments', $fileName, 'public');
                    $attachments[] = $fileName;
                }
                $mail->attachments = json_encode($attachments);
            }

            $mail->save();

            // Send notification email
            $toUserIds = $mail->to_user_ids;
            $emails = User::whereIn('id', $toUserIds)->pluck('email')->toArray();

            if (!empty($emails)) {
                $htmlBody = View::make('emails.notification', [
                    'name' => 'Team',
                    'message' => 'You are receiving a new email',
                ])->render();

                self::sendNotificationMail($emails, $mail->subject, $htmlBody);
            }

            return ['send' => true, 'mail' => $mail];

        } catch (\Exception $e) {
            \Log::error('Mail store error: ' . $e->getMessage());
            return ['send' => false, 'error' => $e->getMessage()];
        }
    }
}


