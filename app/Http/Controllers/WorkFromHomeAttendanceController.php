<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkFromHomeAttendance;
use App\Models\WorkFromHomeReport;

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
        $validateedData = $request->validate([
            'employee_id' => 'required',
            'signin_date' => 'required',
            'signin_time' => 'required',
            'brake_time' => 'required',
            'signout_time' => 'required',
        ]);

        $employee = Employee::with('user')->where('user_id', $request->employee_id)->first();

        $wrokingHrs = CustomHelper::calculateTotalWorkingTime(
            date('Y-m-d', strtotime($validateedData['signin_date'])),
            CustomHelper::formatTimeToSeconds($validateedData['signin_time']),
            CustomHelper::formatTimeToSeconds($validateedData['signout_time']),
            $validateedData['brake_time']
        );
        
        $totalWorkingTime = $wrokingHrs['total_working_time'] ?? '00:00:00';


        if (strtotime($totalWorkingTime) < strtotime('08:00:00')) {
            $is_incomplete = 1;

            CustomHelper::addToBlockList([
                'user_id'    => $validateedData['employee_id'],
                'block_date' => date('Y-m-d'),
                'username' => $employee->user->username,
                'full_name' => $employee->full_name
            ]);

        }else{
            $is_incomplete = 0;
        }

        $attendance = WorkFromHomeAttendance::updateOrCreate(
            ['id' => $request->id],
            [
                'username'      => $employee->user->username,
                'emp_id'        => $validateedData['employee_id'],
                'signin_date'   => date('Y-m-d', strtotime($validateedData['signin_date'])), // if same $validateedData['signin_date'],
                'signin_time'   => CustomHelper::formatTimeToSeconds($validateedData['signin_time']), // if same
                'signout_date'  => date('Y-m-d', strtotime($validateedData['signin_date'])), // if same $validateedData['signin_date'],
                'signout_time'  => CustomHelper::formatTimeToSeconds($validateedData['signout_time']),
                'working_hours' => CustomHelper::formatTimeToSeconds($wrokingHrs['total_working_time']),
                'break_time'    => CustomHelper::formatTimeToSeconds($validateedData['brake_time']),
                'status'        => 'WFH',
                'is_incomplete' => $is_incomplete,
                'created_by'    => Auth::user()->id,
            ]
        );

        // Delete old reports if updating
        if ($request->id) {
            WorkFromHomeReport::where(['username'=>$employee->user->username, 'report_date' => date('Y-m-d', strtotime($validateedData['signin_date']))])->delete();
        }

        foreach ($request->input('reports', []) as $report) {
            WorkFromHomeReport::create([
                'username' => $employee->user->username,
                'emp_id' => $validateedData['employee_id'],
                'project_name' => $report['project_id'],
                'type_of_work' => $report['type_of_work'],
                'time_of_work' => CustomHelper::formatTimeToSeconds($wrokingHrs['total_working_time']),
                'total_time' => $report['total_time'],
                'comments' => $report['comments'],
                'report_date' => date('Y-m-d', strtotime($validateedData['signin_date'])),
                'total_records' => $report['total_records'],
                'productivity_hour' => $report['productivity_hour']
            ]);
        }

        return redirect()->back()->with('success', 'Event saved successfully!');
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
