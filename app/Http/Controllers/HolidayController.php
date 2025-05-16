<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{

    public function index()
    {
        return view('holidays.index');
    }

    public function getHolidayList()
    {
        $holidays = Holiday::select(
            'id',
            'name',
            'date',
            'holiday_group',
        )->get()
        ->map(function ($holidays) {
            return [
                'id' => $holidays->id,
                'name' => $holidays->name ? $holidays->name : 'N/A',
                'date' => $holidays->date ? date('d-m-Y', strtotime($holidays->date)) : 'N/A',
                'group' => $holidays->holiday_group ? $holidays->holiday_group : 'N/A',

            ];
        });



        $response = response()->json(['data' => $holidays]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        Holiday::create($request->all());
        return back()->with('success', 'Holiday created successfully!');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        $holiday = Holiday::find($id);

        if (!$holiday) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $holiday->delete();
        return response()->json(['success' => true, 'message' => 'Holiday deleted successfully.']);
    }

    public function show_holiday(Request $request)
    {

         if ($request->ajax()) {

            $holiday_group = Auth::user()->employee->holidays;
            $formattedHolidays = $holiday_group->map(function ($holiday) {
                return [
                    'id' => $holiday->id,
                    'holiday_name' => $holiday->name ?? 'N/A',
                    'date' => $holiday->date ? date('d-m-Y', strtotime($holiday->date)) : 'N/A',
                ];
            });

            return response()->json([
                'data' => $formattedHolidays
            ]);

        }

        //
        $data['meta_title'] = 'Holidays';
        return view('views.holidays', $data);


    }


}
