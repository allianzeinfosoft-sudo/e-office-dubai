<?php

namespace App\Http\Controllers;

use App\Models\ProductivityTarget;
use Illuminate\Http\Request;

class ProductivityTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $productivityTargets = ProductivityTarget::with('project', 'projectTask', 'employee',)->get();
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'data' => $productivityTargets->map(function ($productivityTarget) {
                    return [
                        'id'            => $productivityTarget->id,
                        'projectName'   => $productivityTarget->project->project_name ?? '',
                        'projectTask'   => $productivityTarget->projectTask->tasks->name ?? '', 
                        'employee'      => $productivityTarget->employee->full_name ?? '',
                        'target_month'  => $productivityTarget->target_month ?? '',
                        'target_year'   => $productivityTarget->target_year ?? '',
                        'rph'           => $productivityTarget->rph ?? '',
                    ];
                }),
            ]);
        }

        //
        $data['meta_title'] = 'Productivity Targets';
        return view('productivity-target.index', $data);
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
        $request->validate([
            'project_id'        => 'required',
            'project_task_id'   => 'required',
            'assignedBy'        => 'required',
            'target_year'       => 'required',
            'rph'               => 'required',
        ]);

        // Create project
        $productivityTarget = ProductivityTarget::updateOrCreate(['id' => $request->id], [
            'project_id'         => $request->project_id,
            'project_task_id'   => $request->project_task_id,
            'assignedBy'        => $request->assignedBy,
            'target_month'      => date('m', strtotime($request->target_year)),
            'target_year'       => date('Y', strtotime($request->target_year)),
            'rph'               => $request->rph,
        ]);
        
        $message = $productivityTarget->wasRecentlyCreated ? 'Project created successfully' : 'Project updated successfully';
        return redirect()->route('productivity-target.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductivityTarget $productivityTarget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $target = ProductivityTarget::findOrFail($id);
        return response()->json($target);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductivityTarget $productivityTarget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        //
        $target = ProductivityTarget::find($id);
        if ($target) {
            $target->delete();
            return response()->json(['success' => true, 'message' => 'Productivity target deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Target not found.']);
    }
}
