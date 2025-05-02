<?php

namespace App\Http\Controllers;

use App\Models\Thoughts;
use Illuminate\Http\Request;

class ThoughtsController extends Controller
{

    public function index(Request $request)
    {

        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $thoughts = Thoughts::get()
            ->map(function ($thoughts) {
                return [
                    'id' => $thoughts->id,
                    'thoughts_title' => $thoughts->thoughts_title ? $thoughts->thoughts_title : '',
                    'display_date' => $thoughts->display_date ?  date('d-m-Y', strtotime($thoughts->display_date)) : 'N/A',
                    'thoughts_detatils' => $thoughts->thoughts_details ? $thoughts->thoughts_details : '',
                    'picture' => $thoughts->picture ? $thoughts->picture : '',
                    'created_at' => $thoughts->created_at ? date('d-m-Y', strtotime($thoughts->created_at)) : '',
                ];
            });

            return response()->json([
                'data' => $thoughts
            ]);

        }

        //
        $data['meta_title'] = 'Thoughts';
        return view('thoughts.index', $data);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $profileImagePath = null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('thoughts_pictures', $filename, 'public');
        }

        $thoughts = Thoughts::updateOrCreate(
            ['id' => $request->id], // 🔍 Match condition (unique identifier)
            [
                'thoughts_title'   => $request->thoughts_title,
                'display_date'    => $request->display_date,
                'thoughts_details'  => $request->thoughts_details,
                'picture'           => $profileImagePath ?? ($request->id ? Thoughts::find($request->id)->picture : 'thoughts_pictures/no-images.jpg'),
            ]
        );

        if ($profileImagePath) {
            $thoughts->save();
        }
        return redirect()->back()->with('success', 'Thoughts created successfully!');

    }


    public function show(string $id)
    {
        //
    }



    public function edit($id){
        $thoughts = Thoughts::find($id);
        $data['thoughts'] = $thoughts;
        return response()->json($data);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy($id)
    {
        $thoughts = Thoughts::find($id);
        // Only delete image if it's not the default one
        if ($thoughts->picture && $thoughts->picture !== 'thoughts_pictures/no-images.jpeg') {
            // Check if file exists in storage
            $imagePath = storage_path('app/public/' . $thoughts->picture);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file
            }
        }
        $thoughts->delete();
        return response()->json(['message' => 'Thoughts deleted successfully']);
    }
}
