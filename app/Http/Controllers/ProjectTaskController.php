<?php

namespace App\Http\Controllers;

use App\Models\ProjectTask;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Tasks;
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
            $projectTasks = ProjectTask::with('project', 'employee', 'tasks')->get();
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'data' => $projectTasks->map(function ($task) {
                    $memberIds = explode(',', $task->members);

                    $members = Employee::whereIn('user_id', $memberIds)->get()->map(function ($member) {
                        return [
                            'id' => $member->id,
                            'full_name' => $member->full_name,
                            'profile_image' => $member->profile_image  ? $member->profile_image  : 'default.png',
                        ];
                    });

                    return [
                        'id' => $task->id,
                        'task_name' => $task->tasks->name ?? '', 
                        'project_name' => optional($task->project)->project_name,
                        'created_at' => date('d-m-Y', strtotime($task->created_at)),
                        'reporting_to' => $task->employee ?? '',
                        'members' => $members,
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
        $validated = $request->validate([
            'id'            => 'nullable|integer|exists:project_tasks,id',
            'task_name'     => 'required|string|max:255',
            'project_id'    => 'required|integer|exists:projects,id',
            'reporting_to'  => 'nullable|integer|exists:users,id',
            'members'       => 'nullable',
        ]);

        // Find the project
        $project = Project::findOrFail($validated['project_id']);

        // If 'all' is selected, get all employee user_ids in the department

        if (in_array('all', $request->members)) {
            $members = Employee::where('department_id', $project->department_id)->pluck('user_id')->toArray();
        } else {
            $members = is_array($request->members) ? $request->members : [];
        }

        // Create or update the project task
        $task = ProjectTask::updateOrCreate(
            ['id' => $request->id],
            [
                'project_id'   => $validated['project_id'],
                'task_name'    => $validated['task_name'],
                'reporting_to' => $validated['reporting_to'] ?? null,
                'members'      => !empty($members) ? implode(',', $members) : null,
            ]
        );

        $message = $task->wasRecentlyCreated ? 'Project task created successfully' : 'Project task updated successfully';

        return redirect()->route('tasks-project.index')->with('success', $message);
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
    public function edit(projectTask $projectTask){
        $data['projectTask'] = $projectTask;
        $data['meta_title'] = 'Edit Task';
        $data['projects'] = Project::all();
        //return view('project-tasks.edit', $data);
        return response()->json($data);
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
        $projectTasks = ProjectTask::with('tasks')->where('project_id', $project_id)->get();
        return response()->json([
            'success' => true,
            'data' => $projectTasks,
        ]);
    }

    public function getMembers($employee_id){
        /* employee_id = project_id Updated for department wise emloyees on 02-06-2025 */
        $project = Project::find($employee_id);
        $members = Employee::where('department_id', $project->department_id)->get();
        return response()->json([
            'success' => true,
            'data' => $members,
        ]);
    }

    public function storeTaskName(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $task = Tasks::updateOrCreate(['name' => $validated['name']],[
            'name'      => $validated['name'],
        ]);

        return response()->json([
            'success' => "New task created successfully",
            'data' => $task,
        ]);
    }
}
