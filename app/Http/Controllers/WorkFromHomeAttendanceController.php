<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkFromHomeAttendance;
use App\Models\WorkFromHomeReport;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\workReport;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Helpers\CustomHelper;

class WorkFromHomeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'from_date'         => 'required|date',
            'to_date'           => 'required|date|after_or_equal:from_date',
            'request_type'      => 'required|in:wfh,wos',
            'attendance_option' => 'required|in:personal,company',
            'reason'            => 'required|string',
        ]);

        $wfhRequest = \App\Models\WorkFromHomeRequest::create([
            'emp_id'            => Auth::id(), // assuming user_id = emp_id in this context, or Auth::user()->employee->user_id
            'from_date'         => $request->from_date,
            'to_date'           => $request->to_date,
            'request_type'      => $request->request_type,
            'attendance_option' => $request->attendance_option,
            'reason'            => $request->reason,
            'status'            => 0, // Pending
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted successfully.',
                'data' => $wfhRequest
            ]);
        }

        return redirect()->back()->with('success', 'Your request has been submitted successfully and is pending approval.');
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
            'signout_date'  => 'required|date',
            'signin_time'   => 'required',
            'brake_time'    => 'required',
            'signout_time'  => 'required',
            'work_type'     => 'required',
        ]);
        $employee = Employee::with('user')->where('user_id', $validatedData['employee_id'])->firstOrFail();
        $signinDate   = date('Y-m-d', strtotime($validatedData['signin_date']));
        $signoutDate  = date('Y-m-d', strtotime($validatedData['signout_date']));
        $signinTime   = CustomHelper::formatTimeToSeconds($validatedData['signin_time']);
        $signoutTime  = CustomHelper::formatTimeToSeconds($validatedData['signout_time']);
        $breakTime    = CustomHelper::formatTimeToSeconds($validatedData['brake_time']);
        $workingHrs   = CustomHelper::calculateTotalWorkingTime($signinDate, $signinTime, $signoutDate, $signoutTime, $breakTime);
        $totalWorkingTime = $workingHrs['total_working_time'] ?? '00:00:00';
        $is_incomplete = 0; //(strtotime($totalWorkingTime) < strtotime('08:00:00')) ? 1 : 0;
        if ($is_incomplete) {
            CustomHelper::addToBlockList([
                'user_id'    => $validatedData['employee_id'],
                'block_date' => $signinDate,
                'username'   => $employee->user->username,
                'full_name'  => $employee->full_name,
            ]);
        }
        // Create or update attendance and get ID
        $attendance = WorkFromHomeAttendance::updateOrCreate(
            [
                'emp_id'      => $validatedData['employee_id'],
                'signin_date' => $signinDate,
            ],
            [
                'username'      => $employee->user->username,
                'emp_id'        => $validatedData['employee_id'],
                'signin_date'   => $signinDate,
                'signin_time'   => $signinTime,
                'signout_date'  => $signoutDate,
                'signout_time'  => $signoutTime,
                'working_hours' => $totalWorkingTime,
                'break_time'    => $breakTime,
                'status'        => $validatedData['work_type'],
                'ipaddress'     => $request->ip(),
                'is_incomplete' => $is_incomplete,
                'created_by'    => Auth::id(),
            ]
        );
        // Always delete previous reports for this date and employee before inserting new
        WorkFromHomeReport::where([
            'emp_id'      => $validatedData['employee_id'],
            'report_date' => $signinDate,
        ])->delete();

        workReport::where([
            'emp_id'      => $validatedData['employee_id'],
            'report_date' => $signinDate,
        ])->delete();

        // Insert fresh report lines
        foreach ($request->input('reports', []) as $report) {
            if (!empty($report['project_id']) && !empty($report['type_of_work'])) {
                $reportData = [
                    'username'           => $employee->user->username,
                    'emp_id'             => $validatedData['employee_id'],
                    'project_name'       => $report['project_id'],
                    'type_of_work'       => $report['type_of_work'],
                    'time_of_work'       => CustomHelper::formatTimeToSeconds($workingHrs['total_working_time']),
                    'total_time'         => CustomHelper::formatTimeToSeconds($report['total_time']) ?? null,
                    'comments'           => $report['comments'] ?? null,
                    'report_date'        => $signinDate,
                    'total_records'      => $report['total_records'] ?? null,
                    'productivity_hour'  => $report['productivity_hour'] ?? null,
                ];

                WorkFromHomeReport::create(array_merge($reportData, [
                    'wfh_attendance_id'  => $attendance->id,
                ]));

                workReport::create(array_merge($reportData, [
                    'emergency' => 0,
                    'break_time' => $breakTime
                ]));
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance and work reports saved successfully!',
                'balance_time' => '00:00' // Optional: calculate actual balance
            ]);
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

    public function get_wfs_wfh_approval_list(){
        $data['meta_title'] = 'WFS /WFS Approval List';
        $data['employees'] = Employee::where('status', 2)->get();
        $data['wfs_wfh_pending'] = WorkFromHomeAttendance::with('employee')->where('approvel_status', 0)->get();
        return view('wfs-wfh-attendance.approval_list', $data);
    }

    public function approval_wfs_wfh($id, Request $request){
        $wfh = WorkFromHomeAttendance::find($id);
        $WfhReport = WorkFromHomeReport::where('wfh_attendance_id', $id)->get();
        if($wfh){
                $attendanceData = [
                    'username'         => $wfh->username,
                    'emp_id'           => $wfh->emp_id,
                    'signin_date'      => $wfh->signin_date,
                    'signout_date'     => $wfh->signout_date,
                    'signin_time'      => CustomHelper::formatTimeToSeconds($wfh->signin_time),
                    'break_time'       => CustomHelper::formatTimeToSeconds($wfh->break_time),
                    'signout_time'     => CustomHelper::formatTimeToSeconds($wfh->signout_time),
                    'working_hours'    => CustomHelper::formatTimeToSeconds($wfh->working_hours),
                    'signin_late_note' => $wfh->status == 'wfh'? 'wfh' : 'wfs',
                    'signout_late_note'=> $wfh->status == 'wfh'? 'wfh' : 'wfs',
                    'status'           => 'mark-out',
                    'punchin_type'     => $wfh->status,
                    'punchout_type'    => $wfh->status,
                    'custom_status'    => '0',
                    'ipaddress'        => $request->ip(),
                ];
                // Insert or update attendance record
                Attendance::updateOrCreate(
                    [
                        'emp_id'      => $wfh->emp_id,
                        'signin_date' => $wfh->signin_date,
                    ],
                    $attendanceData
                );           
            $wfh->approvel_status = 1;
            $wfh->approved_by = Auth::user()->id;
            $wfh->save();
        }

        if ($WfhReport->isNotEmpty()) {
            $grouped = $WfhReport->groupBy(function ($item) {
                return $item->emp_id . '|' . $item->report_date;
            });

            foreach ($grouped as $group) {
                $first = $group->first();
                workReport::where('emp_id', $first->emp_id)
                    ->where('report_date', $first->report_date)
                    ->delete();
                $insertData = $group->map(function ($value) {
                    return [
                        'username'          => $value->username,
                        'emp_id'            => $value->emp_id,
                        'project_name'      => $value->project_name,
                        'type_of_work'      => $value->type_of_work,
                        'time_of_work'      => $value->time_of_work,
                        'total_time'        => CustomHelper::formatTimeToSeconds($value->total_time),
                        'comments'          => $value->comments,
                        'report_date'       => $value->report_date,
                        'total_records'     => $value->total_records,
                        'productivity_hour' => $value->productivity_hour,
                        'emergency'         => 0,
                    ];
                })->toArray();
                workReport::insert($insertData);
            }
        }

        return redirect()->back()->with('success', 'Wfh / wfs approved successfully!');
    }

    public function reject_wfs_wfh($id){
        $wfh = WorkFromHomeAttendance::find($id);
        if($wfh){
            $wfh->approvel_status = 2;
            $wfh->approved_by = Auth::user()->id;
            $wfh->save();
        }
        return redirect()->back()->with('success', 'Wfh / wfs rejected successfully!');
    }
    
    public function wfs_wfh_attendance_report(Request $request){
        $from_date = $request->input('from_date')?? now()->format('Y-m-d');
        $to_date = $request->input('to_date')?? now()->format('Y-m-d');
        $employee_id = $request->input('employee_id')?? null;
        $data['meta_title'] = 'WFS / WFH Attendance Report';
        $data['wfs_wfh_reports'] = WorkFromHomeAttendance::with('employee')->whereBetween('signin_date', [$from_date, $to_date])
            ->when($employee_id, function ($query) use ($employee_id) {
                return $query->where('emp_id', $employee_id);
            })
        ->get();
        $data['html'] = view('wfs-wfh-attendance.attendance_report', $data);
        return response()->json(['status' => 'success', 'html' => $data['html']->render()]);
    }

    public function getRequestApprovalList(Request $request)
    {
        $data['meta_title'] = 'WFS / WFH Request Report';
        $data['employees'] = Employee::where('status', 2)->get();
        
        $query = \App\Models\WorkFromHomeRequest::with('employee');
        
        if ($request->filled('from_date')) {
            $query->where('from_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('to_date', '<=', $request->to_date);
        }
        if ($request->filled('emp_id')) {
            $query->where('emp_id', $request->emp_id);
        }
        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        // If no filters are applied, default to pending requests
        if (!$request->anyFilled(['from_date', 'to_date', 'emp_id', 'request_type'])) {
            $query->where('status', 0);
            $data['meta_title'] = 'WFS / WFH Request Approval List';
        }

        $data['requests'] = $query->orderBy('created_at', 'desc')->get();
        return view('wfs-wfh-attendance.request_approval_list', $data);
    }

    public function approveRequest($id)
    {
        $request = \App\Models\WorkFromHomeRequest::find($id);
        if ($request) {
            $request->status = 1; // Approved
            $request->approved_by = Auth::id();
            $request->save();
            return redirect()->back()->with('success', 'Request approved successfully.');
        }
        return redirect()->back()->with('error', 'Request not found.');
    }

    public function rejectRequest($id)
    {
        $request = \App\Models\WorkFromHomeRequest::find($id);
        if ($request) {
            $request->status = 2; // Rejected
            $request->approved_by = Auth::id();
            $request->save();
            return redirect()->back()->with('success', 'Request rejected successfully.');
        }
        return redirect()->back()->with('error', 'Request not found.');
    }

    public function delete_wfs_wfh_attendance($id){
        $wfh = WorkFromHomeAttendance::find($id);
        if($wfh){
            WorkFromHomeReport::where('wfh_attendance_id', $id)->delete();
            $wfh->delete();
        }
        return redirect()->back()->with('success', 'Wfh / wfs attendance deleted successfully!');
    }   
}
