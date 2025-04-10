<?php

namespace App\Http\Controllers;

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
    public function index()
    {
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
    public function store(Request $request)
    {
        //
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
