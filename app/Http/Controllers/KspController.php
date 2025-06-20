<?php

namespace App\Http\Controllers;

use App\Models\Ksp;
use App\Models\KspCategory;
use Illuminate\Http\Request;

class KspController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $data['ksps'] = Ksp::with('category', 'createdBy')->get();
        $data['meta_title'] = 'KSP';
        return view('tools.ksp.index', $data);
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

        $validated = $request->validate([
            'id' => 'nullable|integer',
            'ksp_title' => 'required|string|max:255',
            'ksp_category' => 'required|integer',
            'created_by' => 'required|integer',
            'ksp_description' => 'nullable|string',
            'ksp_featured_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc,xlsx,xls', // secure allowed types
        ]);

        // Handle file upload
        if ($request->hasFile('ksp_featured_image')) {

            // Delete old file if editing
            if ($request->filled('id')) {
                $existingKsp = Ksp::find($request->id);
                if ($existingKsp && $existingKsp->ksp_featured_image) {
                    $oldFile = 'public/ksps/' . $existingKsp->ksp_featured_image;
                    if (\Storage::exists($oldFile)) {
                        \Storage::delete($oldFile);
                    }
                }
            }
            $file = $request->file('ksp_featured_image');
            $fileName = uniqid('ksp_', true) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/ksps', $fileName);
            $validated['ksp_featured_image'] = $fileName;

        } elseif ($request->filled('id')) {
            // Keep old attachments if no new file uploaded
            $existingsKsp = Ksp::find($request->id);
            $validated['ksp_featured_image'] = $existingsKsp->ksp_featured_image ?? null;
        }

        // Create or update MOM record
        $ksp = Ksp::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            $validated
        );

        return response()->json([
            'message' => 'KSP saved successfully!',
            'data' => $ksp
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ksp $ksp)
    {
        //
        $ksp->load('category', 'createdBy');
        $data['ksp'] = $ksp;
        $data['meta_title'] = 'View KSP';
        $html = view('tools.ksp.show', $data)->render();
        return response()->json([
            'message' => 'KSP fetched successfully!',
            'html' => $html
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ksp $ksp)
    {
        //
         $data['ksp'] = $ksp;
        $data['meta_title'] = 'Edit KSP';
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ksp $ksp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ksp $ksp)
    {
        //
        $ksp->delete();
        return response()->json([
           'message' => 'KSP deleted successfully!'
        ]);
    }

    public function store_category(Request $request){
        $request->validate([
            'category_name' => 'required|string|max:50',
        ]);
        $kspCategory = KspCategory::updateOrCreate(['category_name' => $request->category_name], ['category_name' => $request->category_name]); 
        return response()->json([
           'message' => 'KSP Category saved successfully!',
            'data' => $kspCategory
        ]);
    }
}
