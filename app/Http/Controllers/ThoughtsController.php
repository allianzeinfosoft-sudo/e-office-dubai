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
                    'display_date' => $thoughts->display_date ? $thoughts->display_date : '',
                    'thoughts_detatils' => $thoughts->thoughts_details ? $thoughts->thoughts_details : '',
                    'picture' => $thoughts->picture ? $thoughts->picture : '',
                    'created_at' => $thoughts->created_at ? $thoughts->created_at : '',
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
                'picture' => $profileImagePath,
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
        //
    }
}
