<?php

namespace App\Http\Controllers;

use App\Models\AssetRegister;
use Illuminate\Http\Request;

use App\Models\AssetVendors;
use App\Models\AssetItemMaster;
use App\Models\AssetClassification;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\AssetItemLine;

class AssetRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
            'asset_number' => 'required|string',
            'company_name' => 'required|string',
            'asset_date' => 'required|date',
            'purchase_date' => 'required|date',
            'invoice_number' => 'required|string',
            'vendor_id' => 'required|exists:vendors,id',
            'remarks' => 'nullable|string',
            'upload_invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png',

            'asset_item_id' => 'required|array',
            'asset_model' => 'required|array',
            'asset_description' => 'required|array',
            'asset_classification_id' => 'required|array',
            'asset_category_id' => 'required|array',
            'asset_type_id' => 'required|array',
            'asset_quantity' => 'required|array',
            'asset_price' => 'required|array',
            'asset_total' => 'required|array',
            'serial_number' => 'required|array',
            'warranty' => 'required|array',
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
                'asset_date'     => $request->asset_date,
                'asset_number'   => $request->asset_number,
                'company_name'   => $request->company_name,
                'purchase_date'  => $request->purchase_date,
                'invoice_number' => $request->invoice_number,
                'vendor_id'      => $request->vendor_id,
                'total_amount'   => $totalAmount,
                'remarks'        => $request->remarks,
                'upload_invoice' => $filePath,
            ]
        );

        // Delete old item lines if editing
        $asset->itemLines()->delete();

        // Save asset item lines
        foreach ($request->asset_item_id as $index => $itemId) {
            $asset->itemLines()->create([
                'asset_item_id'           => $itemId,
                'item_model'              => $request->asset_model[$index],
                'asset_description'       => $request->asset_description[$index],
                'asset_classification_id' => $request->asset_classification_id[$index],
                'asset_category_id'       => $request->asset_category_id[$index],
                'asset_type_id'           => $request->asset_type_id[$index],
                'asset_quantity'          => $request->asset_quantity[$index],
                'asset_price'             => $request->asset_price[$index],
                'asset_total'             => $request->asset_total[$index],
                'serial_number'           => $request->serial_number[$index],
                'warranty'                => $request->warranty[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Asset register saved successfully.');
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
    public function edit(AssetRegister $assetRegister)
    {
        //
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
    public function destroy(AssetRegister $assetRegister)
    {
        //
    }
}
