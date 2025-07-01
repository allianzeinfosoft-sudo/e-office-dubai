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
                    'assigned_user',
                    'survey_report',
                ])
                ->get();
                $grouped = $surveyUsers->groupBy('survey_code');

                 $reportData = $grouped->map(function ($group) {
                    $first = $group->first();

                    // Users who have SAR reports
                   $totalUsers = $group->count();

                    // Users who completed (assuming status = 1 or reports exist)
                    $attendedUsers = $group->filter(function ($assign) {
                        return  $assign->survey_report->isNotEmpty();
                    })->count();

                    return [
                        'id' => $first->id,
                        'survey_title' => $first->template?->template_name ?? '-',
                        'survey_name' => $first->survey_name ?? '-',
                        'survey_id' => $first->template_id ?? '',
                        'department' => $first->template?->department_info?->department ?? '',
                        'employees' => $first->employee?->full_name ?? '',
                        'survey_start_date' => $first->survey_start_date ?? '',
                        'survey_end_date' => $first->survey_end_date ?? '',
                        'created_by' => $first->assigned_user?->full_name ?? '',
                        'total_users' => $totalUsers,
                        'attended_users' => $attendedUsers,
                        'status' => $first->status ?? '',
                    ];
                })->values();



            return response()->json([
                'data' => $reportData
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
