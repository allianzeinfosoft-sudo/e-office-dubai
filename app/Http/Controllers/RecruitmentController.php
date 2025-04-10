<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Recruitment;
use App\Models\Graduation;
use App\Models\MinimumQualification;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Project;
use App\Models\Skills;
use App\Models\KeyworsRrf;

use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        /* ajax request */
        if ($request->ajax()) {
            $recruitments = Recruitment::with(['project', 'interViewer', 'designation'])->get(); // eager load if necessary
    
            return response()->json([
                'success' => true,
                'message' => 'Recruitments fetched successfully',
                'data' => $recruitments->map(function ($result, $index) {
                    // Parse skillRequired
                    $skills = [];
                    if (!empty($result->skillRequired)) {
                        $skillIds = explode(',', $result->skillRequired);
                        $skills = Skills::whereIn('id', $skillIds)->get()->map(function ($skill) {
                            return [
                                'id' => $skill->id,
                                'skill_name' => $skill->name,
                            ];
                        });
                    }
    
                    // Parse keyword
                    $keywordsRrf = [];
                    if (!empty($result->keyword)) {
                        $keywordIds = explode(',', $result->keyword);
                        $keywordsRrf = KeyworsRrf::whereIn('id', $keywordIds)->get()->map(function ($keyword) {
                            return [
                                'id' => $keyword->id,
                                'keyword_name' => $keyword->name,
                            ];
                        });
                    }
    
                    return [
                        'row' => $index + 1, 
                        'id' => $result->id,
                        'rrfDate' => date('d-m-Y', strtotime($result->rrfDate)),
                        'jobTitle' => $result->jobTitle ?? '',
                        'projectName' => optional($result->project)->project_name ?? '',
                        'designation' => $result->designation->designation ?? '',
                        'createdAt' => $result->created_at->format('d-m-Y'),
                        'priority' => $result->priority ?? '',
                        'interviewer' => $result->interViewer->full_name ?? '',
                        'skills' => $skills,
                        'keywords' => $keywordsRrf,
                        'status' => $result->status ?? '',
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Recruitments';
        return view('recruitments.index', $data);
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
        $validated = $request->validate([
            'id' => 'nullable|integer', // Optional ID for updateOrCreate
            'empId' => 'required|integer',
            'rrfDate' => 'required|date',
            'branchId' => 'required|integer',
            'departmentId' => 'required|integer',
            'positionId' => 'required|integer',
            'projectId' => 'nullable|integer',
            'shiftId' => 'nullable|integer',
            'salaryRange' => 'nullable|string',
            'jobType' => 'nullable|string',
            'interviewer' => 'nullable|string',
            'sittingArragement' => 'nullable|string',
            'minimumQualification' => 'nullable|string',
            'skillRequired' => 'nullable|array',
            'experience' => 'nullable|string',
            'schoolingMedium' => 'nullable|string',
            'graduation' => 'nullable|string',
            'ageGroup' => 'nullable|string',
            'location' => 'nullable|string',
            'interviewPlace' => 'nullable|string',
            'priority' => 'nullable|string',
            'referralIncentive' => 'nullable|string',
            'requireToAndFroCharge' => 'nullable|string',
            'keyword' => 'nullable|array',
            'seekApproval' => 'nullable|integer',
            'jobTitle' => 'nullable|string',
            'jobDescription' => 'nullable|string',
            'remarks' => 'nullable|string',
            'noOfPersons' => 'required|integer',
        ]);

        // Clone validated data for modification
        $validatedData = $validated;

        // Format the rrfDate
        $validatedData['rrfDate'] = Carbon::parse($validated['rrfDate'])->format('Y-m-d');

        // Implode array fields
        $validatedData['skillRequired'] = isset($validated['skillRequired']) && is_array($validated['skillRequired']) 
            ? implode(',', $validated['skillRequired']) 
            : null;

        $validatedData['keyword'] = isset($validated['keyword']) && is_array($validated['keyword']) 
            ? implode(',', $validated['keyword']) 
            : null;

        $recruitment = Recruitment::updateOrCreate(
            ['id' => $validatedData['id'] ?? null],
            [
                'empId' => $validatedData['empId'] ?? '',
                'rrfDate' => $validatedData['rrfDate']?? '',
                'branchId' => $validatedData['branchId']?? '',
                'departmentId' => $validatedData['departmentId']?? '',
                'positionId' => $validatedData['positionId']?? '',
                'projectId' => $validatedData['projectId']?? '',
                'shiftId' => $validatedData['shiftId']?? 0,
                'salaryRange' => $validatedData['salaryRange']?? '',
                'jobType' => $validatedData['jobType']?? '',
                'interviewer' => $validatedData['interviewer']?? '',
                'sittingArragement' => $validatedData['sittingArragement']?? '',
                'minimumQualification' => $validatedData['minimumQualification']?? '',
                'skillRequired' => $validatedData['skillRequired']?? '',
                'experience' => $validatedData['experience']?? '',
                'schoolingMedium' => $validatedData['schoolingMedium']?? '',
                'graduation' => $validatedData['graduation']?? '',
                'ageGroup' => $validatedData['ageGroup']?? '',
                'location' => $validatedData['location']?? '',
                'interviewPlace' => $validatedData['interviewPlace']?? '',
                'priority' => $validatedData['priority']?? '',
                'referralIncentive' => $validatedData['referralIncentive']?? '',
                'requireToAndFroCharge' => $validatedData['requireToAndFroCharge']?? '',
                'keyword' => $validatedData['keyword']?? '',
                'seekApproval' => $validatedData['seekApproval']?? '',
                'jobTitle' => $validatedData['jobTitle']?? '',
                'jobDescription' => $validatedData['jobDescription']?? '',
                'remarks' => $validatedData['remarks']?? '',
                'noOfPersons' => $validatedData['noOfPersons']?? '',
            ]
        );

        return response()->json([
            'message' => 'Recruitment saved successfully!',
            'data' => $recruitment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recruitment $recruitment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recruitment $recruitment)
    {
        //
        $data['recruitment'] = $recruitment;
        $data['meta_title'] = 'Edit Recuitment';
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recruitment $recruitment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recruitment $recruitment)
    {
        //
        $recruitment->delete();
        return response()->json(['message' => 'Recruitment deleted successfully']);
    }

    /* Load Modal form html */

    public function loadModalFrom(Request $request){
        // $url = 'components.forms.'
        $formUrl = $request->formUrl;
        $elementId = $request->elementId;
        $data['formUrl'] = $formUrl;

        if($elementId == 'positionId' || $elementId == 'porjectId'){
            $data['departments'] = Department::all();
        }
    
        if (view()->exists('components.forms.'.$formUrl)) {
            $html = view('components.forms.'. $formUrl, $data)->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Form not found'
        ]);
    }

    /* Add grduation */
    public function storeGraduation(Request $request){
        $validated = $request->validate([
            'graduation' => 'required|string|max:255',
        ]);

        $data = Graduation::updateOrCreate(
            ['graduation' => $validated['graduation']], // search by name
            ['graduation' => $validated['graduation']]  // update if exists (or same)
        );

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Graduation saved successfully!',
        ]);
    
    }
    /* Add Minimum Qualification */
    public function storeMinimumQualification(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = MinimumQualification::updateOrCreate(
            ['name' => $validated['name']], // search by name
            ['name' => $validated['name']]  // update if exists (or same)
        );

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]);
    }
    /* Add Minimum Qualification */
    public function storePosition(Request $request){
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'department_id' => 'required',
        ]);

        $data = Designation::updateOrCreate(
            ['designation' => $validated['designation']], // search by name
            ['department_id' => $validated['department_id'], 'designation' => $validated['designation']]  // update if exists (or same)
        );

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]);
    }
    public function storeProject(Request $request){
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'department_id' => 'required',
        ]);

        $data = Project::updateOrCreate(
            ['project_name' => $validated['project_name']], // search by name
            ['department_id' => $validated['department_id'], 'project_name' => $validated['project_name']]  // update if exists (or same)
        );

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]);
    }

    /* Add Skills */
    public function storeSkills(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = Skills::updateOrCreate(
            ['name' => $validated['name']], // search by name
            ['name' => $validated['name']]  // update if exists (or same)
        );

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]);
    }

    /* Add Keywords */
    public function storeKeywords(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = KeyworsRrf::updateOrCreate(
            ['name' => $validated['name']], // search by name
            ['name' => $validated['name']]  // update if exists (or same)
        );

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]);
    }
}
