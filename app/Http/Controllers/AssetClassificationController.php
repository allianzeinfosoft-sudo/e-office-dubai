<?php

namespace App\Http\Controllers;

use App\Models\AssetClassification;
use Illuminate\Http\Request;

class AssetClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        //
        if($request->ajax()) {
            $classifications = AssetClassification::all();

            $classifications = $classifications->map(function ($classification, $index) {
                return [
                    'row' => $index + 1,
                    'id' => $classification->id,
                    'name' => $classification->name,
                ];
            });

            return response()->json([
                'data' => $classifications
            ]);
        }

        $data['meta_title'] = 'Classifications';
        return view('company-assets.settings.classifications.index', $data);
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
            'name' => 'required',
        ]);

        AssetClassification::updateOrCreate([
            'id' => $request->id
        ], [
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Classification saved successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetClassification $assetClassification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetClassification $assetClassification)
    {
        //
        $data = $assetClassification;
        
        return response()->json([
            'success' => "success",
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetClassification $assetClassification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetClassification $assetClassification)
    {
        //
        $assetClassification->delete();
        return response()->json([
            'success' => true,
            'message' => 'Classification deleted successfully!',
        ]);
    }
}
