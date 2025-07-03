<?php

namespace App\Http\Controllers;

use App\Models\ScrapRegister;
use Illuminate\Http\Request;

class ScrapRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'Scrap Register';
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
    public function edit(ScrapRegister $scrapRegister)
    {
        //
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
    public function destroy(ScrapRegister $scrapRegister)
    {
        //
    }
}
