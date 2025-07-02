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
                    'assigned_user',
                    'feedback_report'
                ])
                ->get();
                 $grouped = $feedbackUsers->groupBy('feedback_code');

                  $reportData = $grouped->map(function ($group) {

                    $first = $group->first();

                    // Users who have SAR reports
                   $totalUsers = $group->count();

                    // Users who completed (assuming status = 1 or reports exist)
                    $attendedUsers = $group->filter(function ($assign) {
                        return  $assign->feedback_report->isNotEmpty();
                    })->count();

                    return [
                        'id' => $first->id,
                        'feedback_title' => $first->feedback?->feedback_title ?? '',
                        'feedback_name' => $first->feedback_name ?? '-',
                        'feedback_id' => $first->feedback_id ?? '',
                        'department' => $first->feedback?->department_info?->department ?? '',
                        'employees' => $first->employee?->full_name ?? '',
                        'feedback_start_date' => $first->feedback_start_date ?? '',
                        'feedback_end_date' => $first->feedback_end_date ?? '',
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
                'feedback_assign_id'      => $request->feedback_id,
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
