<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Restrict access to admin role
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $data['meta_title'] = 'Attendance';
    
        $data['attendance'] = Attendance::where([
            'username' => Auth::user()->username, 
            'signin_date' => now()->format('Y-m-d')
        ])->first();
    
        $data['days_of_worked'] = Attendance::where('username', Auth::user()->username)
            ->whereMonth('signin_date', now()->month)
            ->count();
    
        $daysInMonth = now()->daysInMonth;
        $workedHours = [];
        $categories = [];
        $weekOffDays = [];
        $totalMinutes = 0;
        $workedDays = 0;

        if (!empty($data['attendance']->signout_time)) {
            $todayMinutes = Attendance::where('username', Auth::user()->username)
            ->whereDate('signin_date', now())
            ->selectRaw("
                COALESCE(
                    SUM(
                        TIMESTAMPDIFF(
                            MINUTE, 
                            STR_TO_DATE(signin_time, '%H:%i:%s'), 
                            STR_TO_DATE(signout_time, '%H:%i:%s')
                        )
                    ), 0
                ) as today_minutes
            ")
            ->value('today_minutes') ?? 0;
        } else {
            // If signout_time is not available, calculate up to current time
            $todayMinutes = Attendance::where('username', Auth::user()->username)
                ->whereDate('signin_date', now())
                ->selectRaw("
                    COALESCE(
                        SUM(
                            TIMESTAMPDIFF(
                                MINUTE, 
                                STR_TO_DATE(signin_time, '%H:%i:%s'), 
                                STR_TO_DATE(?, '%H:%i:%s')
                            )
                        ), 0
                    ) as today_minutes
                ", [now()->format('H:i:s')])
                ->value('today_minutes') ?? 0;
        }

        $todayHours                      = intdiv($todayMinutes, 60);
        $todayMins                       = $todayMinutes % 60;
        $data['todayWorkedHours']        = sprintf('%02d:%02d', $todayHours, $todayMins);
        $data['todayProgressPercentage'] = min(round(($todayMinutes / 480) * 100), 100);

    
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = now()->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('w', strtotime($date)); // 0 = Sunday, 6 = Saturday
    
            // Mark week off days and skip them from calculations
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $weekOffDays[] = $day;
                continue;
            } 
    
            // Fetch worked minutes for the day
            $minutes = Attendance::where('username', Auth::user()->username)
                ->where('signin_date', $date)
                ->selectRaw("
                    IFNULL(
                        SUM(
                            TIMESTAMPDIFF(
                                MINUTE, 
                                STR_TO_DATE(signin_time, '%H:%i:%s'), 
                                STR_TO_DATE(signout_time, '%H:%i:%s')
                            )
                        ), 0
                    ) as worked_hours
                ")
                ->value('worked_hours') ?? 0;
    
            if ($minutes > 0) {
                $workedDays++;
            }
    
            $totalMinutes += $minutes;
    
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
    
            $workedHours[] = sprintf('%02d:%02d', $hours, $mins);
            $categories[] = $day;
        }
    
        // Total working days (excluding week off days)
        $totalWorkingDays = $daysInMonth - count($weekOffDays);
    
        // Total worked hours and minutes
        $totalHours = floor($totalMinutes / 60);
        $totalMins = $totalMinutes % 60;
        $data['totalWorkedHours'] = sprintf('%02d:%02d', $totalHours, $totalMins);
    
        // Assuming an 8-hour workday
        $possibleMinutes = $totalWorkingDays * (8 * 60); // Total possible working minutes
    
        // Calculate progress percentage
        $data['progressPercentage'] = $possibleMinutes > 0 
            ? round(($totalMinutes / $possibleMinutes) * 100) 
            : 0;
    
        // Calculate average worked hours per day
        if ($workedDays > 0) {
            $avgMinutes = round($totalMinutes / $workedDays);
            $avgHours = floor($avgMinutes / 60);
            $avgMins = $avgMinutes % 60;
            $data['avgWorkedHours'] = sprintf('%02d:%02d', $avgHours, $avgMins);
            $data['avgProgressPercentage'] = min(round(($avgMinutes / 480) * 100), 100);
        } else {
            $data['avgWorkedHours'] = '00:00';
            $data['avgProgressPercentage'] = 0;
        }
    
        // Pass data to frontend
        $data['categories'] = $categories;
        $data['seriesData'] = $workedHours;
        $data['weekOffDays'] = $weekOffDays;
        $data['totalWorkingDays'] = $totalWorkingDays;
    
        return view('attendance.index', $data);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function markIn(Request $request) {
        $existingAttendance = Attendance::where('emp_id', Auth::user()->id)->where('signin_date', now()->format('Y-m-d'))->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked attendance today.',
                'data' => [
                    'signin_time' => date('h:i A', strtotime($existingAttendance->signin_time))
                ]
            ]);
        }

        $attendance = Attendance::create([
            'username' => Auth::user()->username,
            'emp_id' => Auth::user()->id,
            'signin_date' => now()->format('Y-m-d'),
            'signin_time' => now()->format('H:i:s'),
            'punchin_type' => 'Web',
            'ipaddress' => $request->ip(),
            'status' => 'mark-in'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'data' => [
                'signin_time' => date('h:i A', strtotime($attendance->signin_time))
            ]
        ]);

    }

    public function markOut(Request $request) {
        $attendance = Attendance::where([
            'username' => Auth::user()->username,
            'signin_date' => now()->format('Y-m-d')
        ])->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have not marked in yet.',
            ]);
        }

        if ($attendance->signout_time) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked out today.',
                'data' => [
                    'signout_time' => date('h:i A', strtotime($attendance->signout_time))
                ]
            ]);
        }

        $attendance->update([
            'signout_time' => now()->format('H:i:s'),
            'signout_date' => now()->format('Y-m-d'),
            'punchout_type' => 'Web',
            'status' => 'mark-out'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked out successfully',
            'data' => [
                'signout_time' => date('h:i A', strtotime($attendance->signout_time))
            ]
        ]);
    }

    public function customMarkIn(Request $request) {
        $existingAttendance = Attendance::where('emp_id', Auth::user()->id)->where('signin_date', now()->format('Y-m-d'))->first();
        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked attendance today.',
                'data' => [
                    'signin_time' => date('h:i A', strtotime($existingAttendance->signin_time))
                ]
            ]);
        }

        $attendance = Attendance::create([
            'username' => Auth::user()->username,
            'emp_id' => Auth::user()->id,
            'signin_date' => date('Y-m-d', strtotime($request->signin_date)),
            'signin_time' => $request->signin_time,
            'signin_late_note' => $request->signin_late_note,
            'punchin_type' => 'Custom',
            'ipaddress' => $request->ip(),
            'status' => 'custom'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'data' => [
                'signin_time' => date('h:i A', strtotime($attendance->signin_time))
            ]
        ]);
    }

    /* Emergency mark-in mark-out*/
    public function emergencyMark(Request $request)
    {
        
        $data = [
            'username' => Auth::user()->username,
            'signin_time' => $request->type === 'mark-in' ? $request->time_in_out : null,
            'signin_late_note' => $request->type === 'mark-in' ? $request->signin_late_note : null,
            'signout_late_note' => $request->type === 'mark-out' ? $request->signin_late_note : null,
            'signout_time' => $request->type === 'mark-out' ? $request->time_in_out : null,
            'status' => 'emergency',
            'punchin_type' => $request->type === 'mark-in' ? 'emergency' : null,
            'punchout_type' => $request->type === 'mark-out' ? 'emergency' : null,
        ];

        if($request->type === 'mark-in'){
            $data['signin_date'] = date('Y-m-d', strtotime($request->signin_date)); 
        }else{
            $data['signout_date'] = date('Y-m-d', strtotime($request->signin_date)); 
        }

        Attendance::updateOrCreate(
            ['username' => Auth::user()->username, 'signin_date' => $request->signin_date],
            $data
        );

        return response()->json([
            'success' => true,
            'message' => $request->type === 'mark-in' ? 'Marked In successfully!' : 'Marked Out successfully!'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
