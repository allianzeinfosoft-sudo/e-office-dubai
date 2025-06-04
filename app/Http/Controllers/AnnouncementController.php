<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Announcement;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $annoncement = Announcement::orderBy('id', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Recruitments fetched successfully',
                'data' => $annoncement->map(function ($result, $index) {
                    return [
                        'row' => $index + 1,
                        'id' => $result->id,
                        'name_announcement' => $result->name_announcement ?? '',
                        'description' => $result->description ?? '',
                        'picture' => $result->picture ? $result->picture : '',
                        'display_start_date' => date('d-m-Y', strtotime($result->display_start_date)),
                        'display_end_date' => date('d-m-Y', strtotime($result->display_end_date)),
                        'createdAt' => $result->created_at->format('d-m-Y')
                    ];
                }),
            ]);
        }
        //
        $data['meta_title'] = 'Announcement';
        return view('others.announcements.index', $data);
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
        $validated = $request->validate([
            'id' => 'nullable|integer', // Optional ID for updateOrCreate
            'name_announcement' => 'required|string|max:255',
            'display_start_date' => 'required|date',
            'display_end_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Clone validated data for modification
        $validatedData = $validated;

        // Format the rrfDate
        $validatedData['display_start_date'] = Carbon::parse($validated['display_start_date'])->format('Y-m-d');
        $validatedData['display_end_date'] = Carbon::parse($validated['display_end_date'])->format('Y-m-d');

        $announcementImagePath = null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $announcementImagePath = $file->storeAs('announcement_pictures', $filename, 'public');
        }


        $announcement = Announcement::updateOrCreate(
            ['id' => $validatedData['id'] ?? null],
            [
                'name_announcement' => $validatedData['name_announcement'] ?? '',
                'display_start_date' => $validatedData['display_start_date']?? '',
                'display_end_date' => $validatedData['display_end_date']?? '',
                'description' => $validatedData['description']?? '',
                'picture' =>  $announcementImagePath ?? '',
            ]
        );

        $recipients = Employee::whereNotNull('user_id')->pluck('user_id')->toArray();

                    // $message = 'New Announcement Created';
                    // createNotification([
                    //     'type' => 'announcement',
                    //     'recipients' => $recipients,
                    //     'message' => $message,
                    // ]);

        return response()->json([
            'message' => 'Announcement saved successfully!',
            'data' => $announcement
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //

         $announcement->display_start_date = $announcement->display_start_date
        ? Carbon::parse($announcement->display_start_date)->format('d-m-Y')
        : null;

    $announcement->display_end_date = $announcement->display_end_date
        ? Carbon::parse($announcement->display_end_date)->format('d-m-Y')
        : null;

        $data['announcement'] = $announcement;
        $data['meta_title'] = 'Edit Announcement';
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->picture && Storage::disk('public')->exists($announcement->picture)) {
            Storage::disk('public')->delete($announcement->picture);
        }
        $announcement->delete();
        return response()->json(['message' => 'Announcement deleted successfully']);
    }

    public function view_announcement()
    {
        $announcements = Announcement::orderBy('display_start_date', 'desc')->get();
        $grouped = $announcements->groupBy(function ($item) {
            return Carbon::parse($item->display_start_date)->format('Y-F'); // e.g. 2025-January
        });

        return view('views.announcement', ['announcementsByMonth' => $grouped]);
    }
}
