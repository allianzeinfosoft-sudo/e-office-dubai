<?php

namespace App\Http\Controllers;

use App\Models\workReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $request->validate([
            'project_name'      => 'required',
            'type_of_work'      => 'required|string|max:255',
            'total_tasks'       => 'nullable|integer',
            'productivity_hour' => 'nullable|numeric',
            'time_of_work'      => 'nullable',
            'total_records'      => 'nullable',
            'comments'          => 'nullable|string',
            'emp_id'            => 'required|integer',
            'report_date'       => 'required|date',
        ]);
        $productivity_hour = is_numeric($request->productivity_hour) ? (int) $request->productivity_hour : 0;
        WorkReport::create([
            'username'          => Auth::user()->username,
            'emp_id'            => $request->emp_id,
            'project_name'      => $request->project_name,
            'type_of_work'      => $request->type_of_work,
            'time_of_work'      => $request->time_of_work,
            'total_time'        => $request->total_time,
            'comments'          => $request->comments,
            'report_date'       => $request->report_date,
            'total_records'     => $request->total_records,
            'productivity_hour' => $productivity_hour,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Work report submitted successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(workReport $workReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(workReport $workReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, workReport $workReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(workReport $workReport)
    {
        //
    }
}
