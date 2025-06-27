<?php

namespace App\Http\Controllers;

use App\Models\AssetItemMaster;
use Illuminate\Http\Request;

class AssetItemMasterController extends Controller
{
   public function index(Request $request)
    {
        if($request->ajax()) {
            $items = AssetItemMaster::get();

            $items = $items->map(function ($item, $index) {
                return [
                    'row' => $index + 1,
                    'id' => $item->id,
                    'item_code' => $item->item_code,
                    'name' => $item->name,
                    'description' => $item->description,
                    'brand' => $item->brand,
                    'status' => $item->status,
                ];
            });

            return response()->json([
                'data' => $items
            ]);
        }
        $data['meta_title'] = 'Item Master';
        return view('company-assets.settings.item-master.index', $data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'required',
            'name' => 'required',
            'brand' => 'required',
        ]);

        AssetItemMaster::updateOrCreate([
            'id' => $request->id
        ],
        [
            'name' => $request->name,
            'item_code' => $request->item_code,
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item saved successfully!',
        ]);
    }


    public function show(AssetItemMaster $assetItemMaster)
    {
        //
    }

    public function edit($assetItemMaster)
    {
          $data = AssetItemMaster::findOrFail($assetItemMaster);
        return response()->json([
            'success' => "success",
            'data' => $data
        ]);
    }

    public function update(Request $request, AssetItemMaster $assetItemMaster)
    {
        //
    }

    public function destroy($assetItemMaster)
    {
        $itemMaster = AssetItemMaster::findOrFail($assetItemMaster);
        $itemMaster->delete();
        return response()->json([
            'success' => true,
            'message' => 'Item Master deleted successfully!',
        ]);
    }
}
