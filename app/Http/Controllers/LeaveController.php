<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('leave.summary');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('leave.apply');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $leaveData = [
            'user_id'     => $user_id,
            'leave_from'  => $request->leave_from,
            'leave_to'    => $request->leave_to,
            'reason'      => $request->reason,
            'leave_type'  => $request->leave_type,
            'leave_category' => $request->leave_category,
        ];

        $leave = Leave::create($leaveData);
        return redirect()->back()->with('success', 'Leave created successfully!');

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
