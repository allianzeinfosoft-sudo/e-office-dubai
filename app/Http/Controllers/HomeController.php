<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\helpers\CustomHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\LeaveAllocation;
use Carbon\Carbon;

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
        $data['holidays'] = Holiday::where('holiday_group', $data['employee']?->holidayGroup)->get();

        $user = Auth::user();
        $fromDate = Carbon::now()->startOfMonth();
        $toDate = Carbon::now()->endOfMonth();

        // Total Working Hours
        $attendances = Attendance::where('status', 'mark-out')->where('emp_id', $user->id)->whereBetween('signin_date', [$fromDate, $toDate]) ->get();

        $totalWorkHours = Attendance::where('emp_id', $user->id)
                ->whereYear('signin_date', $selected_year)
                ->whereBetween('signin_date', [$fromDate, $toDate])
                ->where('status', 'mark-out')
                ->selectRaw('AVG(working_hours) as avg_hours, SUM(working_hours) as total_hours, COUNT(*) as days')
                ->first();


        // Convert seconds to H:i:s
        function formatTime($seconds) {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        $data['totalWorkingTime']   = $totalWorkHours->total_hours;
        $data['averageWorkingTime'] = $totalWorkHours->avg_hours;
        $data['workingDays']        = $attendances->count();

        // Leave count
        $data['leaveCount'] = Leave::where('user_id', $user->id)->where(function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('leave_from', [$fromDate, $toDate])->orWhereBetween('leave_to', [$fromDate, $toDate]);
        })->count();

        $teamLeadIds = Employee::whereNotNull('reporting_to')->distinct()->pluck('reporting_to');
        $data['uniqueTeamLeads'] = Employee::with('department', 'user')->whereIn('user_id', $teamLeadIds)->get();
        $data['worksBrakesData'] = CustomHelper::getMonthlyWorkBreakData($selected_user);
        $data['barChartData'] = CustomHelper::getMonthlyWorkBreakDataForBarChart($selected_user);
        $data['work_analysis']  = CustomHelper::getWorkRatingAnalysisMonthly($selected_user);

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

    // Fetch leaves within the selected range
    $leavesInRange = Leave::where('user_id', $user->id)
        ->whereBetween('leave_from', [$from, $to])
        ->get();

    $leave_allocated = LeaveAllocation::where('user_id',$user->id)->first();
    // Initialize counters
    $leaveThisMonth = 0;
    $totalLeavesTaken = 0;
    $pendingLeaves = 0;
    $fullLeaves = 0;
    $halfLeaves = 0;
    $offDays = 0;
    $leaveBalance = 0;

    foreach ($leavesInRange as $leave) {
        // Calculate the number of days for each leave
        $days = Carbon::parse($leave->leave_from)->diffInDays(Carbon::parse($leave->leave_to)) + 1;



        if ($leave->status == 2) {

            $totalLeavesTaken += $days;
            $leaveThisMonth += $days;

            if ($leave->leave_type === 'full_day') {
                $fullLeaves += $days;
            } elseif ($leave->leave_type === 'haf_day') {
                $halfLeaves += $days;
            } elseif ($leave->leave_type === 'off_day') {
                $offDays += $days;
            }

        } elseif ($leave->status == 1) {
            $pendingLeaves += $days;
        }


    }

    // Calculate past year leaves
    $pastYearLeaves = Leave::where('user_id', $user->id)
        ->whereYear('leave_from', $now->copy()->subYear()->year)
        ->get()
        ->reduce(function ($carry, $leave) {
            $days = Carbon::parse($leave->leave_from)->diffInDays(Carbon::parse($leave->leave_to)) + 1;
            return $carry + $days;
        }, 0);

    // Define total leaves allotted (can be made dynamic)
     $totalLeavesAllotted = $leave_allocated ? $leave_allocated->total_leaves : 0;
     $leaveBalance = $leave_allocated ? $leave_allocated->remaining_leaves : 0;

    return response()->json([
        'leaveThisMonth' => ((int) $leaveThisMonth - (int) $offDays),
        'totalLeavesTaken' => $totalLeavesTaken,
        'pendingLeaves' => $pendingLeaves,
        'pastYearLeaves' => $pastYearLeaves,
        'totalLeavesAllotted' => $totalLeavesAllotted,
        'fullLeaves' => $fullLeaves,
        'halfLeaves' => $halfLeaves,
        'offDays' => $offDays,
        'leaveBalance' => $leaveBalance
    ]);
}

}
