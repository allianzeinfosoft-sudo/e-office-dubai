<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{

    public function index()
    {

        $galleries = Gallery::latest()->get();
        return view('gallery.index', compact('galleries'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'display_date' => 'required|date',
            'gallery_details' => 'nullable|string',
            'file.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $storedImages = [];

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $image) {
                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/gallery', $filename);
                $storedImages[] = 'storage/gallery/' . $filename;
            }
        }

        $gallery = Gallery::create([
            'title' => $request->title,
            'display_date' => $request->display_date,
            'description' => $request->gallery_details,
            'file' => json_encode($storedImages), // Store image paths as JSON
        ]);

        return redirect()->route('gallery.index')->with('success', 'Gallery saved successfully!');
    }



    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('gallery.show', compact('gallery'));
    }


    public function edit(Gallery $gallery)
    {
        //
    }


    public function update(Request $request, Gallery $gallery)
    {
        //
    }


    public function destroy(Gallery $gallery)
    {
        //
    }

    public function deleteImage(Request $request, Gallery $gallery)
    {
        $imageToDelete = $request->input('image');

        $images = json_decode($gallery->file, true);

        if (($key = array_search($imageToDelete, $images)) !== false) {
            // Remove from array
            unset($images[$key]);

            // Unlink (delete file from public storage)
            $imagePath = public_path($imageToDelete);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Reindex array and update DB
            $gallery->file = json_encode(array_values($images));
            $gallery->save();
        }

        return response()->json(['success' => true]);
    }




}
