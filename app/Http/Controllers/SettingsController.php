<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LoginLimitedTime;
use App\Models\Workshift;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class SettingsController extends Controller
{
    public function list_work_shift()
    {
        return view('settings.work_shift');
    }

    public function getWorkShift()
    {
        $workshifts = Workshift::select(
                    'id',
                    'shift_id',
                    'shift_start_time',
                    'shift_end_time',
                    'mini_break_time',
                    'max_break_time',
                )->get()
                ->map(function ($workshifts) {
                    return [
                        'id' => $workshifts->id,
                        'shift_id' => $workshifts->shift_id,
                        'shift_start_time' => $workshifts->shift_start_time,
                        'shift_end_time' => $workshifts->shift_end_time,
                        'mini_break_time' => $workshifts->mini_break_time,
                        'max_break_time' => $workshifts->max_break_time,

                    ];
                });



        $response = response()->json(['data' => $workshifts]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }



    public function store_work_shift(Request $request)
    {
        Workshift::create($request->all());
        return back()->with('success', 'Work shift created successfully!');
    }

    public function delete_work_shift($id)
    {

        $workshift = Workshift::find($id);
        $workshift->delete();
        return response()->json(['message' => 'Workshift deleted successfully']);
    }

    public function edit_work_shift($targetId): JsonResponse
    {

        $workshift = Workshift::find($targetId);
        $data['workshift'] = $workshift;
        return response()->json($data);

    }


    public function userShifts(Request $request)
    {

        if ($request->ajax()) {

            $usersShifts = Employee::with('workshift','user','login_limited_time_info')->get()
            ->map(function ($usersShifts) {
                return [
                    'user_id' => $usersShifts->user_id,
                    'picture' => $usersShifts->profile_image ? $usersShifts->profile_image : '',
                    'name' => $usersShifts->full_name ? $usersShifts->full_name : '',
                    'user_name' => $usersShifts->user->username ? $usersShifts->user->username : '',
                    'shift_start_time' => $usersShifts->workshift->shift_start_time ? $usersShifts->workshift->shift_start_time : '',
                    'shift_end_time' => $usersShifts->workshift->shift_end_time ? $usersShifts->workshift->shift_end_time : '',
                    'wildcard_entry' => $usersShifts->login_limited_time_info->limited_time ? $usersShifts->login_limited_time_info->limited_time : '',
                ];
            });

            return response()->json([
                'data' => $usersShifts
            ]);

        }

        $data['meta_title'] = 'Users Work Shifts';
        return view('settings.change_shift_time', $data);
    }

    public function store_login_limited_time(Request $request)
    {

        LoginLimitedTime::create(['limited_time'=>$request->login_limited_time]);
        return back()->with('success', 'Login Limited Time created successfully!');
    }

    public function update_user_shift(Request $request)
    {
        $updated = Employee::where('user_id', $request->user)
        ->update([
            'shift_id' => $request->shift,
            'login_limited_time' => $request->login_limited_time
        ]);

        if ($updated) {
            return back()->with('success', 'Shift time updated successfully.');
        }
    }

    public function list_user_status()
    {

    }

    public function create_user_status()
     {

     }

     public function store_user_status()
     {

     }

     public function delete_user_status()
     {

     }

     /* Custom Mark Out */
     public function customMakeOut(){
        $data['meta_title'] = 'Custom Mark Out';
        return view('settings.custom-mark-out', $data);
     }

     public function customAttendanceEntry(){
        $data['meta_title'] = 'Custom Attendance Entry';
        $data['employees'] = Employee::get();
        return view('settings.customAttendanceEntry', $data);
     }

     public function fullDayAttendanceEntry(){
        $data['meta_title'] = 'Full Day Attendance Entry';
        $data['employees'] = Employee::get();
        return view('settings.fullDayAttendanceEntry', $data);
     }
}
