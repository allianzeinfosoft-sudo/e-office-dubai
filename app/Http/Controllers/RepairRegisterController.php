<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\RepairRegister;
use App\Models\RepairItemLine;
use App\Models\AssetMapping;
use App\Models\AssetVendors;
use Illuminate\Http\Request;


class RepairRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        //

        if ($request->ajax()) {
            $repairItems = RepairItemLine::with(['register.vendor', 'item', 'assetMapping'])->get();

            $data = $repairItems->map(function ($item, $index) {
                return [
                    'DT_RowIndex'       => $index + 1,
                    'id'                => $item->id,
                    'register_id'       => optional($item->register)->id,
                    'repair_date'       => optional($item->register && $item->register->repair_date)
                                            ? date('d-m-Y', strtotime($item->register->repair_date)) . ' | '.  optional($item->register)->repair_no
                                            : '',
                    'item_code'         => optional($item->item)->item_code,
                    'item_name'         => optional($item->item)->name .' | '. optional($item->item)->brand,
                    'item_model'        => $item->item_model,
                    'serial_no'         => $item->serial_no,
                    'quantity'          => $item->quantity,
                    'unit'              => $item->unit,
                    'rate'              => $item->rate,
                    'actual_rate'       => $item->return_amount,
                    'amount'            => $item->amount,
                    'vendor_name'       => optional(optional($item->register)->vendor)->vendor_name,
                    'item_return_date'  => $item->repair_date ? date('d-m-Y', strtotime($item->repair_date)) : null,
                    'status'            => $item->repair_date == null ? 'Sent' : 'Received',
                    'remarks'           => $item->remarks,
                    'asset_mapping_id'  => optional($item->assetMapping)->id,
                    'asset_id_number'   => optional($item->assetMapping)->item_number,
                ];
            });

            return response()->json(['data' => $data]);
        }
        //
        $data['meta_title'] = 'Repair Register';
        return view('company-assets.repair-register.index', $data);
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
    public function store(Request $request){
        //
        $validated = $request->validate([
            'repair_no' => 'required',
            'repair_date' => 'required',
            'vendor_id' => 'required',
            'repair_item_id' => 'required|array',
        ]);

        $totalAmount = array_sum($request->rate);

        // Create or update main record
        $repairAsset = RepairRegister::updateOrCreate(
            ['id' => $request->id],
            [
                'repair_no'     => $request->repair_no,
                'repair_date'   => date('Y-m-d', strtotime($request->repair_date)),
                'vendor_id'     => $request->vendor_id,
                'status'        => 'sent',
                'return_date'   => $request->return_date ? date('Y-m-d', strtotime($request->return_date)) : null,
                'total_amount'  => $totalAmount,
                'remarks'       => $request->remarks,
            ]
        );

        $previousAssetMappingIds = $repairAsset->items()->pluck('asset_map_id')->toArray();
        AssetMapping::whereIn('id', $previousAssetMappingIds)->update(['repair_id' => null, 'allocation_status' => 0]);

        // Delete old item lines if editing
        $repairAsset->items()->delete();

        // Save asset item lines
        foreach ($request->repair_item_id as $index => $itemId) {
            $repairItemLine = $repairAsset->items()->create([
                'item_master_id'    => $itemId,
                'item_model'        => $request->asset_model[$index],
                'serial_no'         => $request->serial_no[$index],
                'asset_map_id'      => $request->asset_id[$index],
                // 'unit'              => $request->unit[$index],
                // 'quantity'          => $request->quantity[$index],
                'rate'              => $request->rate[$index],
                // 'amount'            => $request->amount[$index],
                'remarks'           => $request->remarks[$index],
            ]);

            if(!empty($request->asset_mapping_id[$index])) {
                AssetMapping::where('id', $request->asset_mapping_id[$index])
                    ->update(['repair_id' => $repairItemLine->id, 'allocation_status' => 2]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => $request->id ? 'Repair register updated successfully.' : 'Repair register saved successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(RepairRegister $repairRegister)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairRegister $repairRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairRegister $repairRegister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        // Fetch the repair item
        $repairItem = RepairItemLine::find($id);

        if (!$repairItem) {
            return response()->json([
                'success' => false,
                'message' => 'Repair item not found.'
            ], 404);
        }

        // Reset asset mapping fields
        AssetMapping::where('id', $repairItem->asset_map_id)
            ->update([
                'repair_id' => null,
                'allocation_status' => 0
            ]);

        // Check if this is the last item under the same register
        $itemCount = RepairItemLine::where('repair_register_id', $repairItem->repair_register_id)->count();

        // Delete repair register if this is the only item
        if ($itemCount <= 1) {
            RepairRegister::where('id', $repairItem->repair_register_id)->delete();
        }else{
            // Update total amount
            $totalAmount = RepairItemLine::where('repair_register_id', $repairItem->repair_register_id)->sum('amount');
            RepairRegister::where('id', $repairItem->repair_register_id)->update(['total_amount' => ($totalAmount - $repairItem->amount)]);
        }

        // Delete the repair item line
        $repairItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Repair register entry deleted successfully.'
        ]);
    }

    public function updateItem(Request $request) {
        $validated = $request->validate([
            'repair_date' => 'required|date',
            'return_amount' => 'required|numeric',
            'remarks' => 'required',
        ]);

        if ($request->id) {
            $item = RepairItemLine::find($request->id);
            $previousRemarks = $item->remarks;
            AssetMapping::where('id', $item->asset_map_id)->update(['repair_id' => null, 'allocation_status' => 0]);

            $item->update([
                'repair_date' => date('Y-m-d', strtotime($validated['repair_date'])),
                'return_amount' => $validated['return_amount'],
                'remarks' => $previousRemarks . ' | ' . $validated['remarks'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Repair item updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found or ID missing.',
        ]);
    }

    public function reportRepairItems() {
        $data['meta_title'] = 'Repair Items Report';
        $data['vendors'] = AssetVendors::all();
        return view('company-assets.reports.repair_item_report', $data);
    }



    public function repairItemsReport(Request $request){

        $vendorId   = $request->input('vendor_id');
        $fromDate   = $request->input('from_date');
        $toDate     = $request->input('to_date');
        $status     = $request->input('status');

        $reportItems = RepairItemLine::with(['item', 'register', 'assetMapping'])
        ->when($vendorId, function ($query) use ($vendorId) {
            $query->whereHas('register', function ($q) use ($vendorId) {
                $q->where('repair_registers.vendor_id', $vendorId); // add table name
            });
        })
        ->when($status, function ($query) use ($status) {
            $query->whereHas('register', function ($q) use ($status) {
                $q->where('repair_registers.status', $status); // add table name
            });
        })
        ->when($fromDate, function ($query) use ($fromDate) {
            $query->whereHas('register', function ($q) use ($fromDate) {
                $q->whereDate('repair_registers.repair_date', '>=', date('Y-m-d', strtotime($fromDate)));
            });
        })
        ->when($toDate, function ($query) use ($toDate) {
            $query->whereHas('register', function ($q) use ($toDate) {
                $q->whereDate('repair_registers.repair_date', '<=', date('Y-m-d', strtotime($toDate)));
            });
        })
        ->get();

        return response()->json([
            'data' => $reportItems->map(function ($item, $index) {
                return [
                    'DT_RowIndex' => $index + 1,
                    'scrap_no' => $item->register->repair_no ?? '',
                    'scrap_date' => optional($item->register)->repair_date ? date('d-m-Y', strtotime($item->register->repair_date)) : '',
                    'vendor_name' => $item->register->vendor->vendor_name ?? '',
                    'item_name' => $item->item->name ?? '',
                    'asset_code' => $item->assetMapping->item_number ?? '',
                    'item_model' => $item->item_model ?? '',
                    'serial_number' => $item->serial_no ?? '',
                    'unit' => $item->unit ?? '',
                    'quantity' => $item->quantity ?? 0,
                    'rate' => $item->rate ?? 0,
                    'amount' => $item->amount ?? 0,
                    'return_date' => $item->repair_date ? date('d-m-Y', strtotime($item->repair_date)) : '',
                    'return_amount' => $item->return_amount ?? '',
                    'remarks' => $item->remarks ?? '',
                ];
            }),
        ]);
    }


    public function getRepairAssetCode(Request $request)
    {

        $asset = AssetMapping::where('allocation_status',2)->where('master_item_id', $request->item_id)
        ->get()
        ->map(function ($assetCodes) {
            return [
                'id' => $assetCodes->id,
                'assetCode' => CustomHelper::itemCodeGenerater($assetCodes->id),
            ];
        });
        return response()->json($asset);
    }
}
