<?php

namespace App\Http\Controllers;

use App\Models\Appreciation;
use Illuminate\Http\Request;

class AppreciationController extends Controller
{

    public function index(Request $request)
    {
        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $appreciation = Appreciation::with('employee')->get()
            ->map(function ($appreciation) {
                return [
                    'id' => $appreciation->id,
                    'appreciant' => $appreciation->appreciant ? $appreciation->employee->full_name : '',
                    'display_date' => $appreciation->display_date ? \Carbon\Carbon::parse($appreciation->display_date)->format('d-m-Y') : '',
                    'appreciation_details' => $appreciation->appreciation_details ? $appreciation->appreciation_details : '',
                    'picture' => $appreciation->picture ? $appreciation->picture : '',
                    'created_at' => $appreciation->created_at ? $appreciation->created_at->format('d-m-Y') : '',
                ];
            });

            return response()->json([
                'data' => $appreciation
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
            ['id' => $request->id], // 🔍 Match condition (unique identifier)
            [
                'appreciant'        => $request->appreciant,
                'project'           => $request->project,
                'display_date'      => $request->display_date,
                'appreciation_details'  => $request->appreciation_details,
                'picture'           => $request->picture,
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
}
