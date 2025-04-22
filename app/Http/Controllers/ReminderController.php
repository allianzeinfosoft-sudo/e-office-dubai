<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReminderController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reminders = Reminder::with('employee')->get()
                ->map(function ($reminder) {
                    return [
                        'id' => $reminder->id,
                        'user_name' => $reminder->employee ? $reminder->employee->full_name : '',
                        'event_name' => $reminder->event_name ?? '',
                        'event_description' => $reminder->event_description ?? '',
                        'start_date' => $reminder->start_date ?? '',
                        'display_time' => $reminder->display_time ?? '',
                        'yearly_in_month' => $reminder->yearly_in_month ?? '',
                        'repeat_on' => $reminder->repeat_mode ?? '',
                        'every' => $reminder->day ?? '', // only if this field exists
                    ];
                });

            return response()->json(['data' => $reminders]);
        }
        $data['meta_title'] = 'Reminders';
        return view('reminder.index', $data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $request->validate([
            'user'          => 'required',
            'event_name'    => 'required|string|max:255',
            'display_time'  => 'required',
            'start_date'    => 'required|date',
            'repeat_status' => 'nullable',

            'end_date' => [
                Rule::requiredIf(fn () => $request->repeat_check == 'on' || $request->repeat_check == 1),
                'nullable', 'date', 'after_or_equal:start_date',
            ],

            'repeat_mode' => [
                Rule::requiredIf(fn () => $request->repeat_check == 'on' || $request->repeat_check == 1),
                'nullable', 'in:daily,weekly,monthly,yearly',
            ],
        ]);


        // Find existing or create new reminder
        $reminder = Reminder::find($request->id) ?? new Reminder();

        $reminder->user_id        = $request->user;
        $reminder->event_name     = $request->event_name;
        $reminder->display_time   = $request->display_time;
        $reminder->start_date     = $request->start_date;
        $reminder->repeat_status  = $request->repeat_status;
        $reminder->event_description = $request->event_description;

        // Handle repeating logic
        if ($request->repeat_status == 'on') {
            $reminder->end_date    = $request->end_date;
            $reminder->repeat_mode = $request->repeat_mode;

            if ($request->repeat_mode == 'weekly') {
                $reminder->day = is_array($request->weekdays)
                    ? json_encode(array_map('trim', $request->weekdays))
                    : null;
            }

            if ($request->repeat_mode == 'monthly') {
                $reminder->monthly_type = $request->monthly_type;

                if ($request->monthly_type == 1) {
                    $reminder->day = $request->onday1;
                }

                if ($request->monthly_type == 2) {
                    $reminder->day = $request->onday3;
                    $reminder->monthly_on_week_position = $request->onday2;
                }
            }

            if ($request->repeat_mode == 'yearly') {
                $reminder->day = $request->onday1;
                $reminder->yearly_in_month = $request->month;
            }
        } else {
            // Optional: Clear repeat-related fields when not repeating
            $reminder->end_date = null;
            $reminder->repeat_mode = null;
            $reminder->day = null;
            $reminder->monthly_type = null;
            $reminder->monthly_on_week_position = null;
            $reminder->yearly_in_month = null;
        }

        $reminder->save();

        return redirect()->back()->with('success', 'Reminder saved successfully!');

    }


    public function show(Reminder $reminder)
    {
        //
    }


    public function edit($id)
    {
        $reminder = Reminder::find($id);
        $data['reminder'] = $reminder;
        return response()->json($data);
    }


    public function update(Request $request, Reminder $reminder)
    {
        //
    }

    public function destroy($id)
    {

        $reminder = Reminder::find($id);
        $reminder->delete();
        return response()->json(['message' => 'Reminder deleted successfully']);

    }
}
