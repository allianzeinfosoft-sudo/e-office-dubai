<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use App\Models\Policy;
use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if ($request->ajax()) {
            $group = strtolower($user->role);

            // ✅ 1. Special groups get all policies
            if (in_array($group, ['hr', 'developer', 'g1'])) {
                $policies = Policy::with(['department', 'project', 'role'])
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $userId = (string) $user->id;

                // ✅ 2. Get user project IDs from ProjectTask
                $userProjectIds = ProjectTask::where(function ($query) use ($userId) {
                    $query->where('members', 'LIKE', "%,$userId,%")
                        ->orWhere('members', 'LIKE', "$userId,%")
                        ->orWhere('members', 'LIKE', "%,$userId")
                        ->orWhere('members', '=', "$userId")
                        ->orWhere('reporting_to', $userId);
                })->pluck('project_id')->unique()->toArray();

                $userDepartmentId = $employee->department_id;

                // ✅ 3. Filter policies based on project or department logic
                $policies = Policy::with(['department', 'project', 'role'])
                    ->where(function ($query) use ($userProjectIds, $userDepartmentId) {
                        $query
                            // ✅ Global policy for all users
                            ->orWhere('department_id', 0)

                            // Case 1: project + department → must match project only
                            ->orWhere(function ($q) use ($userProjectIds) {
                                $q->whereNotNull('project_id')
                                    ->whereNotNull('department_id')
                                    ->whereIn('project_id', $userProjectIds);
                            })

                            // Case 2: only department (≠ 0) → match user department
                            ->orWhere(function ($q) use ($userDepartmentId) {
                                $q->whereNull('project_id')
                                    ->where('department_id', $userDepartmentId)
                                    ->where('department_id', '!=', 0);
                            })

                            // Case 3: only project → match user project
                            ->orWhere(function ($q) use ($userProjectIds) {
                                $q->whereNotNull('project_id')
                                    ->whereNull('department_id')
                                    ->whereIn('project_id', $userProjectIds);
                            });
                    })
                    ->orderBy('id', 'desc')
                    ->get();
            }

            // ✅ 4. Format response
            return response()->json([
                'success' => true,
                'message' => 'Policies fetched successfully',
                'data' => $policies->map(function ($policy, $index) {
                    return [
                        'row'               => $index + 1,
                        'id'                => $policy->id,
                        'policyTitle'       => $policy->policyTitle ?? '',
                        'descriptions'      => $policy->descriptions ?? '',
                        'policyStartDate'   => date('d-m-Y', strtotime($policy->policyStartDate)),
                        'pollicyEndDate'    => date('d-m-Y', strtotime($policy->pollicyEndDate)),
                        'department' => $policy->department_id == 0 ? 'General' : ($policy->department->department ?? 'N/A'),
                        'project'           => $policy->project->project_name ?? 'N/A',
                        'role'              => $policy->role->name ?? 'N/A',
                        'attachments'       => $policy->attachments ?? '',
                        'createdAt'         => $policy->created_at->format('d-m-Y'),
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Policies';
        return view('others.policies.index', $data);
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
    public function store(Request $request){


        // Validate input
        $validated = $request->validate([
            'id'                => 'nullable|integer',
            'policyTitle'       => 'required|string|max:255',
            // 'policyStartDate'   => 'required|date',
            // 'pollicyEndDate'    => 'required|date',
            'department_id'     => 'nullable|integer',
            'attachments'       => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:10240',
        ]);

        // Parse date fields
        $validatedData = $validated;
        // $validatedData['policyStartDate']   = Carbon::parse($validated['policyStartDate'])->format('Y-m-d');
        // $validatedData['pollicyEndDate']    = Carbon::parse($validated['pollicyEndDate'])->format('Y-m-d');

        // Handle file upload if present
        if ($request->hasFile('attachments')) {

            if ($request->has('id')) {
                $existingPolicy = Policy::find($request->id);
                if ($existingPolicy && $existingPolicy->attachments) {
                    $oldFile = 'public/policies/' . $existingPolicy->attachments;
                    if (Storage::exists($oldFile)) {
                        Storage::delete($oldFile);
                    }
                }
            }

            $file       = $request->file('attachments');
            $fileName   = uniqid('policy_', true) . '.' . $file->getClientOriginalExtension();
            $filePath   = $file->storeAs('public/policies', $fileName);
            $validatedData['attachments'] = $fileName;

        }elseif ($request->filled('id')) {

            $validatedData['attachments'] = Policy::find($request->id)->attachments ?? null;
        }

        // Store the policy or update existing one

        $policy = Policy::updateOrCreate(
            ['id' => $validatedData['id'] ?? null],
            [
                'policyTitle'       => $validatedData['policyTitle'] ?? '',
                // 'policyStartDate'   => $validatedData['policyStartDate'] ?? '',
                // 'pollicyEndDate'    => $validatedData['pollicyEndDate'] ?? '',
                'descriptions'      => $validatedData['descriptions'] ?? '',
                'department_id'     => $validatedData['department_id'] ?? null,
                'project_id'        => $request['project_id'] ?? null,
                // 'role_id'           => $request['role_id'] ?? null,
                'attachments'       => $validatedData['attachments'] ?? '',
            ]
        );

         $recipients = Employee::whereNotNull('user_id')->pluck('user_id')->toArray();

                    // $message = 'New Policy Released';
                    // createNotification([
                    //     'type' => 'policy',
                    //     'recipients' => $recipients,
                    //     'message' => $message,
                    // ]);

        return response()->json([
            'message' => 'Policy saved successfully!',
            'data' => $policy
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Policy $policy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Policy $policy)
    {
        $data['policy'] = $policy;
        $data['meta_title'] = 'Edit Policy';
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Policy $policy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Policy $policy){
        // Check if there is an attachment and if it exists in storage
        if ($policy->attachments && Storage::exists($policy->attachments)) {
            // Delete the file from storage
            Storage::delete($policy->attachments);
        }

        // Delete the policy from the database
        $policy->delete();

        return response()->json(['message' => 'Policy deleted successfully']);
    }

    public function getProjectsByDepartment(Request $request)
    {
        $departmentId = $request->get('department_id');
        $projects = Project::where('department_id', $departmentId)->select('id', 'project_name')->get();

        return response()->json(['projects' => $projects]);
    }
}
