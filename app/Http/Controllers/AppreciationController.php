<?php

namespace App\Http\Controllers;

use App\Models\Appreciation;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppreciationController extends Controller
{

    public function index(Request $request)
    {
        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $appreciations = Appreciation::all()->map(function ($appreciation) {
                $appreciantNames = [];

                if (!empty($appreciation->appreciant)) {
                    $ids = explode(',', $appreciation->appreciant); // e.g. "95,96,97"
                    $appreciantNames = Employee::whereIn('user_id', $ids)
                        ->pluck('full_name')
                        ->toArray();
                }

                return [
                    'id' => $appreciation->id,
                    'appreciant' => $appreciantNames, // send array of names
                    'display_date' => $appreciation->display_date
                        ? \Carbon\Carbon::parse($appreciation->display_date)->format('d-m-Y') : '',
                    'display_end_date' => $appreciation->display_end_date
                        ? \Carbon\Carbon::parse($appreciation->display_end_date)->format('d-m-Y') : '',
                    'appreciation_details' => $appreciation->appreciation_details ?? '',
                    'picture' => $appreciation->picture ?? '',
                    'created_at' => $appreciation->created_at
                        ? $appreciation->created_at->format('d-m-Y') : '',
                ];
            });



            return response()->json([
                'data' => $appreciations
            ]);

        }

        //
        $data['meta_title'] = 'Appreciation';
        return view('appreciation.index', $data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $appreciation = Appreciation::updateOrCreate(
            ['id' => $request->id ?: null], // Ensure null, not empty string
            [
                'appreciant'           => is_array($request->appreciant)
                                            ? implode(',', $request->appreciant)
                                            : $request->appreciant,
                'project'              => $request->project,
                'display_date'         => $request->display_date,
                'display_end_date'         => $request->display_end_date,
                'appreciation_details' => $request->appreciation_details,
                'picture'              => $request->picture,
            ]
        );



         $appreciation->save();

        return redirect()->back()->with('success', 'Appreciation created successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $appreciation = Appreciation::find($id);
        $data['appreciation'] = $appreciation;
        return response()->json($data);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $appreciation = Appreciation::find($id);
        $appreciation->delete();
        return response()->json(['message' => 'Appreciation deleted successfully']);
    }

    public function view_appreciation()
    {
        $today = Carbon::today()->toDateString();

        // Fetch all appreciations for today
        $appreciations = Appreciation::orderBy('created_at', 'desc')->get()->map(function ($item) {
            // Exploding the comma-separated employee IDs and retrieving the corresponding Employee models
            $employees = $item->appreciantEmployeesView()->map(function ($emp) {

                return [
                    'full_name' => $emp->full_name ?? '',
                    'profile_image' => $emp->profile_image ?? '',
                    'email' => $emp->user->email ?? '',
                ];
            });

            return [
                'display_date' => $item->display_date,
                'display_end_date' => $item->display_end_date,
                'employees' => $employees,
                'image' => $item->picture,
                'message' => $item->appreciation_details,
            ];
        });


        // Pass the data to the view
        return view('views.appreciation', compact('appreciations'));
    }

}
