<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkFromHomeAttendance;
use App\Models\WorkFromHomeReport;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Helpers\CustomHelper;

class WorkFromHomeAttendanceController extends Controller
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
   public function store(Request $request){

        $validatedData = $request->validate([
            'employee_id'   => 'required|exists:employees,user_id',
            'signin_date'   => 'required|date',
            'signin_time'   => 'required',
            'brake_time'    => 'required',
            'signout_time'  => 'required',
            'work_type'  => 'required',
        ]);

        // Fetch employee with user relationship
        $employee = Employee::with('user')->where('user_id', $validatedData['employee_id'])->firstOrFail();

        $signinDate = date('Y-m-d', strtotime($validatedData['signin_date']));
        $signinTime = CustomHelper::formatTimeToSeconds($validatedData['signin_time']);
        $signoutTime = CustomHelper::formatTimeToSeconds($validatedData['signout_time']);
        $breakTime = CustomHelper::formatTimeToSeconds($validatedData['brake_time']);
        
        $workingHrs = CustomHelper::calculateTotalWorkingTime($signinDate, $signinTime, $signinDate, $signoutTime, $breakTime);

        $totalWorkingTime = $workingHrs['total_working_time'] ?? '00:00:00';

        // Check if working hours are less than 8 hours
        $is_incomplete = (strtotime($totalWorkingTime) < strtotime('08:00:00')) ? 1 : 0;

        if ($is_incomplete) {
            CustomHelper::addToBlockList([
                'user_id'    => $validatedData['employee_id'],
                'block_date' => $signinDate,
                'username'   => $employee->user->username,
                'full_name'  => $employee->full_name
            ]);
        }

        // Save or update attendance
        $attendance = WorkFromHomeAttendance::updateOrCreate(
            ['emp_id' => $validatedData['employee_id'], 'signin_date' => $signinDate],
            [
                'username'      => $employee->user->username,
                'emp_id'        => $validatedData['employee_id'],
                'signin_date'   => $signinDate,
                'signin_time'   => $signinTime,
                'signout_date'  => $signinDate,
                'signout_time'  => $signoutTime,
                'working_hours' => $totalWorkingTime,
                'break_time'    => CustomHelper::formatTimeToSeconds($breakTime),
                'status'        => $validatedData['work_type'],
                'ipaddress'     => $request->ip(),
                'is_incomplete' => $is_incomplete,
                'created_by'    => Auth::id(),
            ]
        );

        // Delete previous reports for this date if updating
        if ($request->id) {
            WorkFromHomeReport::where([
                'emp_id'    => $validatedData['employee_id'],
                'report_date' => $signinDate,
            ])->delete();
        }
        // Store new reports
        foreach ($request->input('reports', []) as $report) {
            if (!empty($report['project_id']) && !empty($report['type_of_work'])) {

                WorkFromHomeReport::create([
                    'username'           => $employee->user->username,
                    'emp_id'             => $validatedData['employee_id'],
                    'project_name'       => $report['project_id'],
                    'type_of_work'       => $report['type_of_work'],
                    'time_of_work'       => CustomHelper::formatTimeToSeconds($workingHrs['total_working_time']),
                    'total_time'         => $report['total_time'] ?? null,
                    'comments'           => $report['comments'] ?? null,
                    'report_date'        => date('Y-m-d', strtotime($validatedData['signin_date'])),
                    'total_records'      => $report['total_records'] ?? null,
                    'productivity_hour'  => $report['productivity_hour'] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Work From Home attendance saved successfully!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(WorkFromHomeAttendance $workFromHomeAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkFromHomeAttendance $workFromHomeAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkFromHomeAttendance $workFromHomeAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkFromHomeAttendance $workFromHomeAttendance)
    {
        //
    }
    
}
