<?php

namespace App\Http\Controllers;

use App\Models\QuickNote;
use Illuminate\Http\Request;

class QuickNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['meta_title'] = 'Quick Notes';
        return view('tools.quick-note.index', $data);
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
        $validateion = $request->validate([
            'title' => 'required',
            'note_description' => 'required',
        ]);

        QuickNote::updateOrCreate(
            [
                'id' => $request->id,
            ], [
                'title' => $request->title,
                'note_description' => $request->note_description,
                'assigned_to' => $request->assigned_to,
                'created_by' => Auth::user()->user_id
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(QuickNote $quickNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuickNote $quickNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuickNote $quickNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuickNote $quickNote)
    {
        //
    }
}
