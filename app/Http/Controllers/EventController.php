<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $events = Event::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Events fetched successfully',
                'data' => $events->map(function ($event, $index) {
                    return [
                        'row' => $index + 1,
                        'id' => $event->id,
                        'eventTitle' => $event->eventTitle ?? '',
                        'description' => $event->description ?? '',
                        'eventDate' => date('d-m-Y', strtotime($event->eventDate)),
                        'createdAt' => $event->created_at->format('d-m-Y'),
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Events';
        return view('others.events.index', $data);
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
        $validated = $request->validate([
            'id' => 'nullable|integer',
            'eventTitle' => 'required|string|max:255',
            'eventDate' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validatedData = $validated;
        $validatedData['eventDate'] = Carbon::parse($validated['eventDate'])->format('Y-m-d');

        $event = Event::updateOrCreate(
            ['id' => $validatedData['id'] ?? null],
            [
                'eventTitle' => $validatedData['eventTitle'] ?? '',
                'eventDate' => $validatedData['eventDate'] ?? '',
                'description' => $validatedData['description'] ?? '',
            ]
        );

        return response()->json([
            'message' => 'Event saved successfully!',
            'data' => $event
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $data['event'] = $event;
        $data['meta_title'] = 'Edit Event';
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }
}
