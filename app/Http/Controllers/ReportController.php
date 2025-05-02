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
        //
        if($request->ajax()) {
            $data['employees'] = Employee::all();
            return view('reports.user-overview.table', $data)->render();
        }
        $current_user = auth()->user()->id;
        
        $monthlyData = CustomHelper::getMonthlyTotalHours($current_user);

        $data['labels'] = $monthlyData['months'];
        $data['average_hours'] = $monthlyData['total_hours'];
        $data['work_analysis'] = CustomHelper::getWorkRatingAnalysis($current_user);
        $data['monthly_report'] = CustomHelper::getMonthlyWorkReport($current_user); 
        $data['meta_title']     = 'User Overview';
        $data['current_user']   = Employee::where('user_id', $current_user)->get()->first();
        $data['employees']      = Employee::all();
        return view('reports.user-overview.index', $data);
    }

    /* */
}
