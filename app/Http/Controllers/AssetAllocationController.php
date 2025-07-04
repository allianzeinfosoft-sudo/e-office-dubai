<?php

namespace App\Http\Controllers;

use App\Models\AssetAllocation;
use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemLine;
use App\Models\AssetItemMaster;
use App\Models\AssetMapping;
use App\Models\AssetType;
use App\Models\AssetVendors;
use Illuminate\Http\Request;

class AssetAllocationController extends Controller
{

    public function index(Request $request)
    {
        $data['classifications'] = AssetClassification::get();
        $data['assetItems'] = AssetItemMaster::all();
        $data['vendors'] = AssetVendors::all();
        $data['assetClassifications'] = AssetClassification::all();
        $data['assetCategories'] = AssetCategory::all();
        $data['assetTypes'] = AssetType::all();

        $data['meta_title'] = 'Allocation Location';
        return view('company-assets.allocation.index', $data);
    }

    public function getModels(Request $request)
    {
        $models = AssetItemLine::where('asset_item_id', $request->item_id)->get(['id', 'item_model']);
        return response()->json($models);
    }

    public function getSerialsNaqty(Request $request)
    {

         // Fetch serials for the selected item and model
        $serials = AssetItemLine::where('asset_item_id', $request->item_id)
            ->where('item_model', $request->item_model)
            ->get(['id', 'serial_number']);

        // Calculate total quantity for this model under the selected item
        $totalQty = AssetItemLine::where('asset_item_id', $request->item_id)
            ->where('item_model', $request->item_model)
            ->sum('asset_quantity'); // or use 'qty' if that's your column name




        return response()->json([
            'serials' => $serials,
            'total_quantity' => $totalQty,
        ]);
    }



    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(AssetAllocation $assetAllocation)
    {
        //
    }

    public function edit(AssetAllocation $assetAllocation)
    {
        //
    }

    public function update(Request $request, AssetAllocation $assetAllocation)
    {
        //
    }


    public function destroy(AssetAllocation $assetAllocation)
    {
        //
    }
}
