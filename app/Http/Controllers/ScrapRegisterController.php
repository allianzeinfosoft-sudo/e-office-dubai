<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\AssetItemLine;
use App\Models\ScrapRegister;
use App\Models\AssetItemMaster;
use App\Models\AssetMapping;
use App\Models\AssetVendors;
use App\Models\ScrapItemLine;
use Illuminate\Http\Request;

class ScrapRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()) {
            $scraps = ScrapRegister::with('vendor')
                ->select('id', 'scrap_no', 'scrap_date', 'scrap_vendor_id', 'total_weight', 'total_amount', 'remarks')
                ->get();

            $data = $scraps->map(function($item, $index) {

                    return [
                        'DT_RowIndex'   => $index + 1,
                        'id'            => $item->id,
                        'scrap_no'      => $item->scrap_no,
                        'asset_mapping_id' => $item->asset_mapping_id,
                        'scrap_date'    => date('d-m-Y', strtotime($item->scrap_date)), //$item->asset_date,
                        'vendor_name'   => $item->vendor->vendor_name,
                        'total_weight'  => $item->total_weight,
                        'total_amount'  => $item->total_amount,
                        'remarks'       => $item->remarks
                    ];

                });

            return response()->json(['data' => $data]);
        }

        $data['meta_title'] = 'Scrap Register';
        $data['items'] = AssetItemMaster::get();
        $data['vendors'] = AssetVendors::all();
        $data['assetItems'] = AssetItemMaster::all();
        return view('company-assets.scrap-register.index', $data);
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
        //
        $validated = $request->validate([
            'scrap_no' => 'required',
            'scrap_date' => 'required',
            'total_weight' => 'required',
            'scrap_vendor_id' => 'required',
            'total_amount' => 'required',
            'remarks' => 'nullable',

            'scrap_item_id' => 'required|array',
            'quantity' => 'required|array',
            'rate' => 'required|array',
            'amount' => 'required|array',
        ]);

        $totalAmount = array_sum($request->amount);

        // Create or update main record
        $asset = ScrapRegister::updateOrCreate(
            ['id' => $request->id],
            [
                'scrap_no'   => $request->scrap_no,
                'scrap_date'     => date('Y-m-d', strtotime($request->scrap_date)),
                'total_weight' => $request->total_weight,
                'scrap_vendor_id'   => $request->scrap_vendor_id,
                'total_amount'   => $totalAmount,
                'remarks'        => $request->remarks,
            ]
        );

        $previousAssetMappingIds = $asset->items()->pluck('asset_mapping_id')->toArray();
        AssetMapping::whereIn('id', $previousAssetMappingIds)->update(['scrap_id' => null, 'allocation_status' => 0]);

        // Delete old item lines if editing
        $asset->items()->delete();

        // Save asset item lines
        foreach ($request->scrap_item_id as $index => $itemId) {
            $scrapItemLine = $asset->items()->create([
                'scrap_item_id'     => $itemId,
                'model'             => $request->model[$index],
                'serial_no'         => $request->serial_no[$index],
                'asset_mapping_id'  => $request->asset_id[$index],
                'unit'              => $request->unit[$index],
                'quantity'          => $request->quantity[$index],
                'rate'              => $request->rate[$index],
                'amount'            => $request->amount[$index],
                'remarks'           => $request->remarks[$index],
            ]);

            if(!empty($request->asset_id[$index])) {
                AssetMapping::where('id', $request->asset_id[$index])
                    ->update(['scrap_id' => $scrapItemLine->id, 'allocation_status' => 3]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Scrap register updated successfully.' : 'Scrap register saved successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ScrapRegister $scrapRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $scrapRegister = ScrapRegister::with(['items', 'mapping'])->findOrFail($id);
        return response()->json([
            'data' => $scrapRegister
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScrapRegister $scrapRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $scrapRegister = ScrapRegister::findOrFail($id);
        $scrapRegister->items()->delete();
        $scrapRegister->delete();
        return response()->json([
            'success' => true,
            'message' => 'Scrap register deleted successfully.',
        ]);

    }

    /* get item serial number */
    public function getItemSerial(Request $request){
        $serials = AssetItemLine::where('asset_item_id', $request->item_id)
        ->when($request->item_model, function ($query, $itemModel) {
            $query->where('item_model', $itemModel);
        })
        ->pluck('serial_number')
        ->toArray();

        return response()->json([
            'success' => true,
            'data' => $serials
        ]);
    }

    public function getItemModel(Request $request){
        $models = AssetItemLine::where('asset_item_id', $request->item_id)
        ->pluck('item_model')
        ->toArray();
        return response()->json([
            'success' => true,
            'data' => $models
        ]);
    }

   public function getAssetId(Request $request)
    {
        $assets = AssetMapping::with('masteritem')
            ->where('master_item_id', $request->item_id)
            ->when($request->item_model, function ($query) use ($request) {
                return $query->where('model', $request->item_model);
            })
            ->when($request->serial_no, function ($query) use ($request) {
                return $query->where('serial_number', $request->serial_no);
            })
            ->where('allocation_status', 0)
            ->get();

        // Format with item_code
        $data = $assets->map(function ($asset) {
            return [
                'id'           => $asset->id,
                'asset_id'    => optional($asset->masteritem)->item_code.'-'.$asset->item_number,
                'item_number'  => $asset->item_number,
                'model'        => $asset->model,
                'serial_no'    => $asset->serial_number,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function reportScrapItems(){
        $data['meta_title'] = 'Scrap Items Report';
        $data['vendors'] = AssetVendors::all();
        $data['scraps'] = ScrapRegister::all();
        return view('company-assets.reports.scrap_item_report', $data);
    }
    public function scrapItemsReport(Request $request){

        $vendorId = $request->input('vendor_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $scrapItems = ScrapItemLine::with(['item', 'register', 'mapping'])
        ->when($vendorId, function ($query) use ($vendorId) {
            $query->whereHas('register', function ($q) use ($vendorId) {
                $q->where('scrap_registers.scrap_vendor_id', $vendorId); // add table name
            });
        })
        ->when($fromDate, function ($query) use ($fromDate) {
            $query->whereHas('register', function ($q) use ($fromDate) {
                $q->whereDate('scrap_registers.scrap_date', '>=', date('Y-m-d', strtotime($fromDate)));
            });
        })
        ->when($toDate, function ($query) use ($toDate) {
            $query->whereHas('register', function ($q) use ($toDate) {
                $q->whereDate('scrap_registers.scrap_date', '<=', date('Y-m-d', strtotime($toDate)));
            });
        })
        ->get();

        return response()->json([
            'data' => $scrapItems->map(function ($item, $index) {
                return [
                    'DT_RowIndex' => $index + 1,
                    'scrap_no' => $item->register->scrap_no ?? '',
                    'scrap_date' => optional($item->register)->scrap_date ? date('d-m-Y', strtotime($item->register->scrap_date)) : '',
                    'vendor_name' => $item->register->vendor->vendor_name ?? '',
                    'item_name' => $item->item->name ?? '',
                    'asset_code' => CustomHelper::itemCodeGenerater($item->mapping->id),
                    'item_model' => $item->model ?? '',
                    'serial_number' => $item->serial_no ?? '',
                    'unit' => $item->unit ?? '',
                    'quantity' => $item->quantity ?? 0,
                    'rate' => $item->rate ?? 0,
                    'amount' => $item->amount ?? 0,
                    'remarks' => $item->remarks ?? '',
                ];
            }),
        ]);
    }
}
