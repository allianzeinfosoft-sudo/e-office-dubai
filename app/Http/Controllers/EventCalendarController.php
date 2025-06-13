<?php

namespace App\Http\Controllers;

use App\Models\EventCalendar;
use App\Models\Employee;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'Event Calendar';
        $slId = 0;
        /* Birthdays */
        $employees = Employee::whereIn('status', [1,2,5])->get();
        $events = [];
        foreach ($employees as $employee) {
            $slId++;
            $dob = Carbon::parse($employee->dob);
            $birthdayThisYear = Carbon::create(now()->year, $dob->month, $dob->day);

            $events[] = [
                'id' => $slId,
                'url' => '',
                'title' => $employee->full_name . "'s Birthday 🎂",
                'start' => $birthdayThisYear->toDateString(),
                'end' => $birthdayThisYear->toDateString(),
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'birthdays',
                    'employee_id' => $employee->id,
                ],
            ];

        }

        $data['events_birthdays'] = $events;

        /* events */
        $officeEvent = Event:: all();
        $office_events = [];
        foreach ($officeEvent as $result) {
            $office_events[] = [
                'id' => $slId,
                'url' => '',
                'title' => $result->eventTitle,
                'start' => $result->eventDate,
                'end' => $result->eventDate,
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'events',
                    'event_id' => $result->id,
                ],
            ];
        }

        $data['office_events'] = $office_events;

        

        return view('tools.event-calendar.index', $data);

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
    public function show(EventCalendar $eventCalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventCalendar $eventCalendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventCalendar $eventCalendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventCalendar $eventCalendar)
    {
        //
    }
}
