<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Restrict access to admin role
    }

    public function index()
    { 
      return view('branch.index');
    }

    public function getBranches()
    {
        $branch = Branch::all()->map(function ($branch) {
            return [
                'id' => $branch->id,
                'branch' => $branch->name,
                 
            ];
        });

        $response = response()->json(['data' => $branch]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

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
}
