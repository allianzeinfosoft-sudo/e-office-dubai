<?php

namespace App\Http\Controllers;

use App\Models\EventCalendar;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Appreciation;
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

        /* Appriciations */
        $appreciations = Appreciation::with('employee','project')->get();
        $appr_events = [];

        foreach ($appreciations as $index => $result) {
            $appr_events[] = [
                'id' => 'appr_' . ($index + 1),
                'url' => '', // you can later add route('appreciation.show', $result->id)
                'title' => $result->employee?->full_name . ' appreciated',
                'start' => $result->display_date,
                'end' => $result->display_date,
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'appreciation',
                    'event_id' => $result->id,
                    'details' => $result->appreciation_details,
                    'picture' => $result->picture,
                ],
            ];
        }

        $data['appr_events'] = $appr_events;

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
        $data = $request->validate([
        'eventTitle' => 'required|string|max:255',
        'eventLabel' => 'nullable|string|max:100',
        'eventStartDate' => 'nullable|date',
        'eventEndDate' => 'nullable|date',
        'eventURL' => 'nullable|url',
        'eventGuests' => 'nullable|array',
        'eventLocation' => 'nullable|string|max:255',
        'eventDescription' => 'nullable|string',
        'allDay' => 'nullable|boolean',
    ]);

    Event::create([
        'title' => $data['eventTitle'],
        'label' => $data['eventLabel'] ?? null,
        'start_date' => $data['eventStartDate'],
        'end_date' => $data['eventEndDate'],
        'url' => $data['eventURL'] ?? null,
        'guests' => $data['eventGuests'] ?? [],
        'location' => $data['eventLocation'] ?? null,
        'description' => $data['eventDescription'] ?? null,
        'all_day' => $request->has('allDay') ? true : false,
    ]);

    return response()->json(['success' => true, 'message' => 'Event created']);
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
