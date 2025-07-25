<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemLine;
use App\Models\AssetItemMaster;
use App\Models\AssetLocation;
use App\Models\AssetMapping;
use App\Models\AssetType;
use App\Models\AssetVendors;
use Illuminate\Http\Request;

class AssetDashboardController extends Controller
{
    public function index()
    {

        $data['meta_title'] = 'Stock Report';
        $data['vendors'] = AssetVendors::all();
        $data['items']  = AssetItemMaster::all();
        $data['classifications'] = AssetClassification::all();
        $data['categories'] = AssetCategory::all();
        $data['types'] = AssetType::all();

        $data['total_stock'] = AssetMapping::count('master_item_id');
        $data['stock_in_hand'] = AssetMapping::where('allocation_status', 0)->count('master_item_id');
        $data['stock_allocated'] = AssetMapping::where('allocation_status', 1)->count('master_item_id');
        $data['stock_scraped'] = AssetMapping::where('allocation_status', 3)->count('master_item_id');
        $data['stock_repaired'] = AssetMapping::where('allocation_status', 2)->count('master_item_id');
        // dd($data['stock_repaired']);

        $data['classifications'] = AssetClassification::all();
        $data['categories'] = AssetCategory::all();
        $data['item_types'] = AssetType::all();
        $data['locations'] = AssetLocation::all();
        $data['master_items'] = AssetItemMaster::all();
        $data['vendors'] = AssetVendors::all();

        $data['assetsClassified'] = AssetItemLine::with('asset_classification')
            ->selectRaw('asset_classification_id, SUM(asset_quantity) as total_quantity')
            ->groupBy('asset_classification_id')
            ->get();

       return view('company-assets.dashboard',$data);
    }

}
