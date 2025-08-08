<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\AssetRegister;
use Illuminate\Http\Request;

use App\Models\AssetVendors;
use App\Models\AssetItemMaster;
use App\Models\AssetClassification;
use App\Models\AssetCategory;
use App\Models\AssetItemLine;
use App\Models\AssetType;
use App\Models\AssetMapping;
use Illuminate\Support\Facades\Storage;

class AssetRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $asset = AssetRegister::with('vendor')
                ->select('id','asset_number', 'asset_date', 'company_name', 'purchase_date', 'invoice_number', 'vendor_id', 'total_amount', 'upload_invoice', 'remarks')
                ->get();

            $data = $asset->map(function($item, $index) {

                    return [
                        'DT_RowIndex'       => $index + 1,
                        'id'                => $item->id,
                        'batch_no'          => $item->asset_number,
                        'asset_date'        => date('d-m-Y', strtotime($item->asset_date)), //$item->asset_date,
                        'company_name'      => $item->company_name,
                        'purchase_date'     => date('d-m-Y', strtotime($item->purchase_date)), //$item->purchase_date,
                        'invoice_number'    => $item->invoice_number,
                        'vendor_name'         => $item->vendor->vendor_name,
                        'total_amount'      => $item->total_amount,
                        'upload_invoice'    => $item->upload_invoice ? '<a href="' . asset('storage/' . $item->upload_invoice) . '" target="_blank"><i class="fa fa-file"></i></a>' : 'N/A',
                        'remarks'           => $item->remarks ?? ''
                    ];

                });



            return response()->json(['data' => $data]);
        }
        //
        $data['meta_title'] = 'Asset Register';
        $data['vendors'] = AssetVendors::all();
        $data['assetItems'] = AssetItemMaster::all();
        $data['assetClassifications'] = AssetClassification::all();
        $data['assetCategories'] = AssetCategory::all();
        $data['assetTypes'] = AssetType::all();

        return view('company-assets.register.index', $data);
    }

    public function registered_items(Request $request)
    {

        if($request->ajax()) {
            $asset =  AssetMapping::with(['register_lineitem', 'masteritem'])->where('allocation_status',0)->get();

            $data = $asset->map(function($item, $index) {

                    return [
                        'DT_RowIndex'       => $index + 1,
                        'id'                => $item->id,
                        'asset_id'          => CustomHelper::itemCodeGenerater($item->id) ?? '-',
                        'classification'    => $item->register_lineitem?->asset_classification?->name ?? '-', //$item->asset_date,
                        'category'          => $item->register_lineitem?->asset_category?->name ?? '-',
                        'type'              => $item->register_lineitem?->asset_type?->name ?? '-', //$item->purchase_date,
                        'item'              => $item->masteritem?->name ?? '-',
                        'brand'             => $item->register_lineitem?->asset_brand ?? '-',
                        'model'             => $item->register_lineitem?->item_model ?? '-',
                        'item_key_id'       => $item->register_lineitem?->item_key_id ?? '-',
                        'serial_number'     => $item->register_lineitem?->serial_number ?? '-',
                        'specification'     => $item->register_lineitem?->asset_description ?? '-',
                        'price'             => $item->register_lineitem?->asset_price ?? '-',
                        'asset_register_id' => $item->register_lineitem_id ?? '',
                    ];

                });



            return response()->json(['data' => $data]);
        }
        //
        $data['meta_title'] = 'Asset Items';
        $data['vendors'] = AssetVendors::all();
        $data['assetItems'] = AssetItemMaster::all();
        $data['assetClassifications'] = AssetClassification::all();
        $data['assetCategories'] = AssetCategory::all();
        $data['assetTypes'] = AssetType::all();

        return view('company-assets.register.items', $data);
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
        $validated = $request->validate([
            'company_name' => 'required|string',
            'asset_number' => 'required',
            'purchase_date' => 'required',
            'invoice_number' => 'required',
            'vendor_id' => 'required',
            'upload_invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'asset_item_id' => 'required|array',
            'asset_total' => 'required|array',
        ]);

        // Upload invoice
        $filePath = null;
        if ($request->hasFile('upload_invoice')) {
            $filePath = $request->file('upload_invoice')->store('invoices', 'public');
        }

        $totalAmount = array_sum($request->asset_total);
        if ($request->id) {
            $asset = AssetRegister::findOrFail($request->id);
            $totalAmount = $asset->total_amount + $totalAmount;
        }
        // Create or update main record
        $asset = AssetRegister::updateOrCreate(
            ['id' => $request->id],
            [
                'asset_date'     => date('Y-m-d', strtotime($request->purchase_date)),
                'asset_number'   => $request->asset_number,
                'company_name'   => $request->company_name,
                'purchase_date'  => date('Y-m-d', strtotime($request->purchase_date)), //$request->purchase_date,
                'invoice_number' => $request->invoice_number,
                'vendor_id'      => $request->vendor_id,
                'total_amount'   => $totalAmount,
                'remarks'        => $request->remarks ?? '',
                'upload_invoice' => $filePath,
            ]
        );

        // Get all line item IDs
        $lineItemIds = $asset->items()->pluck('id');

        // Delete related mappings
        // AssetMapping::whereIn('register_lineitem_id', $lineItemIds)->delete();

        // Now delete line items
        // $asset->items()->delete();

        // Save asset item lines
        foreach ($request->asset_item_id as $index => $itemId) {

            $LineItems = $asset->items()->create([
                'asset_item_id'           => $itemId,
                'asset_brand'             => $request->asset_brand[$index],
                'item_model'              => $request->asset_model[$index],
                'item_key_id'             => $request->item_key_id[$index],
                'asset_description'       => $request->asset_unit[$index],  // Forgot to add unit column so I added to this field
                'asset_classification_id' => $request->asset_classification_id[$index],
                'asset_category_id'       => $request->asset_category_id[$index],
                'asset_type_id'           => $request->asset_type_id[$index],
                'asset_quantity'          => $request->asset_quantity[$index],
                'asset_price'             => $request->asset_price[$index],
                'asset_total'             => number_format((float) $request->asset_total[$index], 2, '.', ''),
                'serial_number'           => $request->serial_number[$index],
                'warranty'                => $request->warranty[$index],
            ]);

            CustomHelper::updateOrCreateAssetMapping([
                'master_item_id'        => $itemId,
                'register_lineitem_id'  => $LineItems->id,
                'asset_quantity'        => $request->asset_quantity[$index],
                'model'                 => $request->asset_model[$index],
                'serial_number'         => $request->serial_number[$index],
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Asset register saved successfully.',
        ]);
        //return redirect()->back()->with('success', 'Asset register saved successfully.');
    }

    public function update_item(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string',
            'asset_number' => 'required',
            'purchase_date' => 'required',
            'invoice_number' => 'required',
            'vendor_id' => 'required',
            'asset_item_id' => 'required|array',
            'asset_total' => 'required|array',
        ]);

        $asset_total = array_sum($request->asset_total);
        $asset_pre_total = array_sum($request->asset_pre_total);

        $asset_price = array_sum($request->asset_price);
        $asset_pre_price = array_sum($request->asset_pre_price);


         if ($request->line_id) {

             $index = 1;
                $assetLine = AssetItemLine::findOrFail($request->line_id);
                $assetTotal = ($assetLine->asset_total - $asset_pre_price) + $asset_price;
                AssetItemLine::where('id',$request->line_id)->update([

                    'asset_item_id'           => $request->asset_item_id[$index],
                    'asset_brand'             => $request->asset_brand[$index],
                    'item_model'              => $request->asset_model[$index],
                    'item_key_id'             => $request->item_key_id[$index],
                    'asset_description'       => $request->asset_unit[$index],  // Forgot to add unit column so I added to this field
                    'asset_classification_id' => $request->asset_classification_id[$index],
                    'asset_category_id'       => $request->asset_category_id[$index],
                    'asset_type_id'           => $request->asset_type_id[$index],
                    'asset_quantity'          => $request->asset_quantity[$index],
                    'asset_price'             => $request->asset_price[$index],
                    'asset_total'             => number_format((float) $assetTotal, 2, '.', ''),
                    'serial_number'           => $request->serial_number[$index],
                    'warranty'                => $request->warranty[$index],

                 ]);
        }

        if ($request->id) {

            $asset = AssetRegister::findOrFail($request->id);
            $assetLineNew = AssetItemLine::findOrFail($request->line_id);
            $totalAmount = ($asset->total_amount - $asset_pre_total) + $assetLineNew->asset_total;

            AssetRegister::updateOrCreate(
                ['id' => $request->id],
                [
                    'asset_date'     => date('Y-m-d', strtotime($request->purchase_date)),
                    'asset_number'   => $request->asset_number,
                    'company_name'   => $request->company_name,
                    'purchase_date'  => date('Y-m-d', strtotime($request->purchase_date)), //$request->purchase_date,
                    'invoice_number' => $request->invoice_number,
                    'vendor_id'      => $request->vendor_id,
                    'total_amount'   => $totalAmount,
                    'remarks'        => $request->remarks ?? '',
                ]
            );
        }





        if ($request->mapping_id) {
            $index = 1;
            AssetMapping::where('id',$request->mapping_id)->update([

                'master_item_id'        => $request->asset_item_id[$index],
                'model'                 => $request->asset_model[$index],
                'serial_number'         => $request->serial_number[$index],
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Asset register updated successfully.',
        ]);
    }

    public function show(AssetRegister $assetRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $register = AssetRegister::with('items')->findOrFail($id);

        return response()->json([
            'data' => $register
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetRegister $assetRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $register = AssetRegister::findOrFail($id);
        $register->delete();

        // Delete related items
        // Get all line item IDs
        $lineItemIds = $register->items()->pluck('id');

        // Delete related mappings
        AssetMapping::whereIn('register_lineitem_id', $lineItemIds)->delete();

        $register->items()->delete();
        if (!empty($register->upload_invoice) && Storage::disk('public')->exists($register->upload_invoice)) {
            Storage::disk('public')->delete($register->upload_invoice);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset register deleted successfully.'
        ]);
    }

    /* stock Report */
    public function stockReport(){
        $data['meta_title'] = 'Stock Report';
        $data['vendors'] = AssetVendors::all();
        $data['items']  = AssetItemMaster::all();
        $data['classifications'] = AssetClassification::all();
        $data['categories'] = AssetCategory::all();
        $data['types'] = AssetType::all();


        $data['stock_in_hand'] = AssetMapping::where('allocation_status', 0)->count('master_item_id');
        $data['stock_allocated'] = AssetMapping::where('allocation_status', 1)->count('master_item_id');
        $data['stock_scraped'] = AssetMapping::where('allocation_status', 3)->count('master_item_id');
        $data['stock_repaired'] = AssetMapping::where('allocation_status', 2)->count('master_item_id');

        $data['assetsClassified'] = AssetItemLine::with('asset_classification')
            ->selectRaw('asset_classification_id, SUM(asset_quantity) as total_quantity')
            ->groupBy('asset_classification_id')
            ->get();
        return view('company-assets.reports.stock-report', $data);
    }

    // public function stockItemReport(Request $request){

    //     $location_status = $request->input('location_status'); // all, allocated, in_store
    //     $classification  = $request->input('classification');
    //     $category        = $request->input('category');
    //     $type            = $request->input('type');
    //     $asset_item_id   = $request->input('asset_item_id');
    //     $vendor          = $request->input('vendor');

    //     $query = AssetRegister::with(['vendor','items']);
    //     if (!empty($vendor)) {
    //         $query->where('vendor_id', $vendor);
    //     }

    //     $query->whereHas('items', function ($q) use ($asset_item_id, $classification, $category, $type) {

    //         if (!empty($classification)) {
    //             $q->where('asset_classification_id', $classification);
    //         }

    //         if (!empty($category)) {
    //             $q->where('asset_category_id', $category);
    //         }

    //         if (!empty($type)) {
    //             $q->where('asset_type_id', $type);
    //         }


    //         // Add location_status filter based on mapping

    //     })->with(['items' => function ($q) use ($location_status, $asset_item_id) {

    //         if ($location_status === 'allocated') {

    //             $q->whereDoesntHave('mapping', fn ($mq) => $mq->where('allocation_status', 0));

    //         } elseif ($location_status === 'in_store') {

    //             $q->whereHas('mapping', fn ($mq) => $mq->where('allocation_status', 0));
    //         }

    //         if (!empty($asset_item_id)) {

    //             $q->where('asset_item_id', $asset_item_id);
    //         }

    //         $q->with([
    //             'asset_item',
    //             'asset_classification',
    //             'asset_category',
    //             'asset_type',
    //             'mapping'
    //         ]);
    //     }]);

    //     $allocated_items = $query->get();


    //     $reportData = [];
    //     $rowIndex = 1;

    //     foreach ($allocated_items as $allocation) {
    //         foreach ($allocation->items as $item) {
    //             $reportData[] = [
    //                 'DT_RowIndex'    => $rowIndex++,
    //                 'item'           => ($item->asset_item?->item_code ?? '') . ' ' . ($item->asset_item?->name ?? ''),
    //                 'model'          => $item->item_model ?? '',
    //                 'serial_number'  => $item->serial_number ?? '',
    //                 'classification' => $item->asset_classification->name ?? '',
    //                 'category'       => $item->asset_category->name ?? '',
    //                 'type'           => $item->asset_type->name ?? '',
    //                 'vendor'         => $allocation->vendor->vendor_name ?? '',
    //             ];
    //         }
    //     }

    //     return response()->json(['data' => $reportData]);

    // }


    public function stockItemReport(Request $request)
    {
        $location_status = $request->input('location_status'); // all, allocated, in_store
        $classification  = $request->input('classification');
        $category        = $request->input('category');
        $type            = $request->input('type');
        $asset_item_id   = $request->input('asset_item_id');
        $vendor          = $request->input('vendor');

        $query = AssetMapping::with([
            'masteritem',
            'register_lineitem.asset_item',
            'register_lineitem.asset_classification',
            'register_lineitem.asset_category',
            'register_lineitem.asset_type',
            'register_lineitem.asset_register',
            'allocation_lineitems.employee',
            'allocation_lineitems.location'
        ]);

        // Apply location_status filter
        if ($location_status === 'allocated') {
            $query->where('allocation_status', '!=', 0);
        } elseif ($location_status === 'in_store') {
            $query->where('allocation_status', 0);
        }

        // Apply filters from register_lineitem relationship
        $query->whereHas('register_lineitem', function ($q) use ($classification, $category, $type, $asset_item_id, $vendor) {
            if (!empty($classification)) {
                $q->where('asset_classification_id', $classification);
            }

            if (!empty($category)) {
                $q->where('asset_category_id', $category);
            }

            if (!empty($type)) {
                $q->where('asset_type_id', $type);
            }

            if (!empty($asset_item_id)) {
                $q->where('asset_item_id', $asset_item_id);
            }

            if (!empty($vendor)) {
                $q->whereHas('asset_register', fn($qr) => $qr->where('vendor_id', $vendor));
            }
        });

        $mappings = $query->get();

        $reportData = [];
        $rowIndex = 1;
        foreach ($mappings as $mapping) {
            $line = $mapping->register_lineitem;

             // Prepare allocated users/locations list
            $allocatedUsers = [];
            foreach ($mapping->allocation_lineitems as $allocation) {
                if ($allocation->allocation_type === 'employee') {
                    $allocatedUsers[] = $allocation->employee->full_name ?? 'Unknown Employee';
                } elseif ($allocation->allocation_type === 'location') {
                    $allocatedUsers[] = $allocation->location->name ?? 'Unknown Location';
                }
            }
            $reportData[] = [
                'DT_RowIndex'    => $rowIndex++,
                'item'           => $line->asset_item?->name ?? '',
                'model'          => $mapping->model ?? '',
                'brand'          => $line->asset_brand ?? '',
                'serial_number'  => $mapping->serial_number ?? '',
                'asset_id'       => CustomHelper::itemCodeGenerater($mapping->id),
                'classification' => $line->asset_classification->name ?? '',
                'category'       => $line->asset_category->name ?? '',
                'type'           => $line->asset_type->name ?? '',
                'vendor'         => $line->asset_register->vendor->vendor_name ?? '',
                'allocation_status'=> $mapping->allocation_status == 1 ? 'Allocated' : 'Not Allocated',
                'user'          => implode(', ', $allocatedUsers)
            ];
        }

        return response()->json(['data' => $reportData]);
    }

    public function deleteAssetItem($id)
    {

        $mappings = AssetMapping::where('register_lineitem_id', $id)->where('allocation_status','!=',0)->first();
        if($mappings)
        {
            return response()->json([
            'message' => 'Cannot delete: Asset is mapped and allocation is active.',
            ], 400);
        }
        else
        {
            $delete_mapping = AssetMapping::findOrFail($id);
            if($delete_mapping)
            {
                $assetline  = AssetItemLine::where('id',$delete_mapping->register_lineitem_id)->first();

                if($assetline){

                    $assetline->asset_total = max(0, $assetline->asset_total - $assetline->asset_price);
                    $assetline->save();

                     $assetRegister = AssetRegister::find($assetline->asset_register_id); // Assuming asset_register_id is the relation field
                    if ($assetRegister) {

                        $assetRegister->total_amount = max(0, $assetRegister->total_amount - $assetline->asset_price);
                        $assetRegister->save();
                    }
                }

                $delete_mapping->delete();
                return response()->json(['message' => 'Asset deleted successfully']);
            }
            else
            {
                 return response()->json([
                'message' => 'Cannot delete: Asset is not exist.',
                ], 400);
            }

        }

    }

    public function edit_item($id)
    {
        $regline_id = AssetMapping::find($id);
        $register_id = AssetItemLine::find($regline_id->register_lineitem_id);
        $register = AssetRegister::with('items')->findOrFail($register_id->asset_register_id);
        $matchedItem = $register->items->firstWhere('id', $id);


        return response()->json([
            'register' => $register,
            'item' => $matchedItem,
            'mapping' => $regline_id
        ]);
    }

}
