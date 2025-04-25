<?php

namespace App\Http\Controllers;

use App\Models\Workshift;
use App\Models\Employee;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function list_work_shift()
    {
        return view('settings.work_shift');
    }

    public function getWorkShift()
    {
        $workshifts = Workshift::select(
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

    public function delete_work_shift()
    {

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
}
