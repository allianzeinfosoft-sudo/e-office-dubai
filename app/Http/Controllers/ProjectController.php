<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Project;
use App\Models\ProjectTask;
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
            'id'                  => 'nullable|integer|exists:projects,id',
            'project_name'        => 'required|string',
            'project_add_person'  => 'required|integer|exists:users,id',
            'department_id'       => 'required|integer|exists:departments,id',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            'total_hours'         => 'nullable|numeric',
            'total_day'           => 'nullable|numeric',
            'task_name'           => 'nullable|array',
            'task_name.*'         => 'required|string',
            'reporting_to'        => 'nullable|integer|exists:users,id',
            'members'             => 'nullable|array',
            'members.*'           => 'nullable|integer|exists:users,id',
        ]);

        // Create or Update Project
        $project = Project::updateOrCreate(
            ['id' => $request->id], // condition
            [
                'project_name'        => $request->project_name,
                'project_add_person'  => $request->project_add_person,
                'department_id'       => $request->department_id,
                'start_date'          => $request->start_date,
                'end_date'            => $request->end_date,
                'total_hours'         => $request->total_hours ?? 0,
                'total_day'           => $request->total_day ?? 0,
            ]
        );

        // Remove old tasks if updating (optional cleanup)
        if ($request->id) {
            ProjectTask::where('project_id', $project->id)->delete();
        }

        // Create new tasks
        if (!empty($request->task_name)) {
            foreach ($request->task_name as $taskName) {
                ProjectTask::create([
                    'project_id'   => $project->id,
                    'task_name'    => $taskName,
                    'reporting_to' => $request->reporting_to ?? null,
                    'members'      => !empty($request->members) ? implode(',', $request->members) : null,
                ]);
            }
        }

        $message = $request->id ? 'Project updated successfully.' : 'Project created successfully.';

        return redirect()->route('projects.index')->with('success', $message);
    }

    /* public function store(Request $request){
        $request->validate([
            'project_name'      => 'required',
            'project_add_person'=> 'required',
            'department_id'     => 'required',
            'start_date'        => 'nullable',
            'end_date'          => 'nullable',
            'total_hours'       => 'nullable',
            'total_day'         => 'nullable',
        ]);

        // Create project
        Project::create([
            'project_name'      => $request->project_name,
            'project_add_person'=> $request->project_add_person,
            'department_id'     => $request->department_id,
            'start_date'        => $request->start_date ? Carbon::parse($request->start_date) : null,
            'end_date'          => $request->end_date ? Carbon::parse($request->end_date) : null,
            'total_hours' => is_numeric($request->total_hours) ? $request->total_hours : 0,
            'total_day'   => is_numeric($request->total_day) ? $request->total_day : 0,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    } */

    /**
     * Display the specified resource.
     */
    public function show(project $project){
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project){
        $data['project'] = $project;
        $data['meta_title'] = 'Edit Project';
        $data['users'] = User::all();
        $data['departments'] = Department::all();

        // Get all task names under this project
        $data['tasks'] = $project->tasks()->pluck('task_name')->toArray();

        // Get the first reporting_to value from project's tasks
        $data['reporting_to'] = $project->tasks()->value('reporting_to');

        // Get unique member IDs from comma-separated strings
        $memberIds = $project->tasks()->pluck('members')->flatMap(function ($memberString) {
            return array_map('trim', explode(',', $memberString));
        })->unique()->values()->toArray();

        $data['members'] = $memberIds;

        return response()->json($data);
        // return view('projects.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        $request->validate([
            'project_name'      => 'required',
            'project_add_person'=> 'required',
            'department_id'     => 'required',
            'start_date'        => 'nullable',
            'end_date'          => 'nullable',
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
