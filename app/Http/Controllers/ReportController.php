<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Helpers\CustomHelper;


class ReportController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /* user overview */
    public function user_overview(Request $request) {
        $selected_user = $request->input('user') ?? auth()->id();
        $selected_year = $request->input('year'); // optional, pass to helpers
    
        // Monthly hours
        $monthlyData = CustomHelper::getMonthlyTotalHours($selected_user, $selected_year);
        $data['labels']        = $monthlyData['months'];
        $data['average_hours'] = $monthlyData['total_hours'];
    
        // Reports
        $data['work_analysis']  = CustomHelper::getWorkRatingAnalysis($selected_user, $selected_year);
        $data['monthly_report'] = CustomHelper::getMonthlyWorkReport($selected_user, $selected_year);
    
        // Attendance + Leave
        $data['attendance_analytics'] = CustomHelper::currentAttendanceAnalytics($selected_user, $selected_year);
        $data['leave_stats']          = CustomHelper::getEmployeeLeaveStats($selected_user, $selected_year);
    
        // Other info
        $data['current_user'] = Employee::where('user_id', $selected_user)->first();
        $data['employees']    = Employee::all();
        $data['meta_title']   = 'User Overview';
    
        return view('reports.user-overview.index', $data);
    }
    
    /* */
    public function monthlyOvenerview(Request $request) {
        $selected_year = $request->input('year'); // optional, pass to helpers
        $data['monthly_report'] = CustomHelper::getMonthlyWorkReport(null, $selected_year);
        $data['meta_title'] = 'Monthly Overview';
        return view('reports.monthly-overview.index', $data);
    }

}
