<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $categories = AssetCategory::all();
            $data = [];
            foreach ($categories as $index => $category) {
                $data[] = [
                    'row' => $index + 1,
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            }
            return response()->json(['data' => $data]);
        }
        $data['meta_title'] = 'Asset Categories';
        return view('properties.category.index', $data);
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
        $request->validate(['name' => 'required|string|max:255']);

        $category = AssetCategory::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name]
        );

        return response()->json(['success' => true, 'message' => 'Category saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetCategory $assetCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetCategory $assetCategory)
    {
        //
         $category = $assetCategory;
        if ($category) {
            return response()->json(['success' => true, 'data' => $category]);
        }

        return response()->json(['success' => false, 'message' => 'Category not found']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetCategory $assetCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetCategory $assetCategory){
        $assetCategory->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted']);
    }
}
