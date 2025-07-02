<?php

namespace App\Http\Controllers;

use App\Exports\SarExport;
use App\Models\SarQuestion;
use App\Models\SarUserAssign;
use App\Models\SelfAppraisalReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SelfAppraisalReportController extends Controller
{

    public function sar_report(Request $request)
    {
        if ($request->ajax()) {

            $sarUsers = SarUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user',
                    'sar_report'
                ])
                ->get();
                $grouped = $sarUsers->groupBy('sar_code');
                 // ->map(function ($sarUsers) {
                    $reportData = $grouped->map(function ($group) {
                    $first = $group->first();

                    // Users who have SAR reports
                    $totalUsers = $group->count();

                    // Users who completed (assuming status = 1 or reports exist)
                    $attendedUsers = $group->filter(function ($assign) {
                        return  $assign->sar_report->isNotEmpty();
                    })->count();

                    return [
                        'id' => $first->id,
                        'sar_title' => $first->template?->template_name ?? '',
                        'sar_name' => $first->sar_name ?? '-',
                        'sar_id' => $first->template_id ?? '',
                        'department' => $first->template?->department_info?->department ?? '',
                        'employees' => $first->employee?->full_name ?? '',
                        'sar_start_date' => $first->sar_start_date ?? '',
                        'sar_end_date' => $first->sar_end_date ?? '',
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

         $data['meta_title'] = 'Sar Report';
         return view('sar.sar_report',  $data);
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

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

     public function exportSarReport($sarId)
    {
        return Excel::download(new SarExport($sarId), 'sar_reports.xlsx');
    }
}
