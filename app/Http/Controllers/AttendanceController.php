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
    public function index(){
        $data['meta_title'] = 'Attendance';
        $data['attendance'] = Attendance::where(['username' => Auth::user()->username, 'signin_date' => now()->format('Y-m-d')])->first();
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
