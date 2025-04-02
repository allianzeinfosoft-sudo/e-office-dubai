<?php

namespace App\Http\Controllers;

use App\Models\ProductivityTarget;
use Illuminate\Http\Request;

class ProductivityTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'Productivity Targets';
        return view('productivity-target.index', $data);
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
    public function show(ProductivityTarget $productivityTarget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductivityTarget $productivityTarget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductivityTarget $productivityTarget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductivityTarget $productivityTarget)
    {
        //
    }
}
