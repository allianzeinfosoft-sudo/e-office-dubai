<?php

namespace App\Http\Controllers;

use App\Models\CustomAttendance;
use App\Models\Attendance;
use Illuminate\Http\Request;

class CustomAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            
            $custom_attendances = CustomAttendance::with('employee')->where('status', '0')->orderBy('id', 'DESC')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'data' => $custom_attendances->map(function ($custom_attendance) {
                    return [
                        'id'            => $custom_attendance->id,
                        'emp_id'        => $custom_attendance->emp_id,
                        'username'      => $custom_attendance->username,
                        'profile_image' => $custom_attendance->employee->profile_image,
                        'signin_date'   => date('d-m-Y', strtotime($custom_attendance->signin_date)),
                        'signin_time'   => $custom_attendance->picktime,
                        'reason'        => $custom_attendance->reason,
                    ];
                }),
            ]);
        }

        //
        $data['meta_title'] = 'Custom Attendance';
        return view('custom-attendance.index', $data);
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
    public function show(CustomAttendance $customAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomAttendance $customAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomAttendance $customAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomAttendance $customAttendance)
    {
        //
    }

    public function acceptCustomMarkIn(Request $request, $id) {
        $custom_attendance = CustomAttendance::findOrFail($id);
            if($custom_attendance){

                $attendance = Attendance::updateOrCreate(
                    [
                        'username'    => $custom_attendance->username,
                        'emp_id'      => $custom_attendance->emp_id,
                        'signin_date' => $custom_attendance->signin_date,
                    ],
                    [
                        'username'          => $custom_attendance->username,
                        'emp_id'            => $custom_attendance->emp_id,
                        'signin_date'       => $custom_attendance->signin_date,
                        'signin_time'       => $custom_attendance->picktime,
                        'signin_late_note'  => $custom_attendance->reason,
                        'break_time'        => '01:00:00',
                        'punchin_type'      => 'Custom',
                        'ipaddress'         => $request->ip(),
                        'status'            => 'custom',
                        'custom_status'     => '1'
                    ]
                ); 
        }
        CustomAttendance::where('id', $id)->update(['status' => '1']);
        return redirect()->back()->with('success', 'Custom Attendance Approved successfully');
    }


    public function rejectCustomMarkIn($id) {
        CustomAttendance::where('id', $id)->update(['status' => '2']);
        return redirect()->back()->with('error', 'Custom Attendance Rejected successfully');
    }
}
