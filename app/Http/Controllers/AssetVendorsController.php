<?php

namespace App\Http\Controllers;

use App\Models\AssetVendors;
use App\Models\VendorCategory;
use Illuminate\Http\Request;

class AssetVendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $vendors = AssetVendors::with('category')->select('id', 'vendor_code', 'vendor_name', 'vendor_category', 'contact_person', 'contact_number')->get();

            $data = $vendors->map(function ($item, $index) {
                return [
                    'DT_RowIndex'      => $index + 1,
                    'id'               => $item->id,
                    'vendor_code'      => $item->vendor_code,
                    'vendor_name'      => $item->vendor_name,
                    'vendor_category'  => $item->category->name ?? 'N/A',
                    'contact_person'   => $item->contact_person,
                    'contact_number'   => $item->contact_number,
                ];
            });

            return response()->json(['data' => $data]);
        }
        $data['meta_title'] = 'Vendors';
        return view('company-assets.settings.vendors.index', $data);
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
            'vendor_code' => 'required|string|max:50',
            'vendor_name' => 'required',
            'vendor_category' => 'required',
            'contact_person' => 'nullable',
            'contact_number' => 'nullable',
            'email' => 'nullable|email',
            'vendor_address' => 'nullable',
            'website' => 'nullable',
            'mobile_number' => 'nullable',
        ]);
        AssetVendors::updateOrcreate(
            [
                'id'=> $request->id
            ],
            $validated
        );

        return response()->json([
            'status' => 'success',
            'message' => $request->id ? 'Vendor updated successfully!' :  'Vendor saved successfully!',
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetVendors $assetVendors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $assetVendors)
    {
        $assetVendors = AssetVendors::with('category')->find($assetVendors);
        return response()->json([
            'status' => 'success',
            'data' => $assetVendors
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetVendors $assetVendors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($assetVendors)
    {
        //
        AssetVendors::find($assetVendors)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Vendor deleted successfully!',
        ]);
    }

    public function store_category(Request $request){
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category = VendorCategory::updateOrCreate(
            ['name' => $request->name],
            ['name' => $request->name]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Category saved successfully!',
            'data' => $category
        ]);
    }
}
