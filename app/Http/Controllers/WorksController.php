<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Project;
use App\Models\workReport;
use App\Models\Employee;
use App\Models\CustomAttendance;
use App\Models\Workshift;
use App\Models\ProductivityTarget;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;


class WorksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Work status - Attendance module will be open.
    */
    public function workStatus(){

    }

    public function sudProjectStatus(){
        $data['meta_title'] = 'SDU Project Status';
        return view('works.sdu_project_status', $data);
    }

    public function temporaryStatus(){
        $data['meta_title'] = 'Temporary Status';

        $attendance = Attendance::with('employee')->where('emp_id', Auth::user()->id)
        ->where('signin_date', now()->format('Y-m-d'))
        ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'You did not mark attendance today.');
        }
        
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
        

        $attendance = Attendance::where('emp_id', Auth::user()->id) ->where('signin_date', now()->format('Y-m-d'))->first();
        
        // ✅ Ensure 'working_hours' is correctly converted to seconds
        if (!empty($attendance->working_hours) && is_string($attendance->working_hours) && strpos($attendance->working_hours, ':') !== false) {
            $timeParts = explode(":", $attendance->working_hours);
            $hours = $timeParts[0];
            $minutes = $timeParts[1];
            $seconds = $timeParts[2] ?? 0; // Default to 0 if seconds are missing
        } else {
            // Calculate working time if working_hours is null or not a string
            $hours = 0;
            $minutes = 0;
            $seconds = 0;
        
            if (!empty($attendance->signin_date) && !empty($attendance->signin_time)) {
                $signout_date = $attendance->signout_date ?? now()->toDateString(); 
                $signout_time = $attendance->signout_time ?? now()->toTimeString(); 
                $calculatedTime = CustomHelper::calculateTotalWorkingTime(
                    $attendance->signin_date,
                    $attendance->signin_time,
                    $signout_date,
                    $signout_time,
                    $attendance->break_time ?? null
                );

                // Ensure calculated time is a string before processing
                if (is_string($calculatedTime) && strpos($calculatedTime, ':') !== false) {
                    $timeParts = explode(":", $calculatedTime);
                    $hours = $timeParts[0];
                    $minutes = $timeParts[1];
                    $seconds = $timeParts[2] ?? 0;
                }
            }
        }
        
            $totalAttendanceTime = $calculatedTime['total_working_time'] ?? '00:00:00';
            $attendanceParts = array_map('intval', explode(':', $totalAttendanceTime));
            $attendanceParts = array_pad($attendanceParts, 3, 0);
            $totalAttendanceSeconds = ($attendanceParts[0] * 3600) + ($attendanceParts[1] * 60) + $attendanceParts[2];
         
            // ✅ Sum reported time in seconds (using TIME_TO_SEC)
            $totalReportedSeconds = WorkReport::where('emp_id', Auth::user()->id)
            ->where('report_date', now()->format('Y-m-d'))
            ->whereRaw("TIME_TO_SEC(total_time) IS NOT NULL")
            ->sum(DB::raw('TIME_TO_SEC(total_time)'));

            // $totalReportedTime = gmdate("H:i:s", $totalSeconds);    

            $balanceSeconds = max($totalAttendanceSeconds - $totalReportedSeconds, 0);
            $formattedBalanceTime = gmdate("H:i:s", $balanceSeconds);
            
            $missingReport->balance_time = $formattedBalanceTime;
           
            $data['missingReport'] = $missingReport;
            $data['attendance'] = $attendance;
            $data['calculatedTime'] = $calculatedTime;

            $data['repots_posted'] = WorkReport::with(['project', 'projectTask', 'tasks'])
                ->where('username', Auth::user()->username)
                ->where('report_date', now()->format('Y-m-d'))
                ->get();

            $data['projects'] = Project::all();
            //return view('attendance.work_report', $data);
            $data['user_shift'] = Workshift::where('id', $attendance->employee->shift_id)->first(); 

        return view('works.temporaray_status', $data);
    
    }

    public function entryOpen(){
        $data['meta_title'] = 'Entry Open';
        $data['attendance']     = Attendance::where(['username' => Auth::user()->username, 'signin_date' => now()->format('Y-m-d')])->first();
        $data['employee'] = Employee::where('user_id', Auth::id())->first();
        return view('works.entry_open', $data);
    }
}
