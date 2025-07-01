<?php

namespace App\Http\Controllers;

use App\Exports\ParExport;
use App\Models\ParUserAssign;
use App\Models\PerformanceAppraisalReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PerformanceAppraisalReportController extends Controller
{

    public function par_report(Request $request)
    {
        if ($request->ajax()) {

            $parUsers = ParUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user',
                    'par_report'
                ])
                ->get();
                $grouped = $parUsers->groupBy('par_code');

                $reportData = $grouped->map(function ($group) {
                     $first = $group->first();

                    // Users who have PAR reports
                   $totalUsers = $group->count();

                    // Users who completed (assuming status = 1 or reports exist)
                    $attendedUsers = $group->filter(function ($assign) {
                        return  $assign->par_report->isNotEmpty();
                    })->count();

                    return [
                        'id' => $first->id,
                        'par_title' => $first->template?->template_name ?? '',
                        'par_name' => $first->par_name ?? '',
                        'par_id' => $first->template_id ?? '',
                        'department' => $first->template?->department_info?->department ?? '',
                        'employees' => $first->employee?->full_name ?? '',
                        'par_start_date' => $first->par_start_date ?? '',
                        'par_end_date' => $first->par_end_date ?? '',
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

         $data['meta_title'] = 'Par Report';
         return view('par.par_report',  $data);
    }


    public function create()
    {
        //
    }


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


    public function show(PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }

    public function edit(PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }

    public function update(Request $request, PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }

    public function destroy(PerformanceAppraisalReport $performanceAppraisalReport)
    {
        //
    }
    public function exportParReport($parId)
    {
        return Excel::download(new ParExport($parId), 'par_reports.xlsx');
    }
}
