<?php

namespace App\Http\Controllers;

use App\Models\Appearence;
use App\Models\BackgroundImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppearenceController extends Controller
{

    public function index(Request $request)
    {

         if($request->ajax()) {

            $currentBackgrounds = DB::table('background_images')->pluck('image_id', 'background_type');

            $backgrounds = Appearence::get()
                ->map(function ($background) use ($currentBackgrounds) {
                    $type = $background->background_type;

                    // Determine if this image is the active one for this type
                    $isActive = isset($currentBackgrounds[$type]) && $currentBackgrounds[$type] == $background->id;

                    return [
                        'id' => $background->id,
                        'background_type' => $type, // now a string
                        'image' => $background->image ?? '',
                        'is_active' => $isActive,
                    ];
                });

                return response()->json(['data' => $backgrounds]);

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
        } else {
            // fallback image if no file uploaded
            $profileImagePath = $request->id ? Appearence::find($request->id)->image : 'appearence/no-images.jpg';
        }

        // Ensure background_type is always an array
        $backgroundTypes = is_array($request->background_type)
            ? $request->background_type
            : [$request->background_type];

        // Create a row for each background type (as string, not JSON array)
        foreach ($backgroundTypes as $type) {
            Appearence::create([
                'background_type' => $type, // Store as plain string
                'image' => $profileImagePath,
            ]);
        }

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
        if ($background_image->image && $background_image->image !== 'appearence/no-images.jpeg') {
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
