<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\AllocationLineItem;
use App\Models\AssetAllocation;
use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemLine;
use App\Models\AssetItemMaster;
use App\Models\AssetLocation;
use App\Models\AssetMapping;
use App\Models\AssetType;
use App\Models\AssetVendors;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
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

        $assetIds = AssetMapping::with('masteritem')->where('serial_number', $request->serial_id)->get()
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

            'asset_code_id'     => 'required',
            'asset_user'        => 'required',
            'asset_item_id'     => 'required|array',
            'asset_model_id'    => 'nullable|array',
            'asset_project_id'  => 'required|array',
            'specification'     => 'required|array',
            'remarks'           => 'required',
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

        //  Save asset item lines
        foreach ($request->asset_item_id as $index => $itemId) {

            $master_item_code = CustomHelper::getItemCode($request->asset_item_id[$index]);

            $itemLine = $asset->items()->create([
                'allocation_id'           => $itemId,
                'item'                    => $request->asset_item_id[$index],
                'model'                   => $request->asset_model_id[$index],
                'serial_number'           => $request->asset_serialnumber[$index],
                'project'                 => $request->asset_project_id[$index],
                'asset_mapping_id'        => $request->asset_code_id[$index],
                'specification'           => $request->specification[$index]
            ]);


            CustomHelper::updateAssetMapping([

                'allocation_id'  => $itemLine->id,
                'user_type'      => $request->asset_user,
                'asset_mapping_id' => $request->asset_code_id[$index],
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

    public function reportAllocatedItems()
    {
        $data['meta_title']  = 'Allocated Items Report';
        $data['departments'] = Department::all();
        $data['employees'] = Employee::all();
        $data['locations'] = AssetLocation::all();
        $data['projects'] = Project::all();
        $data['classifications'] = AssetClassification::all();
        return view('company-assets.reports.allocation_item_report', $data);
    }

    public function allocatedItemsReport(Request $request)
    {
        $classification = $request->input('classification');
        $department     = $request->input('department');
        $user_type      = $request->input('user_type');
        $location       = $request->input('location');
        $employee           = $request->input('employee');
        $project        = $request->input('project');

        $query = AssetAllocation::with(['items' => function ($q) {
                            $q->where('status', 1)
                            ->with(['masterItem', 'project_info']);
                        }, 'employee', 'department_name'])
                    ->where('status', 1);


        if(!empty($classification))
        {

        }
        // Apply filters if present
        if (!empty($user_type)) {
            $query->where('user_type', $user_type);
        }
        if($user_type == 'employee' && !empty($employee))
        {
            $query->where('user', $employee);
        }
        if($user_type == 'location' && !empty($location))
        {
            $query->where('user', $location);
        }


        if (!empty($department)) {
            $query->where('department', $department);
        }

        if (!empty($project)) {
            $query->whereHas('items', function ($q) use ($project) {
                $q->where('project', $project);
            });
        }

        // Load data (no pagination)
        $allocated_items = $query->get();

        $reportData = [];
        $rowIndex = 1;

        foreach ($allocated_items as $allocation) {

            foreach ($allocation->items as $item) {

                $reportData[] = [
                    'DT_RowIndex'    => $rowIndex++,
                    'item_name' => ($item->masterItem?->brand ?? '') . ' ' . ($item->masterItem?->name ?? ''),
                    'model'          => $item->model ?? '',
                    'asset_id'       => CustomHelper::itemCodeGenerater($item->asset_mapping_id),
                    'serial_number'  => $item->serial_number ?? '',
                    'allocated_to'   => $allocation->employee->full_name ?? '',
                    'department'     => $allocation->department_name->department ?? '',
                    'project'        => $item->project_info->project_name ?? '',
                    'allocated_date' => optional($allocation->created_at)->format('d-m-Y'),
                ];
            }
        }

        return response()->json(['data' => $reportData]);
    }

    public function getAssetCode(Request $request)
    {

         $asset = AssetMapping::where('master_item_id', $request->item_id)
        ->get()
        ->map(function ($assetCodes) {
            return [
                'id' => $assetCodes->id,
                'assetCode' => CustomHelper::itemCodeGenerater($assetCodes->id),
            ];
        });
        return response()->json($asset);
    }





   public function getAssetItemInfo(Request $request)
    {
        $asset = AssetMapping::with('register_lineitem')->find($request->item_id);

        if (!$asset) {
            return response()->json(['error' => 'Asset not found'], 404);
        }

        return response()->json([
            'id' => $asset->id,
            'model' => $asset->model ?? '-',
            'brand' => $asset->register_lineitem?->asset_brand ?? '-',
            'classification' => $asset->register_lineitem?->asset_classification?->name ?? '-',
            'category' => $asset->register_lineitem?->asset_category?->name ?? '-',
            'type' => $asset->register_lineitem?->asset_type?->name ?? '-',
            'serial_number' => $asset->serial_number ?? '-',
            'specification' => $asset->register_lineitem?->asset_description ?? '-',
        ]);
    }


    public function allAssets(Request $request)
    {
        // dd($request->all());
          // Optional paginator override (usually not needed unless doing something special)
        Paginator::currentPageResolver(function () use ($request) {
            return $request->input('page', 1);
        });

        // Start query with eager loading
        $query = AssetMapping::with(['register_lineitem', 'masteritem']);

        // Filter by asset ID if provided
        if ($request->filled('asset_id')) {
            $query->where('id', $request->asset_id);
        }

        // Paginate results (with filter values preserved in pagination links)
        $assets = $query->paginate(10)->withQueryString();

        // Get all asset IDs (for dropdown or search suggestion list, etc.)
        $assetIds = AssetMapping::pluck('id'); // more efficient than all()

        // Return view
        return view('company-assets.assets.index', [
            'meta_title' => 'All Assets',
            'assets' => $assets,
            'assetIds' => $assetIds,
        ]);
    }



}
