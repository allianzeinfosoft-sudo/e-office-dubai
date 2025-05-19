<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Project;
use App\Models\workReport;
use App\Models\CustomAttendance;
use App\Models\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

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
        $data['meta_title'] = 'Attendance';
        
        $user               = Auth::user();
        $today              = now()->format('Y-m-d');
        $currentMonth       = now()->format('Y-m');
        $daysInMonth        = now()->daysInMonth;
        $weekOffDays        = [0, 6]; // Sunday = 0, Saturday = 6 

        $data['attendance']     = Attendance::where(['username' => Auth::user()->username, 'signin_date' => now()->format('Y-m-d')])->first();
        $data['employee'] = Employee::with('workshift')->where('user_id', Auth::user()->id)->first();
        $data['days_of_worked'] = Attendance::where('username', Auth::user()->username)->whereMonth('signin_date', now()->month)->count();

        if($user->employee?->join_date == $today){
            return view('attendance.index', $data);
        }

        /* cutofftime */
        /* $cutoffTime = $user->employee->login_limited_time ?? '09:15:00';
        $isLate = now()->format('H:i:s') > $cutoffTime;
        $data['disableCustomMarkIn'] = $isLate; */

        $shiftStartTime = $data['employee']?->workshift?->shift_start_time;

        if ($shiftStartTime) {
            // Convert shift start time to Carbon
            $shiftTime = Carbon::createFromFormat('H:i:s', $shiftStartTime);

            // Define allowed window
            $earliestMarkIn = $shiftTime->copy()->subMinutes(30);
            $latestMarkIn   = $shiftTime->copy()->addMinutes(15);
            $now            = now();

            // Disable mark-in outside allowed window
            $data['disableCustomMarkIn'] = !($now->between($earliestMarkIn, $latestMarkIn));
        } else {
            // Fallback: Disable mark-in if no shift time is defined
            $data['disableCustomMarkIn'] = true;
        }
        
        // Fetch all holidays in the current month
        $holidays = DB::table('holidays') ->whereBetween('date', ["$currentMonth-01", "$currentMonth-$daysInMonth"])->pluck('date')->toArray();

        // Fetch all attendance records for the user in this month
        $attendanceDays = Attendance::where('username', $user->username)->whereBetween('signin_date', ["$currentMonth-01", "$currentMonth-$daysInMonth"])->pluck('signin_date')->toArray();

        for ($day = 1; $day < now()->day; $day++) {
            $date           = "$currentMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $dayOfWeek      = date('w', strtotime($date));
            $isWeekOff      = in_array($dayOfWeek, $weekOffDays);
            $isHoliday      = in_array($date, $holidays);
            $hasAttendance  = in_array($date, $attendanceDays);
            
            if (!$isWeekOff && !$isHoliday && !$hasAttendance) {
                // Check if leave exists that covers this date
                $leaveExists = DB::table('leaves')
                ->where('user_id', $user->id)
                ->whereDate('leave_from', '<=', $date)
                ->whereDate('leave_to', '>=', $date)
                ->exists();
                
                if (!$leaveExists) {
                    // User missed work on a working day without leave
                    $data['date'] = $date;
                    $data['error'] = "You missed work on ". date('d-m-Y', strtotime($date))  ." without apply leave. Please click here to  
                    <a class='btn btn-xs btn-primary' href='" . route('leaves.create', ['date' => $date]) . "'> Apply Leave </a>";
                    return view('attendance.no_action_from', $data);
                    /* return redirect()->route('leaves.create', ['date' => $date])
                        ->with('error', "You missed work on $date without leave. Please apply for leave."); */
                }
            }
        }

        

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

        /* incomplete working hours */
        $nonApprovedIncompleteWorkingHours = Attendance::where('username', $user->username)->where('is_incomplete', 1)->where('incomplete_approved', 0)->count();

        if ($nonApprovedIncompleteWorkingHours > 0) {
            return view('attendance.no_action_from', $data);
        }
        


        /* Mission Mark Out First */
        $missingMarkOut = Attendance::where('username', Auth::user()->username)
        ->where('signin_date', '<', now()->format('Y-m-d')) // Check dates before today
        ->whereNull('signout_time') // No sign-out time means the user has not marked out
        ->first();

        if ($missingMarkOut) {
            // If there's a missing mark-out, don't allow marking in
            $data['meta_title'] = 'Mark Out First';
            $data['missingMarkOut'] = $missingMarkOut; // Pass the missing mark-out date to the view

            $data['error'] = "You missed to Mark-out on ". date('d-m-Y', strtotime($missingMarkOut->signin_date)) ;
            return view('attendance.no_action_from', $data);

            //return view('attendance.markOut', $data); // Show a page telling the user to mark out first
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

        $missingReport = Attendance::where('attendances.emp_id', Auth::user()->id)
        ->leftJoin('work_reports', function ($join) {
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
        ->havingRaw('total_reported_time < total_attendance_time')
        ->where('attendances.status', 'mark-out')
        ->orderBy('attendances.signin_date', 'desc')
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
    
        $workingTime = CustomHelper::calculateTotalWorkingTime(
            $attendance->signin_date,
            $attendance->signin_time,
            now()->format('Y-m-d'),
            now()->format('H:i:s'),
            $attendance->break_time
        );
    
        $isIncomplete = strtotime($workingTime['total_working_time']) < strtotime('08:00:00') ? 1 : 0;
    
        $attendance->update([
            'signout_time' => now()->format('H:i:s'),
            'signout_date' => now()->format('Y-m-d'),
            'punchout_type' => 'Web',
            'status' => 'mark-out',
            'working_hours' => $workingTime['total_working_time'],
            'is_incomplete' => $isIncomplete
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
        $userId = Auth::user()->id;
        $signinDate = date('Y-m-d', strtotime($request->signin_date));

        $employee = Employee::where('user_id', $userId)->first();       
        
        $existingAttendance = Attendance::where('emp_id', $userId)
        ->where('signin_date', $signinDate)
        ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked attendance today.',
                'data' => [
                    'signin_time' => date('h:i A', strtotime($existingAttendance->signin_time))
                ]
            ]);
        }

        // Store data in `custom_attendances` table
        $customAttendance = CustomAttendance::where('emp_id', $userId)
        ->where('signin_date', $signinDate)
        ->first();

        if ($customAttendance) {
            // Update the existing custom attendance request
            $customAttendance->update([
                'picktime'    => $request->signin_time,
                'reason'      => $request->signin_late_note ?? 'custom Mark In',
                'status'      => 0, // Reset to pending on update
                'approved_by' => null
            ]);
    
            $message = 'Your custom Mark In request has been updated and sent for re-approval.';
        } else {

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $monthlyCustomMarkings = CustomAttendance::where('emp_id', $userId) ->whereMonth('signin_date', $currentMonth)->whereYear('signin_date', $currentYear)->count();
            
            if ($monthlyCustomMarkings > 5) {
                
                CustomAttendance::create([
                    'username'    => Auth::user()->username,
                    'emp_id'      => $userId,
                    'picktime'    => $request->signin_time,
                    'reason'      => $request->signin_late_note ?? 'custom Mark In',
                    'signin_date' => $signinDate,
                    'status'      => 0,
                    'approved_by' => null,
                    'approver'    => $employee['reporting_to']
                ]);

            }else{

                CustomAttendance::create([
                    'username'    => Auth::user()->username,
                    'emp_id'      => $userId,
                    'picktime'    => $request->signin_time,
                    'reason'      => $request->signin_late_note ?? 'custom Mark In',
                    'signin_date' => $signinDate,
                    'status'      => 0,
                    'approved_by' => null,
                ]);
            }
    
            $message = 'Your custom Mark In has been sent for approval.';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function customMarkOut(Request $request, $id) {
        $request->validate([
            'signout_time'      => 'required',
            'signout_late_note' => 'required',
        ]);
    
        $markOut = Attendance::findOrFail($id);
    
        $workingTime = CustomHelper::calculateTotalWorkingTime(
            $markOut->signin_date,
            $markOut->signin_time,
            $request->signout_date,
            $request->signout_time,
            $markOut->break_time
        );
    
        $totalWorkingTime = $workingTime['total_working_time'] ?? '00:00:00';
    
        $markOut->signout_time      = $request->signout_time;
        $markOut->signout_date      = $request->signout_date;
        $markOut->signout_late_note = $request->signout_late_note;
        $markOut->status            = 'mark-out';
        $markOut->punchout_type     = 'custom';
        $markOut->working_hours     = $totalWorkingTime;
    
        if (strtotime($totalWorkingTime) < strtotime('08:00:00')) {
            $markOut->is_incomplete = 1;
        }
    
        $markOut->save();
    
        return response()->json(['success' => true, 'message' => 'Mark out updated successfully.']);
    }


    /* Emergency mark-in mark-out*/
    public function emergencyMark(Request $request){
        $username = Auth::user()->username;
        $userId = Auth::user()->id;
        $signinDate = date('Y-m-d', strtotime($request->signin_date));
        $time = $request->time_in_out;
        $lateNote = $request->signin_late_note;
        
        if ($request->type === 'mark-in') {
            // Check if already marked in
            $existing = Attendance::where('username', $username)
                ->whereDate('signin_date', $signinDate)
                ->where('status', 'emergency')
                ->first();

            if (!$existing) {
                Attendance::create([
                    'username' => $username,
                    'emp_id' => $userId,
                    'status' => 'emergency',
                    'signin_date' => $signinDate,
                    'signin_time' => $time,
                    'punchin_type' => 'emergency',
                    'signin_late_note' => $lateNote,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Marked In successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Already marked in for this date (emergency).',
                ]);
            }

        } elseif ($request->type === 'mark-out') {
            $existing = Attendance::where('username', $username)
                ->whereDate('signin_date', $signinDate)
                ->where('status', 'emergency')
                ->first();

            if ($existing) {
                $workingTime = CustomHelper::calculateTotalWorkingTime(
                    $existing->signin_date,
                    $existing->signin_time,
                    $signinDate,
                    $time,
                    '00:00:00'
                );
            
                $totalWorkingTime = $workingTime['total_working_time'] ?? '00:00:00';

                $existing->update([
                    'signout_date' => $signinDate,
                    'signout_time' => $time,
                    'status' => 'mark-out',
                    'punchout_type' => 'emergency',
                    'signout_late_note' => $lateNote,
                    'working_hours' => $totalWorkingTime,
                    'break_time' => '00:00:00',
                ]);
                
                // return redirect()->route('work-report.emerbency-work-report')->with('success', 'Marked Out successfully!');
    
                return response()->json([
                    'success' => true,
                    'message' => 'Marked Out successfully!',
                ]); 
                
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot mark out without a matching emergency mark-in.',
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid type provided.',
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
    public function destroy($id)
    {
        //
        $item = Attendance::findOrFail($id);
        $item->delete();

        return response()->json(['success' => true]);
    }

    public function markedInList(){
        $markedInListData = Attendance::with('employee')
            //->where('signin_date', date('Y-m-d'))
            ->whereIn('status', ['mark-in', 'custom'])
            ->orderBy('signin_time')
            ->get();

            
            $data = $markedInListData->map(function ($markInList) {
                
            $employee = optional($markInList->employee);
                $image = $employee->profile_image
                    ? asset('storage/' . $employee->profile_image)
                    : asset('assets/img/avatars/1.png');

                return [
                    'id' => $markInList->id,
                    'profile_image' => '<div class="avatar-wrapper"><div class="avatar avatar-sm me-3"><img src="'. $image . '" alt="Avatar" class="rounded-circle"></div></div>',
                    'name' => $employee->full_name ?? '',
                    'username' => $markInList->username ?? '',
                    'markin_date' => $markInList->signin_date ?? '' ,
                    'markin_time' => $markInList->signin_time ?? '',
                ];
            });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function employeeMarkin($id){
        $data = Attendance::with('employee')->find($id);
        return response()->json([
            'success' => true,
            'data' =>$data
        ]);
    }


    public function customAttendanceEntry(Request $request)
    {
        $request->validate([
            'employee' => 'required',
            'signin_date' => 'required',
            'signin_time' => 'required'
        ]);

        $employeeId = $request->employee;
        $signinDate = date('Y-m-d', strtotime($request->signin_date));
        $signinTime = $request->signin_time;

        $Employee = Employee::with('user')->where('user_id', $employeeId)->firstOrFail();

        // Save or update Attendance
        $attendance = Attendance::updateOrCreate(
            [
                'emp_id' => $employeeId,
                'signin_date' => $signinDate,
            ],
            [
                'username' => $Employee->user->username,
                'signin_time' => $signinTime,
                'signin_late_note' => $request->signin_late_note ?? null,
                'punchin_type' => 'Custom',
                'ipaddress' => $request->ip(),
                'status' => 'custom',
                'custom_status' => '1'
            ]
        );

        // Save or update CustomAttendance
        /* CustomAttendance::updateOrCreate(
            [
                'emp_id' => $employeeId,
                'signin_date' => $signinDate,
            ],
            [
                'username' => $user->username,
                'picktime' => $signinTime,
                'reason' => $request->signin_late_note ?? null,
                'status' => 0,
                'approved_by' => null
            ]
        ); */

        return response()->json([
            'success' => true,
            'message' => 'Attendance saved successfully.',
            'data' => [
                'signin_time' => date('h:i A', strtotime($attendance->signin_time))
            ]
        ]);
    }

    public function storeFullDayEntry(Request $request){
        $validated = $request->validate([
            'emp_id'           => 'required|exists:employees,user_id', // Ensure employee exists in the system
            'signin_date'      => 'required|date_format:d-m-Y',
            'signout_date'     => 'required|date_format:d-m-Y',
            'signin_time'      => 'required',
            'break_time'       => 'nullable',
            'signout_time'     => 'required',
            'working_hours'    => 'required',
            'signin_late_note' => 'nullable|string|max:500',
        ]);

        $employeeId = $request->emp_id;
        $user = Employee::with('user')->where('user_id', $employeeId)->firstOrFail();

        try {
            $attendanceData = [
                'username'         => $user->user->username,
                'emp_id'           => $validated['emp_id'],
                'signin_date'      => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['signin_date'])->format('Y-m-d'),
                'signout_date'     => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['signout_date'])->format('Y-m-d'),
                'signin_time'      => $validated['signin_time'],
                'break_time'       => $validated['break_time'] ?? '00:00:00',  // Default to '00:00' if break_time is null
                'signout_time'     => $validated['signout_time'],
                'working_hours'    => $validated['working_hours'],
                'signin_late_note' => $validated['signin_late_note'] ?? null, // Null if not provided
                'signout_late_note'=> $validated['signin_late_note'] ?? null, // Same logic for signout_late_note
                'status'           => 'mark-out',
                'punchin_type'     => 'custom',
                'punchout_type'    => 'custom',
                'custom_status'    => '1',
                'ipaddress'        => $request->ip()
            ];

            // Check if an attendance record for this employee already exists for the given signin_date
            Attendance::updateOrCreate(
                [
                    'emp_id'      => $validated['emp_id'],
                    'signin_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $validated['signin_date'])->format('Y-m-d'), // Make sure to format dates as Y-m-d
                ],
                $attendanceData // If it exists, it will be updated; if not, it will be created
            );

            return response()->json(['status' => 'success', 'message' => 'Full day attendance entry saved successfully.']);
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error in storing full day entry: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    public function getIncompleteWorkingHours(){
        $data['meta_title'] = 'Incomplete Working Hours';

        $data['years'] = Attendance::selectRaw('YEAR(signin_date) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

        // Get distinct months with month number and name (optional)
        $data['months'] = Attendance::selectRaw('MONTH(signin_date) as month, MONTHNAME(signin_date) as month_name')
        ->distinct()
        ->orderBy('month')
        ->get();

        $data['pending_approvels'] = Attendance::where('is_incomplete', 1)
        ->where('incomplete_approved', 0)
        ->with('employee') // optional: if you need employee details
        ->orderBy('signin_date', 'desc')
        ->get();

        return view('attendance.incomplete_working_hours', $data);
    }

    public function getIncompleteWorkingHoursReport(Request $request){

        $year = $request->input('year');
        $month = $request->input('month');

        // Get filtered attendance records
        $data['attendances'] = Attendance::whereYear('signin_date', $year)
        ->whereMonth('signin_date', $month)
        ->where('working_hours', '<', '08:00:00')
        ->with('employee')
        ->orderBy('signin_date', 'DESC')
        ->get();

        // Return a rendered Blade partial as HTML
        $html = view('attendance.incomplete_report_table',$data)->render();

        return response()->json([
            'success' => true,
            'html'    => $html,
        ]);
    }

    public function approveIncompleteAttendance($id){

    $attendance = Attendance::findOrFail($id);

        if ($attendance->is_incomplete && !$attendance->incomplete_approved) {
            $attendance->incomplete_approved = 1;
            $attendance->incomplete_approved_by = Auth::id();
            $attendance->incomplete_approved_at = now();
            $attendance->save();

            return redirect()->back()->with('success', 'Attendance approved successfully.');
        }

        return redirect()->back()->with('error', 'Invalid or already approved record.');
    }
}
