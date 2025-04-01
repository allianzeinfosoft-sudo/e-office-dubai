<?php

namespace App\Http\Controllers;

use App\Models\ProjectTask;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $projectTasks = ProjectTask::with('project')->get();
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'data' => $projectTasks->map(function ($task) {
                    return [
                        'id' => $task->id,
                        'task_name' => $task->task_name, 
                        'project_name' => optional($task->project)->project_name,
                        'created_at' => date('d-m-Y', strtotime($task->created_at)),
                        'reporting_to' => $task->reporting_to,
                        'members' => $task->members,
                    ];
                }),
            ]);
        }

        //
        $data['meta_title'] = 'Project Tasks';
        return view('project-tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['meta_title'] = 'Create Task';
        $data['projects'] = Project::all();
        return view('project-tasks.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'task_name'     => 'required|string|max:255',
            'project_id'    => 'required',
            'reporting_to'  => 'nullable',
            'members' => 'nullable',
        ]);

        // Create project Task
        ProjectTask::create([
            'project_id'     => $request->project_id,
            'task_name'      => $request->task_name,
            'reporting_to'     => $request->reporting_to ?? null,
            'members' => isset($request->members) ? implode(',', $request->members) : null,
        ]);

        return redirect()->route('tasks-project.index')->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(projectTask $projectTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(projectTask $projectTask)
    {
        $data['projectTask'] = $projectTask;
        $data['meta_title'] = 'Edit Task';
        $data['projects'] = Project::all();
        return view('project-tasks.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id){
        // Validate the request data
        $validatedData = $request->validate([
            'task_name'      => 'required|string|max:255',
            'project_id'     => 'required|exists:projects,id', // Ensure project exists
            'pr_task_id'     => 'nullable',
            'pr_sub_task_id' => 'nullable',
        ]);

        // Find the project task
        $projectTask = ProjectTask::findOrFail($id);

        // Update the task details
        $projectTask->update($validatedData);

        return redirect()->route('tasks-project.index')->with('success', 'Project Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(projectTask $projectTask){
        // Delete the project
        $projectTask->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function getTasksByProject($project_id){

        $projectTasks = ProjectTask::where('project_id', $project_id)->get();

        return response()->json([
            'success' => true,
            'data' => $projectTasks,
        ]);
    }

    public function getMembers($employee_id){
        $members = Employee::where('reporting_to', $employee_id)->get();
        return response()->json([
            'success' => true,
            'data' => $members,
        ]);
    }
}
