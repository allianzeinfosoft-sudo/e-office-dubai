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

        // Get today's attendance
        $attendance = Attendance::with('employee')
            ->where('emp_id', Auth::user()->id)
            ->where('signin_date', now()->format('Y-m-d'))
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'You did not mark attendance today.');
        }

        // Determine total working time
        $calculatedTime = ['total_working_time' => '00:00:00'];
        $hours = $minutes = $seconds = 0;

        if (!empty($attendance->working_hours) && strpos($attendance->working_hours, ':') !== false) {
            $timeParts = explode(":", $attendance->working_hours);
            $hours = (int)$timeParts[0];
            $minutes = (int)$timeParts[1];
            $seconds = isset($timeParts[2]) ? (int)$timeParts[2] : 0;

            $calculatedTime['total_working_time'] = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            $signout_date = $attendance->signout_date ?? now()->toDateString();
            $signout_time = $attendance->signout_time ?? now()->toTimeString();

            $calculatedTime = CustomHelper::calculateTotalWorkingTime(
                $attendance->signin_date,
                $attendance->signin_time,
                $signout_date,
                $signout_time,
                $attendance->break_time ?? null
            );

            if (
                isset($calculatedTime['total_working_time']) &&
                strpos($calculatedTime['total_working_time'], ':') !== false
            ) {
                $timeParts = explode(":", $calculatedTime['total_working_time']);
                $hours = (int)$timeParts[0];
                $minutes = (int)$timeParts[1];
                $seconds = isset($timeParts[2]) ? (int)$timeParts[2] : 0;
            }
        }

        $totalAttendanceSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

        // Total reported time in seconds
        $totalReportedSeconds = WorkReport::where('emp_id', Auth::user()->id)
            ->where('report_date', now()->format('Y-m-d'))
            ->whereRaw("TIME_TO_SEC(total_time) IS NOT NULL")
            ->sum(DB::raw('TIME_TO_SEC(total_time)'));

        // Calculate balance time
        $balanceSeconds = max($totalAttendanceSeconds - $totalReportedSeconds, 0);
        $formattedBalanceTime = gmdate("H:i:s", $balanceSeconds);

        // Get missing report (only if needed)
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
            ->havingRaw('total_reported_time < total_attendance_time')
            ->where('attendances.status', 'mark-out')
            ->where('attendances.emp_id', Auth::user()->id)
            ->first();

        if ($missingReport) {
            $missingReport->balance_time = $formattedBalanceTime;
        }

        $data['attendance'] = $attendance;
        $data['calculatedTime'] = $calculatedTime;
        $data['missingReport'] = $missingReport;
        $data['repots_posted'] = WorkReport::with(['project', 'projectTask', 'tasks'])
            ->where('username', Auth::user()->username)
            ->where('report_date', now()->format('Y-m-d'))
            ->get();
        $data['projects'] = Project::all();
        $data['user_shift'] = Workshift::find($attendance->employee->shift_id);

        return view('works.temporaray_status', $data);
    }

    public function entryOpen(){
        $data['meta_title'] = 'Entry Open';
        $data['attendance']     = Attendance::where(['username' => Auth::user()->username, 'signin_date' => now()->format('Y-m-d')])->first();
        $data['employee'] = Employee::where('user_id', Auth::id())->first();
        return view('works.entry_open', $data);
    }
}
