<?php

namespace App\Http\Controllers;

use App\Models\ConferenceHall;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConferenceHallController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $bookings = ConferenceHall:: with('dept','bookedBy')->orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Bookings fetched successfully',
                'data' => $bookings->map(function ($result, $index) {
                    $participants = '';

                    foreach($result->participants_employees as $participant){
                        $participants .= '<span class="badge bg-label-danger m-1">'. $participant . '</span> ';
                    }

                    return [
                        'row' => $index + 1,
                        'id' => $result->id,
                        'booked_by' => $result->bookedBy?->full_name,
                        'department' => $result->dept?->department,
                        'booking_date' => date('d-m-Y', strtotime($result->booking_date)),
                        'start_time' => date('H:i', strtotime($result->start_time)),
                        'end_time' => date('H:i', strtotime($result->end_time)),
                        'participants' => $participants,
                        'purpose' => $result->purpose ?? '',
                        'status' => ($result->status == 2) ? 'Rejected' : (($result->status == 1) ? '<span class="badge bg-label-success m-1"> Confirmed </span>' : '<span class="badge bg-label-warning m-1"> Pending </span>'),
                        'createdAt' => $result->created_at->format('d-m-Y')
                    ];
                }),
            ]);
        }
        //
        $data['meta_title'] = 'Booking';
        $data['bookings']   = ConferenceHall:: with('dept','bookedBy')->orderBy('id', 'desc')->get();
        return view('conferance-hall.index', $data);
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
        $validated = $request->validate([
            'department_id' => 'required',
            'booked_by'     => 'required',
            'booking_date'  => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'participants'  => 'required',
            'purpose'       => 'required',
        ]);

        $bookingDate = Carbon::parse($validated['booking_date'])->format('Y-m-d');
        $startTime = Carbon::parse($validated['start_time'])->format('H:i:s');
        $endTime = Carbon::parse($validated['end_time'])->format('H:i:s');

        // Conflict check for overlapping bookings (excluding self if editing)
        $conflict = ConferenceHall::where('booking_date', $bookingDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
                });
            })
            ->when($request->id, fn($query) => $query->where('id', '!=', $request->id)) // Exclude self when updating
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'A booking already exists for the selected date and time.',
            ], 409); // HTTP Conflict
        }

        // Save booking
        ConferenceHall::updateOrCreate(
            ['id' => $request->id],
            [
                'department_id' => $validated['department_id'],
                'booked_by'     => $validated['booked_by'],
                'booking_date'  => $bookingDate,
                'start_time'    => $startTime,
                'end_time'      => $endTime,
                'participants'  => is_array($validated['participants']) 
                                    ? implode(',', $validated['participants']) 
                                    : $validated['participants'],
                'purpose'       => $validated['purpose'],
                'status'        => 1
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->id 
                ? 'Conference Hall Updated Successfully!' 
                : 'Conference Hall Booked Successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConferenceHall $conferenceHall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConferenceHall $conferenceHall){
    // Debug output
    $data['conference_hall'] = $conferenceHall;
    $data['meta_title'] = 'Edit Conference Hall';
    
    return response()->json($data);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConferenceHall $conferenceHall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConferenceHall $conferenceHall)
    {
        //
        $conferenceHall->delete();
        return response()->json(['message' => 'Conference Hall deleted successfully']);
    }
}
