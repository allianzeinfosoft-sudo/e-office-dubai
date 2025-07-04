<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\AssetRegister;
use Illuminate\Http\Request;

use App\Models\AssetVendors;
use App\Models\AssetItemMaster;
use App\Models\AssetClassification;
use App\Models\AssetCategory;
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
}
