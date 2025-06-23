<?php

namespace App\Http\Controllers;

use App\Exports\FeedbackReportExport;
use App\Models\Feedback;
use App\Models\FeedbackAssign;
use App\Models\FeedbackReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Svg\Tag\Rect;

class FeedbackReportController extends Controller
{

    public function feedback_report(Request $request)
    {

        if ($request->ajax()) {

            $feedbackUsers = FeedbackAssign::with([
                    'feedback.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->get()
                ->map(function ($feedbackUsers) {
                    return [
                        'id' => $feedbackUsers->id,
                        'feedback_title' => $feedbackUsers->feedback?->feedback_title ?? '',
                        'feedback_id' => $feedbackUsers->feedback_id ?? '',
                        'department' => $feedbackUsers->feedback?->department_info?->department ?? '',
                        'employees' => $feedbackUsers->employee?->full_name ?? '',
                        'feedback_start_date' => $feedbackUsers->feedback_start_date ?? '',
                        'feedback_end_date' => $feedbackUsers->feedback_end_date ?? '',
                        'created_by' => $feedbackUsers->assigned_user?->full_name ?? '',
                        'status' => $feedbackUsers->status ?? '',
                    ];
                });

            return response()->json([
                'data' => $feedbackUsers
            ]);

        }

         $data['meta_title'] = 'Feedback Report';
         return view('feedback.feedback_report',  $data);
    }


    public function exportFeedbackReport($feedbackId)
    {
        return Excel::download(new FeedbackReportExport($feedbackId), 'feedback_report.xlsx');
    }


   public function store(Request $request)
    {

        foreach ($request->answers as $entry) {
            FeedbackReport::create([
                'feedback_id'      => $request->feedback_id,
                'question'    => $entry['question_id'],
                'mark'        => $entry['mark'],
                'comment'     => $entry['comment'],
            ]);
        }

          $feedbackAssign = FeedbackAssign::find($request->feedback_id);
            if ($feedbackAssign) {
                $feedbackAssign->status = 2; // Change this to your desired status value
                $feedbackAssign->feedback_submit_date = now(); // Optional: record submission date
                $feedbackAssign->total_score = $request->total_score ?? 0;
                $feedbackAssign->maximum_score = $request->maximum_score ?? 0;
                $feedbackAssign->score_percentage = $request->percentage ?? 0;
                $feedbackAssign->grade = $request->grade ?? '';
                $feedbackAssign->save();
            }



        return redirect()->back()->with('success', 'Feedback Report submitted successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(FeedbackReport $feedbackReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeedbackReport $feedbackReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeedbackReport $feedbackReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeedbackReport $feedbackReport)
    {
        //
    }
}
