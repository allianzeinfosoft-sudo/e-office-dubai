<?php

namespace App\Http\Controllers;

use App\Exports\SurveyReportExport;
use App\Models\SurveyReport;
use App\Models\SurveyUserAssign;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SurveyReportController extends Controller
{


    public function survey_report(Request $request)
    {

        if ($request->ajax()) {

            $surveyUsers = SurveyUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->get()
                ->map(function ($surveyUsers) {
                    return [
                        'id' => $surveyUsers->id,
                        'survey_title' => $surveyUsers->template?->template_name ?? '',
                        'survey_id' => $surveyUsers->template_id ?? '',
                        'department' => $surveyUsers->template?->department_info?->department ?? '',
                        'employees' => $surveyUsers->employee?->full_name ?? '',
                        'survey_start_date' => $surveyUsers->feedback_start_date ?? '',
                        'survey_end_date' => $surveyUsers->feedback_end_date ?? '',
                        'created_by' => $surveyUsers->assigned_user?->full_name ?? '',
                        'status' => $surveyUsers->status ?? '',
                    ];
                });



            return response()->json([
                'data' => $surveyUsers
            ]);

        }

         $data['meta_title'] = 'Survey Report';
         return view('survey.survey_report',  $data);
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

    public function exportSurveyReport($surveyId)
    {
        return Excel::download(new SurveyReportExport($surveyId), 'survey_reports.xlsx');
    }

}
