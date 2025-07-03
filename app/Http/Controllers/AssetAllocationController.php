<?php

namespace App\Http\Controllers;

use App\Models\AssetAllocation;
use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemMaster;
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
