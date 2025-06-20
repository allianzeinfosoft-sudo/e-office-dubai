<?php

namespace App\Http\Controllers;

use App\Models\SarQuestion;
use App\Models\SarUserAssign;
use App\Models\SelfAppraisalReport;
use Illuminate\Http\Request;

class SelfAppraisalReportController extends Controller
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
            SelfAppraisalReport::create([
                'sar_id'      => $request->sar_id,
                'question'    => $entry['question_id'],
                'mark'        => $entry['mark'],
                'comment'     => $entry['comment'],
            ]);
        }

          $sarAssign = SarUserAssign::find($request->sar_id);
            if ($sarAssign) {
                $sarAssign->status = 2; // Change this to your desired status value
                $sarAssign->sar_submit_date = now(); // Optional: record submission date
                $sarAssign->total_score = $request->total_score ?? 0;
                $sarAssign->maximum_score = $request->maximum_score ?? 0;
                $sarAssign->score_percentage = $request->percentage ?? 0;
                $sarAssign->grade = $request->grade ?? '';
                $sarAssign->save();
            }



        return redirect()->back()->with('success', 'Self Appraisal submitted successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(SelfAppraisalReport $selfAppraisalReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SelfAppraisalReport $selfAppraisalReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SelfAppraisalReport $selfAppraisalReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SelfAppraisalReport $selfAppraisalReport)
    {
        //
    }
}
