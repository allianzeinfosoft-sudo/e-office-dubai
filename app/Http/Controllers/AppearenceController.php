<?php

namespace App\Http\Controllers;

use App\Models\Appearence;
use App\Models\BackgroundImage;
use Illuminate\Http\Request;

class AppearenceController extends Controller
{

    public function index(Request $request)
    {

         if($request->ajax()) {

            $background = Appearence::get()
            ->map(function ($background) {
                return [
                    'id' => $background->id,
                    'background_type' => $background->background_type ? $background->background_type : '',
                    'image' => $background->image ? $background->image : '',

                ];
            });



            return response()->json([
                'data' => $background
            ]);

        }

        $data['meta_title'] = 'Change Appearence';
        return view('appearence.index', $data);

    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $profileImagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('appearence', $filename, 'public');
        }

        Appearence::create([
            'background_type' => is_array($request->background_type) ? json_encode($request->background_type) : $request->background_type,
            'image' => $profileImagePath ?? ($request->id ? Appearence::find($request->id)->image : 'appearence/no-images.jpg'),
        ]);

        return redirect()->back()->with('success', 'Appearance saved successfully.');
    }


    public function show(Appearence $changeAppearence)
    {
        //
    }


    public function edit(Appearence $changeAppearence)
    {
        //
    }


    public function update(Request $request, Appearence $changeAppearence)
    {
        //
    }

    public function destroy($id)
    {
        $background_image = Appearence::find($id);
        // Only delete image if it's not the default one
        if ($background_image->image && $background_image->image !== 'banner_pictures/no-images.jpeg') {
            // Check if file exists in storage
            $imagePath = storage_path('app/public/' . $background_image->image);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file
            }
        }
        $background_image->delete();
        return response()->json(['message' => 'Background image deleted successfully']);
    }

    public function Bg_select(Request $request)
    {
        $selected = BackgroundImage::updateOrCreate(
            ['background_type' => $request->background_type],
            ['image_id' => $request->image_id]
        );

        return response()->json([
            'message' => 'Background image selected successfully.',
            'selected' => $selected
        ]);
    }
}
