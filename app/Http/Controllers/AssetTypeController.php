<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use App\Models\AssetType;
use Illuminate\Http\Request;

class AssetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       if($request->ajax()) {
            $types = AssetType::with('category')->get();

            $types = $types->map(function ($type, $index) {
                return [
                    'row' => $index + 1,
                    'id' => $type->id,
                    'name' => $type->name,
                    'category' => $type->category?->name
                ];
            });

            return response()->json([
                'data' => $types
            ]);
        }
        $data['categories'] =  AssetCategory::all();
        $data['meta_title'] = 'Types';
        return view('company-assets.settings.types.index', $data);
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
            'asset_category_id' => 'required',
        ]);

        AssetType::updateOrCreate([
            'id' => $request->id
        ], [
            'name' => $request->name,
            'asset_category_id' => $request->asset_category_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Type saved successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetType $assetType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($type)
    {

        $data = AssetType::findOrFail($type);
        return response()->json([
            'success' => "success",
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetType $assetType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($type)
    {
        $assetType = AssetType::findOrFail($type);
        $assetType->delete();
        return response()->json([
            'success' => true,
            'message' => 'Type deleted successfully!',
        ]);
    }
}
