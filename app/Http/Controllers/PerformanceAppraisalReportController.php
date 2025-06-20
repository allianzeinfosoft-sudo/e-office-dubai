<?php

namespace App\Http\Controllers;

use App\Models\ParUserAssign;
use App\Models\PerformanceAppraisalReport;
use Illuminate\Http\Request;

class PerformanceAppraisalReportController extends Controller
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
    public function store(Request $request)
    {


        foreach ($request->answers as $entry) {
            PerformanceAppraisalReport::create([
                'par_id'      => $request->par_id,
                'question'    => $entry['question_id'],
                'mark'        => $entry['mark'],
                'comment'     => $entry['comment'],
            ]);
        }

          $parAssign = ParUserAssign::find($request->par_id);
            if ($parAssign) {
                $parAssign->status = 2; // Change this to your desired status value
                $parAssign->par_submit_date = now(); // Optional: record submission date
                $parAssign->total_score = $request->total_score ?? 0;
                $parAssign->maximum_score = $request->maximum_score ?? 0;
                $parAssign->score_percentage = $request->percentage ?? 0;
                $parAssign->grade = $request->grade ?? '';
                $parAssign->save();
            }
        return redirect()->back()->with('success', 'Performance Appraisal submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }
}
