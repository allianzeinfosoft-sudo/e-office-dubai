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
                ->select('id', 'asset_date', 'asset_number', 'company_name', 'purchase_date', 'invoice_number', 'vendor_id', 'total_amount', 'upload_invoice', 'remarks')
                ->get();

            $data = $asset->map(function($item, $index) {

                    return [
                        'DT_RowIndex'       => $index + 1,
                        'id'                => $item->id,
                        'asset_date'        => date('d-m-Y', strtotime($item->asset_date)), //$item->asset_date,
                        'asset_number'      => $item->asset_number,
                        'company_name'      => $item->company_name,
                        'purchase_date'     => date('d-m-Y', strtotime($item->purchase_date)), //$item->purchase_date,
                        'invoice_number'    => $item->invoice_number,
                        'vendor_name'         => $item->vendor->vendor_name,
                        'total_amount'      => $item->total_amount,
                        'upload_invoice'    => '<a href="' . asset('storage/' . $item->upload_invoice) . '" target="_blank"><i class="fa fa-file"></i></a>',
                        'remarks'           => $item->remarks
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
            'asset_number' => 'required',
            'company_name' => 'required|string',
            'purchase_date' => 'required',
            'invoice_number' => 'required',
            'vendor_id' => 'required',
            'remarks' => 'nullable',
            'upload_invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'asset_item_id' => 'required|array',
            'asset_quantity' => 'required|array',
            'asset_price' => 'required|array',
            'asset_total' => 'required|array',
        ]);

        // Upload invoice
        $filePath = null;
        if ($request->hasFile('upload_invoice')) {
            $filePath = $request->file('upload_invoice')->store('invoices', 'public');
        }



        $totalAmount = array_sum($request->asset_total);

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
                'remarks'        => $request->remarks,
                'upload_invoice' => $filePath,
            ]
        );

        // Get all line item IDs
        $lineItemIds = $asset->items()->pluck('id');

        // Delete related mappings
        AssetMapping::whereIn('register_lineitem_id', $lineItemIds)->delete();

        // Now delete line items
        $asset->items()->delete();

        // Save asset item lines
        foreach ($request->asset_item_id as $index => $itemId) {

            $LineItems = $asset->items()->create([
                'asset_item_id'           => $itemId,
                'item_model'              => $request->asset_model[$index],
                'asset_description'       => $request->asset_unit[$index],  // Forgot to add unit column so I added to this field
                'asset_classification_id' => $request->asset_classification_id[$index],
                'asset_category_id'       => $request->asset_category_id[$index],
                'asset_type_id'           => $request->asset_type_id[$index],
                'asset_quantity'          => $request->asset_quantity[$index],
                'asset_price'             => $request->asset_price[$index],
                'asset_total'             => $request->asset_total[$index],
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
    /**
     * Display the specified resource.
     */
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

    public function stockItemReport(Request $request){

        $location_status = $request->input('location_status'); // all, allocated, in_store
        $classification  = $request->input('classification');
        $category        = $request->input('category');
        $type            = $request->input('type');
        $asset_item_id   = $request->input('asset_item_id');
        $vendor          = $request->input('vendor');

        $query = AssetRegister::with(['vendor','items']);

        if (!empty($vendor)) {
            $query->where('vendor_id', $vendor);
        }

        $query->whereHas('items', function ($q) use ($asset_item_id, $classification, $category, $type) {

            if (!empty($classification)) {
                $q->where('asset_classification_id', $classification);
            }

            if (!empty($category)) {
                $q->where('asset_category_id', $category);
            }

            if (!empty($type)) {
                $q->where('asset_type_id', $type);
            }


            // Add location_status filter based on mapping

        })->with(['items' => function ($q) use ($location_status, $asset_item_id) {

            if ($location_status === 'allocated') {

                $q->whereDoesntHave('mapping', fn ($mq) => $mq->where('allocation_status', 0));

            } elseif ($location_status === 'in_store') {

                $q->whereHas('mapping', fn ($mq) => $mq->where('allocation_status', 0));
            }

            if (!empty($asset_item_id)) {

                $q->where('asset_item_id', $asset_item_id);
            }

            $q->with([
                'asset_item',
                'asset_classification',
                'asset_category',
                'asset_type',
                'mapping'
            ]);
        }]);

        $allocated_items = $query->get();


        $reportData = [];
        $rowIndex = 1;

        foreach ($allocated_items as $allocation) {
            foreach ($allocation->items as $item) {
                $reportData[] = [
                    'DT_RowIndex'    => $rowIndex++,
                    'item'           => ($item->asset_item?->item_code ?? '') . ' ' . ($item->asset_item?->name ?? ''),
                    'model'          => $item->item_model ?? '',
                    'serial_number'  => $item->serial_number ?? '',
                    'classification' => $item->asset_classification->name ?? '',
                    'category'       => $item->asset_category->name ?? '',
                    'type'           => $item->asset_type->name ?? '',
                    'vendor'         => $allocation->vendor->vendor_name ?? '',
                ];
            }
        }

        return response()->json(['data' => $reportData]);


    }
}
