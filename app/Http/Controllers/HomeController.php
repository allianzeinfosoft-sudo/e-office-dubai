<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\helpers\CustomHelper;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
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
        $selected_user =  auth()->id();
        $selected_year =  date('Y');

        $data['employee'] = Employee::with('department', 'designation', 'workshift', 'reportingToEmployee')->where('user_id', $selected_user)->first();
        $data['attendance_analytics'] = CustomHelper::currentAttendanceAnalytics($selected_user, $selected_year);

        $user = Auth::user();
        $fromDate = Carbon::now()->startOfMonth();
        $toDate = Carbon::now()->endOfMonth();

        // Total Working Hours
        $attendances = Attendance::where('emp_id', $user->id) ->whereBetween('signin_date', [$fromDate, $toDate]) ->get();

        $totalWorkingSeconds = 0;
        foreach ($attendances as $att) {
            $totalWorkingSeconds += strtotime($att->working_hours ?? '00:00:00') - strtotime('TODAY');
        }

        $averageWorkingSeconds = count($attendances) > 0 ? $totalWorkingSeconds / count($attendances) : 0;

        // Convert seconds to H:i:s
        function formatTime($seconds) {
            return gmdate('H:i:s', $seconds);
        }

        $data['totalWorkingTime'] = formatTime($totalWorkingSeconds);
        $data['averageWorkingTime'] = formatTime($averageWorkingSeconds);
        $data['workingDays'] = $attendances->count();

        // Leave count
        $data['leaveCount'] = Leave::where('user_id', $user->id) ->where(function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('leave_from', [$fromDate, $toDate])->orWhereBetween('leave_to', [$fromDate, $toDate]);
        })->count();
        
        return view('dashboard', $data);
    }

    public function getNotifications(Request $request)
    {
        return auth()->user()->notifications;
    }

    public function getAnalytics(Request $request)
{
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
        ->whereBetween('signin_date', [$from->toDateString(), $to->toDateString()])
        ->get();

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
        'totalWorkingTime' => $formatTime($totalWorkingSeconds),
        'averageWorkingTime' => $formatTime($averageWorkingSeconds),
        'workingDays' => $attendances->count(),
        'leaveCount' => $leaveCount
    ]);
}
}
