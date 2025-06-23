<?php

namespace App\Http\Controllers;

use App\Models\SurveyReport;
use App\Models\SurveyUserAssign;
use Illuminate\Http\Request;

class SurveyReportController extends Controller
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
            SurveyReport::create([
                'survey_id'      => $request->survey_id,
                'question'    => $entry['question_id'],
                'answer_type' => $entry['answer_type'],
                'answer'      => $entry['answer'] ?? null,
            ]);
        }

          $surveyAssign = SurveyUserAssign::find($request->survey_id);
            if ($surveyAssign) {
                $surveyAssign->status = 2; // Change this to your desired status value
                $surveyAssign->survey_submit_date = now(); // Optional: record submission date
                $surveyAssign->save();
            }



        return redirect()->back()->with('success', 'Survey Report submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyReport $surveyReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyReport $surveyReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyReport $surveyReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyReport $surveyReport)
    {
        //
    }
}
