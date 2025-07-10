<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\AllocationLineItem;
use App\Models\AssetAllocation;
use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemLine;
use App\Models\AssetItemMaster;
use App\Models\AssetMapping;
use App\Models\AssetType;
use App\Models\AssetVendors;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

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
       $models = AssetItemLine::where('asset_item_id', $request->item_id)
        ->get()
        ->map(function ($model) {
            return [
                'id' => $model->id,
                'item_model' => $model->item_model ?? '',
            ];
        })
        ->groupBy(function ($item) {
            return $item['item_model']; // group by array value
        });

        return response()->json($models);
    }

    public function getSerials(Request $request)
    {

        $serials = AssetItemLine::where('asset_item_id', $request->item_id)
            ->where('item_model', $request->item_model)
            ->get(['id', 'serial_number']);

        return response()->json([
            'serials' => $serials
        ]);
    }

    public function getQty(Request $request)
    {
        $totalQty = AssetItemLine::where('serial_number', $request->serial_number)
            ->sum('asset_quantity');
        return response()->json([
            'total_quantity' => $totalQty,
        ]);
    }

    public function getAssetId(Request $request)
    {

        $assetIds = AssetMapping::with('masteritem')->where('serial_number', $request->serial_id)->where('allocation_status',0)->get()
        ->map(function ($assetIds) {
            return [
                'id' => $assetIds->id,
                'asset_id' => $assetIds->item_number ? $assetIds->masteritem->item_code .'-'. $assetIds->item_number : '',
                'asset_id_number' => $assetIds->item_number ?? '',
            ];
        });

        return response()->json([
            'assetIds' => $assetIds,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'asset_id' => 'required',
            'asset_user' => 'required',
            'asset_item_id' => 'required|array',
            'asset_model_id' => 'nullable|array',
            'asset_project_id' => 'required|array',
            // 'asset_available_quantity' => 'required|array',
            // 'asset_quantity' => 'required|array',
            'specification' => 'required|array',
            'remarks' => 'required',
        ]);


        $asset = AssetAllocation::updateOrCreate(
            ['id' => $request->id],
            [
                'user_type'   => $request->asset_user,
                'user'   => $request->asset_employee ?? $request->asset_location ?? '',
                'department'  => $request->department_id ?? '',
                'remarks' => $request->remarks
            ]
        );

        //  Delete old item lines if editing
        $asset->items()->delete();

        //  Save asset item lines
        foreach ($request->asset_item_id as $index => $itemId) {

            $master_item_code = CustomHelper::getItemCode($request->asset_item_id[$index]);

            $asset_code = $master_item_code->item_code.'-'.$request->asset_id[$index];
            $itemLine = $asset->items()->create([
                'allocation_id'           => $itemId,
                'item'                    => $request->asset_item_id[$index],
                'model'                   => $request->asset_model_id[$index],
                'serial_number'           => $request->asset_serialnumber[$index],
                'project'                 => $request->asset_project_id[$index],
                'asset_id'                => $asset_code,
                // 'qty'                     => $request->asset_quantity[$index],
                'specification'           => $request->specification[$index]
            ]);


            CustomHelper::updateAssetMapping([

                'allocation_id'  => $itemLine->id,
                'user_type'      => $request->asset_user,
                'asset_item_id'  => $request->asset_item_id[$index],
                'item_number'    => $request->asset_id[$index],
                'model'          => $request->asset_model_id[$index],
                'serial_number'  => $request->asset_serialnumber[$index],
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Asset allocation saved successfully.',
        ]);
    }

   public function allotedItemSearch(Request $request)
    {
        $userType = $request->input('user');
        $userId = $request->input('employee');

        // Fix: Tell Laravel what the current page is
        Paginator::currentPageResolver(function () use ($request) {
            return $request->input('page', 1);
        });

       $allocations = AssetAllocation::with(['items' => function ($query) {
                    $query->where('status', 1);
                }, 'employee', 'department_name'])
            ->where('user_type', $userType)
            ->where('user', $userId)
            ->where('status', 1)
            ->whereHas('items', function ($query) {
                $query->where('status', 1);
            })
            ->paginate(5);

        $html = view('partials.asset_allocation_accordion', compact('allocations'))->render();

        return response()->json([
            'message' => 'Allocated items loaded successfully.',
            'html' => $html
        ]);
    }

  public function returnToStore(Request $request, $allocationId)
    {
        $item = AllocationLineItem::findOrFail($allocationId);
        $item->status = 0;
        $item->return_date_time = date('Y-m-d H:i:s');
        $item->comment = $request->comment;
        $item->save();

        $mappings = AssetMapping::whereJsonContains('allocation_id', (int) $allocationId)->get();

        foreach ($mappings as $mapping) {
            // Filter out the current allocationId
            $updatedIds = array_filter($mapping->allocation_id, function ($id) use ($allocationId) {
                return $id != $allocationId;
            });

            // Re-index the array to avoid JSON encoding issues
            $mapping->allocation_id = array_values($updatedIds);
            $mapping->allocation_status = 0;
            $mapping->save();
        }

        // Check if AJAX
        if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asset returned successfully.'
                ]);
            }

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
