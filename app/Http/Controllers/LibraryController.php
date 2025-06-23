<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BooksCategory;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'E-Library';
        
        return view('library.index', $data);
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function books_categories(){
        //
        $data['meta_title'] = 'Books Categories';
        $data['categories'] = BooksCategory::all();
        return view('library.categories.index', $data);
    }

    public function store_category(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:50',
            'parent_id' => 'nullable|integer',
        ]);
        
        $category = BooksCategory::updateOrCreate(
            ['id' => $request->id], 
            ['name' => $request->name,
            'parent_id' => $request->parent_id]); 

        return response()->json([
           'message' => 'Category saved successfully!',
            'data' => $category
        ]);
    }
    public function edit_category(Request $request){
        $category = BooksCategory::find($request->id);
        return response()->json([
            'category' => $category
        ]);
    }
    
    public function delete_category(Request $request){
        $category = BooksCategory::find($request->id);
        $category->delete();   
    }
}
