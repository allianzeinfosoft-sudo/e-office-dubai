<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use App\Models\AssetExpiry;
use App\Models\AssetVendors;
use Illuminate\Http\Request;

class AssetExpiryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $expireItems = AssetExpiry::with(['category', 'vendor'])->get();

            $data = $expireItems->map(function ($item, $index) {
                return [
                    'DT_RowIndex'       => $index + 1,
                    'id'                => $item->id,
                    'service_name'      => $item->service_name,
                    'asset_category'    => $item->category->name ?? '-',
                    'asset_vendor'      => $item->vendor->vendor_name ?? '-',
                    'licence_id'        => $item->licence_id,
                    'licence_count'     => $item->licence_count,
                    'start_date'        => date('d-m-Y', strtotime($item->start_date)),
                    'last_updated_date' => date('d-m-Y', strtotime($item->last_updated_date)),
                    'expiry_date'       => date('d-m-Y', strtotime($item->expiry_date)),
                    'cost'              => $item->cost,
                    'remarks'           => $item->remarks,

                ];
            });

            return response()->json(['data' => $data]);
        }
        //
        $data['meta_title'] = 'Expiry Register';
        return view('company-assets.expiry-register.index', $data);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required',
            'asset_category_id' => 'required',
            'asset_vendor_id' => 'required',

        ]);


        $repairAsset = AssetExpiry::updateOrCreate(
            ['id' => $request->id],
            [
                'service_name'          => $request->service_name,
                'asset_categories_id'   => $request->asset_category_id,
                'asset_vendors_id'      => $request->asset_vendor_id,
                'licence_id'            => $request->licence_id,
                'licence_count'         => $request->licence_count,
                'cost'                  => $request->cost,
                'start_date'            => date('Y-m-d', strtotime($request->start_date)),
                'last_updated_date'     => date('Y-m-d', strtotime($request->last_updated_date)),
                'expiry_date'           => date('Y-m-d', strtotime($request->expiry_date)),
                'remarks'               => $request->remarks,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Repair register updated successfully.' : 'Repair register saved successfully.',
        ]);
    }


    public function show(AssetExpiry $assetExpiry)
    {
        //
    }


    public function edit($assetExpiry)
    {
        $assetExpiry = AssetExpiry::with('category','vendor')->find($assetExpiry);

        return response()->json([
            'status' => 'success',
            'data' => $assetExpiry
        ]);
    }


    public function update(Request $request, AssetExpiry $assetExpiry)
    {
        //
    }


    public function destroy($id)
    {
        $expireItem = AssetExpiry::find($id);
        $expireItem->delete();

         return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully.'
        ]);
    }

    public function reportAssetExpiry()
    {
        $data['meta_title'] = 'Asset Expiry Report';

        return view('company-assets.expiry-register.index', $data);
    }
}
