<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        // Regular page view
        $data['meta_title'] = 'Projects';
        return view('projects.list', $data);
    }

    public function getProject(Request $request){
        $projects = Project::with('department', 'user')->get();
            return response()->json([
                'success' => true,
                'message' => 'Projects fetched successfully',
                'data' => $projects->map(function ($project) {
                    return [
                        'id'              => $project->id,
                        'project_name'    => $project->project_name,
                        'department_name' => optional($project->department)->department ?? '',
                        'user_name'       => optional($project->user)->username ?? '',
                        'start_date'      => $project->start_date,
                        'end_date'        => $project->end_date,
                    ];
                }),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        $data['meta_title'] = 'Create Project';
        $data['users'] = User::all();
        $data['departments'] = Department::all();
        return view('projects.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'project_name'      => 'required|string|max:255',
            'project_add_person'=> 'required|exists:users,id',
            'department_id'     => 'required|exists:departments,id',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'total_hours'       => 'nullable',
            'total_day'         => 'nullable',
        ]);

        // Create project
        Project::create([
            'project_name'      => $request->project_name,
            'project_add_person'=> $request->project_add_person,
            'department_id'     => $request->department_id,
            'start_date'        => Carbon::parse($request->start_date)->setTimeFrom(Carbon::now()),
            'end_date'          => Carbon::parse($request->end_date)->setTimeFrom(Carbon::now()),
            'total_hours'       => $request->total_hours,
            'total_day'         => $request->total_day,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(project $project){
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(project $project){
        $data['project'] = $project;
        $data['meta_title'] = 'Edit Project';
        $data['users'] = User::all();
        $data['departments'] = Department::all();
        return response()->json($data);
        //return view('projects.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $request->validate([
            'project_name'      => 'required|string|max:255',
            'project_add_person'=> 'required',
            'department_id'     => 'required',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'total_hours'       => 'nullable',
            'total_day'         => 'nullable',
        ]);

        // Find the project
        $project = Project::findOrFail($id);

        // Update project data
        $project->update([
            'project_name'       => $request->project_name,
            'project_add_person' => $request->project_add_person,
            'department_id'      => $request->department_id,
            'start_date'         => Carbon::parse($request->start_date)->setTimeFrom(Carbon::now()),
            'end_date'           => Carbon::parse($request->end_date)->setTimeFrom(Carbon::now()),
            'total_hours'        => is_numeric($request->total_hours) ? $request->total_hours : 0.00,
            'total_day'          => is_numeric($request->total_day) ? $request->total_day : 0,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($projectId){
        // Find the project
        $project = Project::findOrFail($projectId);

        // Delete the project
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
