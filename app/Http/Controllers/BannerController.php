<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index(Request $request)
    {
        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $banner = Banner::get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'banner_title' => $banner->banner_title ? $banner->banner_title : '',
                    'display_date' => $banner->display_date ? date('d-m-Y',strtotime($banner->display_date)) : '',
                    'banner_detatils' => $banner->banner_details ? $banner->banner_details : '',
                    'picture' => $banner->picture ? $banner->picture : '',
                    'created_at' => $banner->created_at ? date('d-m-Y',strtotime($banner->created_at)) : '',
                ];
            });

            return response()->json([
                'data' => $banner
            ]);

        }

        //
        $data['meta_title'] = 'Banners';
        return view('banner.index', $data);
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
            $profileImagePath = $file->storeAs('banner_pictures', $filename, 'public');
        }

        $banner = Banner::updateOrCreate(
            ['id' => $request->id], // 🔍 Match condition (unique identifier)
            [
                'banner_title'   => $request->banner_title,
                'display_date'    => $request->display_date,
                'banner_details'  => $request->banner_details,
                'picture'           => $profileImagePath ?? ($request->id ? Banner::find($request->id)->picture : 'banner_pictures/no-images.jpg'),
            ]
        );

        if ($profileImagePath) {
            $banner->save();
        }
        return redirect()->back()->with('success', 'Banner created successfully!');
    }


    public function show(Banner $banner)
    {
        //
    }


    public function edit($id)
    {
        $banner = Banner::find($id);
        $data['banner'] = $banner;
        return response()->json($data);
    }


    public function update(Request $request, Banner $banner)
    {
        //
    }


    public function destroy($id)
    {
        $banner = Banner::find($id);
        // Only delete image if it's not the default one
        if ($banner->picture && $banner->picture !== 'banner_pictures/no-images.jpeg') {
            // Check if file exists in storage
            $imagePath = storage_path('app/public/' . $banner->picture);
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file
            }
        }
        $banner->delete();
        return response()->json(['message' => 'Banner deleted successfully']);
    }
}
