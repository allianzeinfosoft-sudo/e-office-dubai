<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Project;
use App\Models\workReport;
use App\Models\CustomAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Log;
class AttendanceController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
          // Restrict access to admin role
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $data['meta_title']     = 'Attendance';

        $user = Auth::user();
        $today = now()->format('Y-m-d');
        $currentMonth = now()->format('Y-m');
        $daysInMonth = now()->daysInMonth;
        $weekOffDays = [0, 6]; // Assuming Sunday (0) and Saturday (6) are week-offs

        // Fetch all holidays in the current month
        $holidays = DB::table('holidays')
            ->whereBetween('date', ["$currentMonth-01", "$currentMonth-$daysInMonth"])
            ->pluck('date')
            ->toArray();

        // Fetch all attendance records for the user in this month
        $attendanceDays = Attendance::where('username', $user->username)
            ->whereBetween('signin_date', ["$currentMonth-01", "$currentMonth-$daysInMonth"])
            ->pluck('signin_date')
            ->toArray();

        // Identify absent days (days where the user has no attendance record)
        $absentDays = [];
        for ($day = 1; $day < now()->day; $day++) { // Exclude today
            $date = "$currentMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $dayOfWeek = date('w', strtotime($date));

            // If no attendance record exists for this day and it's not a holiday or a week off, mark as absent
            if (!in_array($date, $attendanceDays) && !in_array($date, $holidays) && !in_array($dayOfWeek, $weekOffDays)) {
                $absentDays[] = $date;
            }
        }

        // If there are any absent working days, redirect user to apply for leave
        if (!empty($absentDays)) {
            return redirect()->route('leaves.create')->with('error', 'You have absent working days. Please apply for leave before punching in.');
        }
        
        $data['attendance']     = Attendance::where(['username' => Auth::user()->username, 'signin_date' => now()->format('Y-m-d')])->first();
        $data['days_of_worked'] = Attendance::where('username', Auth::user()->username)->whereMonth('signin_date', now()->month)->count();

        $daysInMonth    = now()->daysInMonth;
        $workedHours    = [];
        $categories     = [];
        $weekOffDays    = [];
        $totalMinutes   = 0;
        $workedDays     = 0;

        if (!empty($data['attendance']->signout_time)) {
            $todayMinutes = Attendance::where('username', Auth::user()->username)->whereDate('signin_date', now())
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
            $todayMinutes = Attendance::where('username', Auth::user()->username)->whereDate('signin_date', now())
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

        $missingMarkOut = Attendance::where('username', Auth::user()->username)
        ->where('signin_date', '<', now()->format('Y-m-d')) // Check dates before today
        ->whereNull('signout_time') // No sign-out time means the user has not marked out
        ->first();

        if ($missingMarkOut) {
            // If there's a missing mark-out, don't allow marking in
            $data['meta_title'] = 'Mark Out First';
            $data['missingMarkOut'] = $missingMarkOut; // Pass the missing mark-out date to the view
            return view('attendance.markOut', $data); // Show a page telling the user to mark out first
        }


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

         /* $missingReport = Attendance::where('status', 'mark-out') -> whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('work_reports')
                ->whereColumn('work_reports.report_date', 'attendances.signin_date')
                ->whereColumn('work_reports.username', 'attendances.username');
        })->first();  */

        $missingReport = Attendance::leftJoin('work_reports', function ($join) {
            $join->on('work_reports.report_date', '=', 'attendances.signin_date')
                 ->on('work_reports.username', '=', 'attendances.username');
        })

        ->select(
            'attendances.id',
            'attendances.emp_id',
            'attendances.username',
            'attendances.signin_date',
            'attendances.working_hours',
            'attendances.break_time',
            'attendances.status',
            DB::raw('COALESCE(SUM(TIME_TO_SEC(work_reports.total_time)), 0) as total_reported_time'),
            DB::raw('TIME_TO_SEC(attendances.working_hours) as total_attendance_time')
        )
        ->groupBy(
            'attendances.id',
            'attendances.emp_id',
            'attendances.username',
            'attendances.signin_date',
            'attendances.working_hours',
            'attendances.break_time',
            'attendances.status'
        )
        ->havingRaw('total_reported_time < total_attendance_time') // Ensure reported time is less than attendance time
        ->where('attendances.status', 'mark-out') 
        ->first();

        //dd($missingReport);

        if ($missingReport) { 
            $attendance = Attendance::where('emp_id', $missingReport->emp_id)
                ->where('signin_date', $missingReport->signin_date)
                ->first();
            
            // ✅ Ensure 'working_hours' is correctly converted to seconds
            if (strpos($attendance->working_hours, ':') !== false) {
                list($hours, $minutes, $seconds) = explode(":", $attendance->working_hours);
            } else {
                // Default seconds to 00 if missing
                list($hours, $minutes) = explode(":", $attendance->working_hours);
                $seconds = 0;
            }
            $totalAttendanceTime = ($hours * 3600) + ($minutes * 60) + $seconds;

            // ✅ Sum reported time in seconds (using TIME_TO_SEC)
            $totalReportedTime = WorkReport::where('emp_id', $missingReport->emp_id)
                ->where('report_date', $missingReport->signin_date)
                ->sum(DB::raw('TIME_TO_SEC(total_time)'));

            // 🔍 Debug: Log values to check
            Log::info("Attendance Time: {$attendance->working_hours} -> $totalAttendanceTime seconds");
            Log::info("Reported Time: $totalReportedTime seconds");

            // ✅ Calculate balance time
            $balanceTime = max($totalAttendanceTime - $totalReportedTime, 0);
            $formattedBalanceTime = gmdate("H:i:s", $balanceTime);

            Log::info("Balance Time: $formattedBalanceTime");

            $missingReport->balance_time = $formattedBalanceTime;
        
            $data['meta_title'] = 'Add Work Report';
            $data['projects'] = Project::all();
            $data['missingReport'] = $missingReport;
            $data['repots_posted'] = WorkReport::with(['project', 'projectTask'])
                ->where('username', Auth::user()->username)
                ->where('report_date', $missingReport->signin_date)
                ->get();
            
            return view('attendance.work_report', $data);
        } else {
            return view('attendance.index', $data);
        }
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
            'break_time' => '01:00:00',
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
        $workingTime = CustomHelper::calculateTotalWorkingTime($attendance->signin_date, $attendance->signin_time, now()->format('Y-m-d'), now()->format('H:i:s'), $attendance->break_time);

        $attendance->update([
            'signout_time' => now()->format('H:i:s'),
            'signout_date' => now()->format('Y-m-d'),
            'punchout_type' => 'Web',
            'status' => 'mark-out',
            'working_hours' => $workingTime['total_working_time']
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
            'status' => 'custom',
            'custom_status' => '1'
        ]);

        // Store data in `custom_attendances` table
        $customAttendance = CustomAttendance::create([
            'username' => Auth::user()->username,
            'emp_id' => Auth::user()->id,
            'picktime' => $request->signin_time,
            'reason' => $request->signin_late_note,
            'signin_date' => date('Y-m-d', strtotime($request->signin_date)),
            'status' => 0, // Assuming 0 means pending status
            'approved_by' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'data' => [
                'signin_time' => date('h:i A', strtotime($attendance->signin_time))
            ]
        ]);
    }

    public function customMarkOut(Request $request, $id) {
        $request->validate([
            'signout_time' => 'required',
            'signout_late_note' => 'required',
        ]);

        $markOut = Attendance::findOrFail($id);
        $workingTime = CustomHelper::calculateTotalWorkingTime($markOut->signin_date, $markOut->signin_time, $request->signout_date, $request->signout_time, $markOut->break_time);
        $markOut->signout_time = $request->signout_time;
        $markOut->signout_date = $request->signout_date;
        $markOut->signout_late_note = $request->signout_late_note;
        $markOut->status = 'mark-out';
        $markOut->punchout_type = 'custom';
        $markOut->working_hours = $workingTime['total_working_time'] ?? 0;
        $markOut->save();

        return response()->json(['success' => true, 'message' => 'Mark out updated successfully.']);
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
