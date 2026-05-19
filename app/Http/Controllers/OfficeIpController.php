<?php

namespace App\Http\Controllers;

use App\Models\OfficeIp;
use Illuminate\Http\Request;

class OfficeIpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $officeIps = OfficeIp::all();
        return view('office-ips.index', compact('officeIps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('office-ips.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:office_ips,ip_address',
            'status'     => 'required|boolean',
        ]);

        OfficeIp::create($request->only('ip_address', 'status'));

        return redirect()->route('office-ips.index')->with('success', 'Office IP added successfully.');
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
        $officeIp = OfficeIp::findOrFail($id);
        return view('office-ips.edit', compact('officeIp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $officeIp = OfficeIp::findOrFail($id);

        $request->validate([
            'ip_address' => 'required|ip|unique:office_ips,ip_address,' . $id,
            'status'     => 'required|boolean',
        ]);

        $officeIp->update($request->only('ip_address', 'status'));

        return redirect()->route('office-ips.index')->with('success', 'Office IP updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $officeIp = OfficeIp::findOrFail($id);
        $officeIp->delete();

        return redirect()->route('office-ips.index')->with('success', 'Office IP deleted successfully.');
    }
}
