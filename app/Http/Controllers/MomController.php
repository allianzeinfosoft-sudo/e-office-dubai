<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Mom;
use App\Models\Employee;
use Illuminate\Http\Request;

class MomController extends Controller
{
    /**
     * Display a listing of the MOMs.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $moms = Mom::with('employee')->orderBy('id', 'desc')->get();
    
            return response()->json([
                'success' => true,
                'message' => 'MOMs fetched successfully',
                'data' => $moms->map(function ($mom, $index) {
    
                    $assignedTo = [];
    
                    if (!empty($mom->assigned_to)) {
                        $empIds = explode(',', $mom->assigned_to);
                        $employees = Employee::whereIn('user_id', $empIds)->get();
                        $assignedTo = $employees->map(function ($emp) {
                            return [
                                'id' => $emp->user_id,
                                'full_name' => $emp->full_name,
                            ];
                        });
                    }
    
                    return [
                        'row' => $index + 1,
                        'id' => $mom->id,
                        'mom_title' => $mom->mom_title ?? '',
                        'mom_date' => date('d-m-Y', strtotime($mom->mom_date)),
                        'created_by' => $mom->employee->full_name ?? '',
                        'assigned_to' => $assignedTo ?? '',
                        'attachments' => $mom->attachments ?? '',
                        'status' => $mom->status,
                        'created_at' => date('d-m-Y', strtotime($mom->created_at)), //optional($mom->created_at)->format('d-m-Y'),
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Minutes of Meeting';
        return view('others.moms.index', $data);
    }

    /**
     * Store a newly created or updated MOM.
     */
    public function store(Request $request){

        $validated = $request->validate([
            'id' => 'nullable|integer',
            'mom_title' => 'required|string|max:255',
            'mom_date' => 'required|date',
            'created_by' => 'required|integer',
            'assigned_to' => 'nullable',
            'mom_details' => 'nullable|string',
            'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc,xlsx,xls', // secure allowed types
        ]);

        // Convert assigned_to array to string for DB storage
        $validated['assigned_to'] = isset($validated['assigned_to']) ? implode(',', $validated['assigned_to']) : null;

        // Format the date to Y-m-d
        $validated['mom_date'] = Carbon::parse($validated['mom_date'])->format('Y-m-d');

        // Handle file upload
        if ($request->hasFile('attachments')) {

            // Delete old file if editing
            if ($request->filled('id')) {
                $existingMom = Mom::find($request->id);
                if ($existingMom && $existingMom->attachments) {
                    $oldFile = 'public/moms/' . $existingMom->attachments;
                    if (\Storage::exists($oldFile)) {
                        \Storage::delete($oldFile);
                    }
                }
            }
            $file = $request->file('attachments');
            $fileName = uniqid('mom_', true) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/moms', $fileName);
            $validated['attachments'] = $fileName;

        } elseif ($request->filled('id')) {
            // Keep old attachments if no new file uploaded
            $existingMom = Mom::find($request->id);
            $validated['attachments'] = $existingMom->attachments ?? null;
        }

        // Create or update MOM record
        $mom = Mom::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            $validated
        );

        return response()->json([
            'message' => 'MOM saved successfully!',
            'data' => $mom
        ]);
    }

    /**
     * Show the form for editing the specified MOM.
     */
    public function edit(Mom $mom)
    {
        $data['mom'] = $mom;
        $data['meta_title'] = 'Edit MOM';
        return response()->json($data);
    }

    /**
     * Remove the specified MOM from storage.
     */
    public function destroy(Mom $mom)
    {
        $mom->delete();
        return response()->json(['message' => 'MOM deleted successfully']);
    }
}
