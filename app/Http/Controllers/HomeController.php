<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\helpers\CustomHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Appreciation;
use App\Models\LeaveAllocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){

        $selected_user =  Auth::user()->id;
        $selected_year =  date('Y');

        $data['employee'] = Employee::with('department', 'designation', 'workshift', 'reportingToEmployee')->where('user_id', $selected_user)->first();
        $data['attendance_analytics'] = CustomHelper::currentAttendanceAnalytics($selected_user, $selected_year);

        $data['holidays'] = Holiday::whereNotNull('holiday_group')->where('holiday_group', $data['employee']?->holidayGroup)
                            ->whereYear('date', now()->year)   // 🔥 only current year
                            ->orderBy('date', 'ASC')            // 🔥 Jan → Dec
                            ->get();


        $user = Auth::user();
        $fromDate = Carbon::now()->startOfMonth();
        $toDate = Carbon::now()->endOfMonth();
        $today = Carbon::today();

        // Total Working Hours
        $attendances = Attendance::where('status', 'mark-out')->where('emp_id', $user->id)->whereBetween('signin_date', [$fromDate, $toDate]) ->get();

        // Get all working_hours within the selected range
        $attendancesHours = Attendance::where('emp_id', $user->id)
            ->whereYear('signin_date', $selected_year)
            ->whereBetween('signin_date', [$fromDate, $toDate])
            ->where('status', 'mark-out')
            ->pluck('working_hours'); // Returns a collection of HH:MM:SS strings

            // Helper function to format seconds to HH:MM:SS
            function formatTime($seconds) {
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);

                return sprintf('%02d:%02d', $hours, $minutes);
            }
        $totalSeconds = 0;
        $validDays = 0;

        foreach ($attendancesHours as $time) {
            if (preg_match('/^(\d+):(\d{2}):(\d{2})$/', $time, $matches)) {
                $hours = (int) $matches[1];
                $minutes = (int) $matches[2];
                $seconds = (int) $matches[3];
                $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                $validDays++;
            }
        }

        $total_leaves_days = Leave::where('user_id', $user->id)
            // Adjust if your system uses a different label
            ->where('leave_type','=','half_day')
            ->whereMonth('leave_from', $fromDate)
            ->whereYear('leave_from', $selected_year)
            ->sum('leave_day_count');

        // $validDays = $validDays - $total_leaves_days;
        $validDays = max($validDays - $total_leaves_days, 0);

        // Helper to format seconds to H:i:s
        // function formatTime($seconds) {
        //     $hours = floor($seconds / 3600);
        //     $minutes = floor(($seconds % 3600) / 60);
        //     $seconds = $seconds % 60;

        //     return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        // }
        $totalWorkingHrs = round($totalSeconds/3600, 2);
        $data['totalWorkingTime']   = CustomHelper::decimalToHoursMinutes($totalWorkingHrs);  // $totalSeconds/3600;
        $data['averageWorkingTime'] = $validDays > 0 ? CustomHelper::decimalToHoursMinutes(round($totalWorkingHrs / $validDays, 2)) : '00:00:00'; //$validDays > 0 ? ($totalSeconds/3600) / $validDays : '00:00:00';
        $data['workingDays']        = $validDays;

        // Leave count
        $data['leaveCount'] = Leave::where('user_id', $user->id)
            // Adjust if your system uses a different label
            ->where('leave_type','!=','off_day')
            ->whereMonth('leave_from', $fromDate)
            ->whereYear('leave_from', $selected_year)
            ->sum('leave_day_count');

        $teamLeadIds                = Employee::whereNotNull('reporting_to')->distinct()->pluck('reporting_to');
        $data['uniqueTeamLeads']    = Employee::with('department', 'user')->whereIn('user_id', $teamLeadIds)->get();
        $data['worksBrakesData']    = CustomHelper::getMonthlyWorkBreakData($selected_user);
        $data['barChartData']       = CustomHelper::getMonthlyWorkBreakDataForBarChart($selected_user);
        $data['work_analysis']      = CustomHelper::getWorkRatingAnalysisMonthly($selected_user);

        /* Birthdays */
        $birthdayEmployees = Employee::select('full_name', 'profile_image')
            ->where('status','!=',4)
            ->whereMonth('dob', $today->month)
            ->whereDay('dob', $today->day)
            ->get();

        $feedData = collect();

        if ($birthdayEmployees->isNotEmpty()) {
            $birthdayFeed = [
                'type' => 'birthday',
                'display_date' => $today->format('d-F'),
                'sort_date' => $today->format('Y-m-d'),
                'employees' => $birthdayEmployees->map(function ($employee) {
                    return [
                        'full_name' => $employee->full_name,
                        'profile_image' => $employee->profile_image ?: '/profile_pics/default-avatar.png',
                    ];
                }),
            ];

            $feedData->push($birthdayFeed);
        }

         // WORK ANNIVERSARY FEED
            $workAnniversaryEmployees = Employee::select('full_name', 'profile_image', 'join_date')
                ->where('status','!=',4)
                ->whereMonth('join_date', $today->month)
                ->whereDay('join_date', $today->day)
                ->get();
            
            $workAnniversaryFeed = null;

            if ($workAnniversaryEmployees->isNotEmpty()) {

                $workAnniversaryFeed = [
                    'type' => 'work_anniversary',
                    'display_date' => $today->format('d-F'),
                    'sort_date' => $today->format('Y-m-d'),
                    'employees' => $workAnniversaryEmployees->map(function ($employee) use ($today) {

                        // Calculate Years of Service
                        $yearsCompleted = Carbon::parse($employee->join_date)->diffInYears($today);

                        return [
                            'full_name' => $employee->full_name,
                            'years' => $yearsCompleted,
                            'profile_image' => $employee->profile_image ?: '/profile_pics/default-avatar.png',
                        ];
                    }),
                ];
                 $feedData->push($workAnniversaryFeed);
            }

        /* Appreciations */
        $rawAppreciations = Appreciation::whereDate('display_date', '<=', $today)
            ->whereDate('display_end_date', '>=', $today)
            ->get();

        if ($rawAppreciations->isNotEmpty()) {
            $appreciations = $rawAppreciations->map(function ($appreciation) {
                $displayDate = Carbon::parse($appreciation->display_date);
                $employeeDetails = [];

                // Get IDs from the 'appreciant' string
                $ids = array_filter(array_map('trim', explode(',', $appreciation->appreciant)));

                if (!empty($ids)) {
                    $employees = Employee::with('user:id,email')
                        ->whereIn('user_id', $ids)
                        ->get(['id', 'user_id', 'full_name', 'profile_image']);

                    $employeeDetails = $employees->map(function ($employee) {
                        return [
                            'full_name' => $employee->full_name,
                            'email' => $employee->user?->email ?? '',
                            'profile_image' => $employee->profile_image ?: '/profile_pics/default-avatar.png',
                        ];
                    })->toArray();
                }

                return [
                    'type' => 'appreciation',
                    'display_date' => $displayDate->format('d-F'),
                    'sort_date' => $displayDate->format('Y-m-d'),
                    'employees' => $employeeDetails,
                    'message' => $appreciation->appreciation_details,
                    'image' => $appreciation->picture,
                ];
            });

            $feedData = $feedData->merge($appreciations);
        }

        $data['feed_data'] = $feedData->sortByDesc('sort_date')->values()->toArray();

        return view('dashboard', $data);
    }

    public function getNotifications(Request $request)
    {
        return auth()->user()->notifications;
    }

    public function getAnalytics(Request $request){
        $user = Auth::user();
        $range = $request->range;

        $now = Carbon::now();
        switch ($range) {
            case 'today':
                $from = $now->copy()->startOfDay();
                $to = $now->copy()->endOfDay();
                break;
            case 'yesterday':
                $from = $now->copy()->subDay()->startOfDay();
                $to = $now->copy()->subDay()->endOfDay();
                break;
            case 'last_7_days':
                $from = $now->copy()->subDays(6)->startOfDay();
                $to = $now->copy()->endOfDay();
                break;
            case 'last_30_days':
                $from = $now->copy()->subDays(29)->startOfDay();
                $to = $now->copy()->endOfDay();
                break;
            case 'last_month':
                $from = $now->copy()->subMonth()->startOfMonth();
                $to = $now->copy()->subMonth()->endOfMonth();
                break;
            default: // current_month
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                break;
        }

        $attendances = Attendance::where('emp_id', $user->id)
            ->where('status', 'mark-out')
            ->whereBetween('signin_date', [$from->toDateString(), $to->toDateString()])
            ->get();

        $totalWorkHours = Attendance::where('emp_id', $user->id)
            ->whereBetween('signin_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'mark-out')
            ->selectRaw('AVG(working_hours) as avg_hours, SUM(working_hours) as total_hours, COUNT(*) as days')
            ->first();


    $totalWorkingSeconds = 0;
    foreach ($attendances as $att) {
        if ($att->working_hours) {
            $parts = explode(':', $att->working_hours);
            $totalWorkingSeconds += ($parts[0] ?? 0) * 3600 + ($parts[1] ?? 0) * 60 + ($parts[2] ?? 0);
        }
    }

    $averageWorkingSeconds = count($attendances) ? $totalWorkingSeconds / count($attendances) : 0;

    $formatTime = fn($seconds) => gmdate('H:i:s', $seconds);

    $leaveCount = Leave::where('user_id', $user->id)
        ->where(function ($q) use ($from, $to) {
            $q->whereBetween('leave_from', [$from, $to])
              ->orWhereBetween('leave_to', [$from, $to]);
        })
        ->count();

    return response()->json([
        'totalWorkingTime' => $totalWorkHours->total_hours,
        'averageWorkingTime' => round($totalWorkHours->avg_hours, 2),
        'workingDays' => $attendances->count(),
        'leaveCount' => $leaveCount
    ]);
}

public function getLeaveSummary(Request $request)
{
    $user = Auth::user();
    $range = $request->range ?? 'current_month';
    $now = Carbon::now();

    switch ($range) {
        case 'today':
            $from = $now->copy()->startOfDay();
            $to = $now->copy()->endOfDay();
            break;
        case 'yesterday':
            $from = $now->copy()->subDay()->startOfDay();
            $to = $now->copy()->subDay()->endOfDay();
            break;
        case 'last_7_days':
            $from = $now->copy()->subDays(6)->startOfDay();
            $to = $now->copy()->endOfDay();
            break;
        case 'last_30_days':
            $from = $now->copy()->subDays(29)->startOfDay();
            $to = $now->copy()->endOfDay();
            break;
        case 'last_month':
            $from = $now->copy()->subMonth()->startOfMonth();
            $to = $now->copy()->subMonth()->endOfMonth();
            break;
        default: // current_month
            $from = $now->copy()->startOfMonth();
            $to = $now->copy()->endOfMonth();
            break;
    }

    // Fetch leaves within selected range
    $leavesInRange = Leave::where('user_id', $user->id)
        // ->whereBetween('leave_from', [$from, $to])
        ->get();

    // Get total allocation
    $leaveAllocated = LeaveAllocation::where('user_id', $user->id)
    ->where('year', now()->year)
    ->first();

    // Initialize counters
    $leaveThisMonth = 0;
    $totalLeavesTaken = 0;
    $pendingLeaves = 0;
    $fullLeaves = 0;
    $halfLeaves = 0;
    $offDays = 0;

    $leaveBalance = $leave_allocated?->remaining_leaves ?? 0;

    foreach ($leavesInRange as $leave) {
        $days = Carbon::parse($leave->leave_from)->diffInDays(Carbon::parse($leave->leave_to)) + 1;
        if ($leave->status == 2) { // Approved
            if ($leave->leave_type === 'full_day') {
                $fullLeaves += $days;
                $totalLeavesTaken += $days;
                if ($range === 'current_month') $leaveThisMonth += $days;
            } elseif ($leave->leave_type === 'half_day') {
                $halfLeaves += $days;
                $totalLeavesTaken += $days * 0.5;
                if ($range === 'current_month') $leaveThisMonth += $days * 0.5;
            } elseif ($leave->leave_type === 'off_day') {
                $offDays += $days;
                // offDays typically not counted in leave totals
            }
        } elseif ($leave->status == 1) { // Pending
            $pendingLeaves += $days;
        }
    }

    // Calculate total leaves taken in the **current year**
    $yearStart = $now->copy()->startOfYear();
    $yearEnd = $now->copy()->endOfYear();
    $currentYearLeaves = Leave::where('user_id', $user->id)
        ->whereBetween('leave_from', [$yearStart, $yearEnd])
        ->where('status', 2)
        ->get()
        ->reduce(function ($carry, $leave) {
            $days = Carbon::parse($leave->leave_from)->diffInDays(Carbon::parse($leave->leave_to)) + 1;
            if ($leave->leave_type === 'half_day') {
                return $carry + ($days * 0.5);
            } elseif ($leave->leave_type === 'full_day') {
                return $carry + $days;
            }
            return $carry; // ignore off_day
        }, 0);

    // Past year leave count
    $pastYearLeaves = Leave::where('user_id', $user->id)
        ->whereYear('leave_from', $now->copy()->subYear()->year)
        ->where('status', 2)
        ->get()
        ->reduce(function ($carry, $leave) {
            $days = Carbon::parse($leave->leave_from)->diffInDays(Carbon::parse($leave->leave_to)) + 1;
            if ($leave->leave_type === 'half_day') {
                return $carry + ($days * 0.5);
            } elseif ($leave->leave_type === 'full_day') {
                return $carry + $days;
            }
            return $carry;
        }, 0);

    return response()->json([
        'leaveThisMonth' => $leaveThisMonth,
        'totalLeavesTaken' => $currentYearLeaves,
        'pendingLeaves' => $pendingLeaves,
        'pastYearLeaves' => $pastYearLeaves,
        'totalLeavesAllotted' => $leave_allocated?->total_leaves ?? 0,
        'fullLeaves' => $fullLeaves,
        'halfLeaves' => $halfLeaves,
        'offDays' => $offDays,
        'leaveBalance' => $leaveBalance
    ]);
}

public function showChangeForm()
{
    return view('auth.passwords.force_change');
}

public function change(Request $request)
{

     $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = true;
        $user->save();

        return redirect()->route('home')->with('success', 'Password changed successfully.');
}

}
