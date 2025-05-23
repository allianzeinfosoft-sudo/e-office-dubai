<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $policies = Policy::orderBy('id', 'desc')->get();

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
                        'department'         => $policy->department->department ?? '',
                        'role'              => $policy->role->name ?? '',
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
            'policyStartDate'   => 'required|date',
            'pollicyEndDate'    => 'required|date',
            'department_id'      => 'nullable|integer',
            'role_id'           => 'nullable|integer',
            'descriptions'      => 'nullable|string',
            'attachments'       => 'nullable|file|mimes:pdf,docx,jpg,jpeg,png|max:10240', // Assuming max 10MB files
        ]);

        // Parse date fields
        $validatedData = $validated;
        $validatedData['policyStartDate']   = Carbon::parse($validated['policyStartDate'])->format('Y-m-d');
        $validatedData['pollicyEndDate']    = Carbon::parse($validated['pollicyEndDate'])->format('Y-m-d');

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
            // Keep old attachment if no new file is uploaded
            $validatedData['attachments'] = Policy::find($request->id)->attachments ?? null;
        }

        // Store the policy or update existing one
        $policy = Policy::updateOrCreate(
            ['id' => $validatedData['id'] ?? null],
            [
                'policyTitle'       => $validatedData['policyTitle'] ?? '',
                'policyStartDate'   => $validatedData['policyStartDate'] ?? '',
                'pollicyEndDate'    => $validatedData['pollicyEndDate'] ?? '',
                'descriptions'      => $validatedData['descriptions'] ?? '',
                'department_id'      => $validatedData['department_id'] ?? '',
                'role_id'           => $validatedData['role_id'] ?? '',
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
}
