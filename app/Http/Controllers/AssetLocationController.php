<?php

namespace App\Http\Controllers;

use App\Models\AssetLocation;
use Illuminate\Http\Request;

class AssetLocationController extends Controller
{

    public function index(Request $request)
    {
        if($request->ajax()) {
            $locations = AssetLocation::get();

            $locations = $locations->map(function ($location, $index) {
                return [
                    'row' => $index + 1,
                    'id' => $location->id,
                    'name' => $location->name,
                    'category' => $location->category?->name
                ];
            });

            return response()->json([
                'data' => $locations
            ]);
        }
        $data['meta_title'] = 'Location';
        return view('company-assets.settings.locations.index', $data);
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
            'name' => 'required',
        ]);

        AssetLocation::updateOrCreate([
            'id' => $request->id
        ], [
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location saved successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetLocation $assetLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($assetLocation)
    {
      $data = AssetLocation::findOrFail($assetLocation);
        return response()->json([
            'success' => "success",
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetLocation $assetLocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($assetLocation)
    {
       $assetType = AssetLocation::findOrFail($assetLocation);
        $assetType->delete();
        return response()->json([
            'success' => true,
            'message' => 'Type deleted successfully!',
        ]);
    }
}
