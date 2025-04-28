<?php

namespace App\Http\Controllers;

use App\Models\workReport;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkReportController extends Controller
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
    $request->validate([
        'project_name'      => 'required|string|max:255',
        'type_of_work'      => 'required|string|max:255',
        'total_tasks'       => 'nullable|integer',
        'productivity_hour' => 'nullable|numeric',
        'time_of_work'      => 'nullable|string',
        'total_records'     => 'nullable|integer',
        'comments'          => 'nullable|string',
        'emp_id'            => 'required|integer',
        'report_date'       => 'required|date',
        'total_time'        => 'required|string', // Ensure total_time is provided
    ]);

    


    // Convert productivity_hour to integer
    $productivity_hour = is_numeric($request->productivity_hour) ? (int) $request->productivity_hour : 0;

    // Convert total_time to seconds (if stored as HH:MM:SS)
    list($hours, $minutes, $seconds) = array_pad(explode(":", $request->total_time), 3, 0);
    $totalTimeInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

    // ✅ Create WorkReport
    $workReport = WorkReport::create([
        'username'          => Auth::user()->username,
        'emp_id'            => $request->emp_id,
        'project_name'      => $request->project_name,
        'type_of_work'      => $request->type_of_work,
        'time_of_work'      => $request->time_of_work,
        'total_time'        => gmdate("H:i:s", $totalTimeInSeconds), // Store as HH:MM:SS
        'comments'          => $request->comments,
        'report_date'       => $request->report_date,
        'total_records'     => $request->total_records,
        'productivity_hour' => $productivity_hour,
    ]);

    // ✅ Fetch attendance for the given report date
    $attendance = Attendance::where('emp_id', $request->emp_id)
        ->where('signin_date', $request->report_date)
        ->first();

    if (!$attendance) {
        return response()->json([
            'success' => false,
            'message' => 'Attendance record not found for the given date.',
        ], 404);
    }

    // ✅ Convert working_hours to seconds
    list($hours, $minutes, $seconds) = array_pad(explode(":", $attendance->working_hours), 3, 0);
    $totalAttendanceTime = ($hours * 3600) + ($minutes * 60) + $seconds;

    // ✅ Sum reported work time in seconds
    $totalReportedTime = WorkReport::where('emp_id', $request->emp_id)
        ->where('report_date', $request->report_date)
        ->sum(DB::raw('TIME_TO_SEC(total_time)'));

    // ✅ Calculate balance time
    $balanceTime = max($totalAttendanceTime - $totalReportedTime, 0);
    $formattedBalanceTime = gmdate("H:i:s", $balanceTime);

    // ✅ Load related models
    $workReport->load(['user', 'project', 'projectTask']);

    return response()->json([
        'success' => true,
        'data' => $workReport,
        'balance_working_hours' => $formattedBalanceTime,
        'message' => 'Work report submitted successfully!',
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(workReport $workReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(workReport $workReport){
        //
        $workReport->load(['user', 'project', 'projectTask']);
        return response()->json([
            'success' => true,
            'data' => $workReport,
            'message' => 'Work report submitted successfully!',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, workReport $workReport){
        $request->validate([
            'project_name'      => 'required',
            'type_of_work'      => 'required|string|max:255',
            'total_tasks'       => 'nullable|integer',
            'productivity_hour' => 'nullable|numeric',
            'time_of_work'      => 'nullable',
            'total_records'     => 'nullable',
            'comments'          => 'nullable|string',
            'emp_id'            => 'required|integer',
            'report_date'       => 'required|date',
        ]);
    
        // Convert productivity hour to integer if numeric
        $productivity_hour = is_numeric($request->productivity_hour) ? (int) $request->productivity_hour : 0;
    
        // ✅ Update work report
        $workReport->update([
            'project_name'      => $request->project_name,
            'type_of_work'      => $request->type_of_work,
            'time_of_work'      => $request->time_of_work,
            'total_time'        => $request->total_time,
            'comments'          => $request->comments,
            'total_records'     => $request->total_records,
            'productivity_hour' => $productivity_hour,
        ]);
    
        // ✅ Fetch total attendance working time (in seconds)
        $attendance = Attendance::where('emp_id', $request->emp_id)
            ->where('signin_date', $request->report_date)
            ->first();
    
        $totalAttendanceTime = $attendance ? strtotime($attendance->working_hours) - strtotime('00:00:00') : 0;
    
        // ✅ Get total reported work time for the same date and employee (convert to seconds)
        $totalReportedTime = WorkReport::where('emp_id', $request->emp_id)
            ->where('report_date', $request->report_date)
            ->sum(DB::raw('total_time * 3600')); // Convert hours to seconds if stored as decimal
    
        // ✅ Calculate the balance working time
        $balanceTime = max($totalAttendanceTime - $totalReportedTime, 0);
        $formattedBalanceTime = gmdate("H:i:s", $balanceTime);
    
        $workReport->load(['user', 'project', 'projectTask']);
    
        return response()->json([
            'success' => true,
            'data' => $workReport,
            'balance_working_hours' => $formattedBalanceTime,
            'message' => 'Work report updated successfully!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        $workReport = WorkReport::find($id);
        if (!$workReport) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }
        $workReport->delete();
        return response()->json(['success' => true, 'message' => 'Work report deleted successfully!']);
    }

    public function customWorkstore(Request $request){
        // Validate the incoming request
        $request->validate([
            'emp_id'            => 'required|exists:employees,user_id', // Ensure emp_id exists in the employee table
            'report_date'       => 'required|date',
            'project_name'      => 'required|string',
            'type_of_work'      => 'required|string',
            'total_tasks'       => 'nullable|integer',
            'productivity_hour' => 'nullable|numeric',
            'time_of_work'      => 'nullable|string',
            'total_records'     => 'nullable|integer',
            'comments'          => 'nullable|string',
            'total_time'        => 'required|string', // Ensure total_time is a string (e.g., HH:MM:SS)
        ]);

        $reportDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->report_date)->format('Y-m-d');

        // Retrieve the employee
        $employee = Employee::with('user')->where('user_id', $request->emp_id)->first();

        // If employee doesn't exist, return an error
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        // Convert productivity_hour to integer or default to 0
        $productivity_hour = is_numeric($request->productivity_hour) ? (int) $request->productivity_hour : 0;

        // Convert total_time to seconds (if stored as HH:MM:SS)
        list($hours, $minutes, $seconds) = array_pad(explode(":", $request->total_time), 3, 0);
        $totalTimeInSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

        // Define unique attributes for finding the record to update (e.g., employee and report date)
        $attributes = [
            'emp_id'       => $request->emp_id,
            'report_date'  => $reportDate,  // Assuming report_date is unique for the employee
        ];

        // Define the data to be inserted or updated
        $data = [
            'username'          => $employee->user->username, // Ensure the employee has a 'username' attribute
            'emp_id'            => $request->emp_id,
            'project_name'      => $request->project_name,
            'type_of_work'      => $request->type_of_work,
            'time_of_work'      => $request->time_of_work,
            'total_time'        => gmdate("H:i:s", $totalTimeInSeconds), // Store as HH:MM:SS
            'comments'          => $request->comments,
            'total_records'     => $request->total_records,
            'report_date'       => $reportDate,
            'productivity_hour' => $productivity_hour,
        ];

        // Use updateOrCreate to either update or create the work report
        $workReport = WorkReport::updateOrCreate($attributes, $data);

        // Return a success response with the created or updated report data
        return response()->json([
            'success' => true,
            'data' => $workReport,
            'message' => 'Work report submitted successfully!',
        ]);
    }
}
