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
use Illuminate\Support\Facades\Auth;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        /* ajax request */
        if ($request->ajax()) {
            if(Auth::user()->role == 'Developer' || Auth::user()->role == 'HR' || Auth::user()->role == 'G1')
            {
                $recruitments = Recruitment::with(['project', 'interViewer', 'designation'])->where('draft_status', 0)->orderBy('id', 'desc')->get();
            }
            else
            {
                $recruitments = Recruitment::with(['project', 'interViewer', 'designation'])->where('draft_status', 0)->where('created_by',Auth::user()->id)->orderBy('id', 'desc')->get();
            }
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
                    $priority_colors = [
                        '0' => 'dark',
                        '1' => 'danger',
                        '2' => 'info',
                        '3' => 'primary',
                    ];

                    return [
                        'row' => $index + 1,
                        'id' => $result->id,
                        'rrfDate' => date('d-m-Y', strtotime($result->rrfDate)),
                        'jobTitle' => $result->jobTitle ?? '',
                        'projectName' => optional($result->project)->project_name ?? '',
                        'designation' => $result->designation->designation ?? '',
                        'createdAt' => $result->created_at->format('d-m-Y'),
                        'priority' => $result->priority ? '<span class="badge bg-'.$priority_colors[$result->priority].'">' . config('optionsData.priority')[$result->priority] . '</span>' : '',
                        'interviewer' => $result->interViewer->full_name ?? '',
                        'status' => $result->status ?? '',
                        'approval_status' => $result->approval_status ?? '',
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
            'id' => 'nullable',
            'created_by' => 'required',
            'empId' => 'required',
            'rrfDate' => 'required|date',
            'branchId' => 'required',
            'departmentId' => 'required',
            'positionId' => 'required',
            'projectId' => 'nullable',
            'shiftId' => 'nullable',
            'salaryRange' => 'nullable',
            'jobType' => 'nullable',
            'interviewer' => 'nullable',
            'sittingArragement' => 'nullable',
            'minimumQualification' => 'nullable',
            'skillRequired' => 'nullable',
            'experience' => 'nullable',
            'schoolingMedium' => 'nullable',
            'graduation' => 'nullable',
            'ageGroup' => 'nullable',
            'location' => 'nullable',
            'interviewPlace' => 'nullable',
            'priority' => 'nullable',
            'referralIncentive' => 'nullable',
            'requireToAndFroCharge' => 'nullable',
            'keyword' => 'nullable',
            'seekApproval' => 'nullable',
            'jobTitle' => 'nullable',
            'jobDescription' => 'nullable',
            'remarks' => 'nullable',
            'noOfPersons' => 'required',
        ]);

        // Format the rrfDate
        $validated['rrfDate'] = Carbon::parse($validated['rrfDate'])->format('Y-m-d');

        // Implode arrays
        $validated['skillRequired'] = isset($validated['skillRequired']) ? implode(',', $validated['skillRequired']) : null;
        $validated['keyword'] = isset($validated['keyword']) ? implode(',', $validated['keyword']) : null;

        // Convert empty string fields to null (especially integer ones)
        $nullableToNull = [
            'projectId',
            'shiftId',
            'seekApproval',
            'jobType',
            'interviewer',
            'sittingArragement',
            'minimumQualification',
            'seekApproval',
            'priority',
            'referralIncentive',
            'requireToAndFroCharge', // ← Add this line
        ];
        foreach ($nullableToNull as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $recruitment = Recruitment::updateOrCreate(
            ['id' => $validated['id'] ?? null],
            array_merge($validated, [
                'draft_status' => $request->draft_status ?? 0,
            ])
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
        $data['meta_title'] = 'Show Recuitment';
        $data['recruitment'] = $recruitment;
        return view('recruitments.show', $data);
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

        return redirect()->route('recruitments.index')->with('success', 'Graduation saved successfully!');
        /* return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Graduation saved successfully!',
        ]); */

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
        return redirect()->route('recruitments.index')->with('success', 'Minimum Qualification saved successfully!');

        /* return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]); */
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

        return redirect()->route('recruitments.index')->with('success', 'New position saved successfully!');

        /* return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]); */
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
    public function storeSkills(Request $request)
    {
        $validated = $request->validate([
            'skill_name' => 'required|string|max:255',
        ]);

        $skill = Skills::updateOrCreate(
            ['name' => $validated['skill_name']], // match existing by name
            ['name' => $validated['skill_name']]  // same value updated (optional here)
        );
        return redirect()->route('recruitments.index')->with('success', 'Skill saved successfully!');

        /* return response()->json([
            'success' => true,
            'data' => $skill,
            'message' => 'Skill saved successfully!',
        ]); */
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
        return redirect()->route('recruitments.index')->with('success', 'Keywords saved successfully!');
        /* return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Minimum Qualification saved successfully!',
        ]); */
    }

    public function draftList(Request $request){

        if ($request->ajax()) {
            $recruitments = Recruitment::with(['project', 'interViewer', 'designation'])->where(['draft_status' => 1])->orderBy('id', 'desc')->get();

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
                    $priority_colors = [
                        '0' => 'dark',
                        '1' => 'danger',
                        '2' => 'info',
                        '3' => 'primary',
                    ];

                    return [
                        'row' => $index + 1,
                        'id' => $result->id,
                        'rrfDate' => date('d-m-Y', strtotime($result->rrfDate)),
                        'jobTitle' => $result->jobTitle ?? '',
                        'projectName' => optional($result->project)->project_name ?? '',
                        'designation' => $result->designation->designation ?? '',
                        'createdAt' => $result->created_at->format('d-m-Y'),
                        'priority' => $result->priority ? '<span class="badge bg-'.$priority_colors[$result->priority].'">' . config('optionsData.priority')[$result->priority] . '</span>' : '',
                        'interviewer' => $result->interViewer->full_name ?? '',
                        'skills' => $skills,
                        'keywords' => $keywordsRrf,
                        'status' => $result->status ?? '',
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'Draft Recruitments';
        return view('recruitments.drafts', $data);

    }

    public function updateStatus(Request $request){

        $request->validate([
            'id' => 'required|exists:recruitments,id',
            'status' => 'required|integer',
            'status_reason' => 'nullable|string'
        ]);

        $recruitment = Recruitment::find($request->id);
        $recruitment->status = $request->status;
        $recruitment->status_reason = $request->status_reason;
        $recruitment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $recruitment->status
        ]);
    }

    public function rrf_approvals(Request $request){

        $approver_id = Auth::user()->id;
        if ($request->ajax()) {
            $recruitments = Recruitment::with(['project', 'interViewer', 'designation'])
                ->where('approval_status', 0)
                ->where('seekApproval',$approver_id)
                ->orderByDesc('id')
                ->get();

            $priorityColors = [
                '0' => 'dark',
                '1' => 'danger',
                '2' => 'info',
                '3' => 'primary',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Recruitments fetched successfully',
                'data' => $recruitments->map(function ($result, $index) use ($priorityColors) {
                    return [
                        'row'           => $index + 1,
                        'id'            => $result->id,
                        'rrfDate'       => optional($result->rrfDate) ? date('d-m-Y', strtotime($result->rrfDate)) : '',
                        'jobTitle'      => $result->jobTitle ?? '',
                        'projectName'   => optional($result->project)->project_name ?? '',
                        'designation'   => optional($result->designation)->designation ?? '',
                        'createdAt'     => optional($result->created_at)->format('d-m-Y'),
                        'priority'      => isset($result->priority)
                            ? '<span class="badge bg-' . ($priorityColors[$result->priority] ?? 'secondary') . '">' .
                                (config('optionsData.priority')[$result->priority] ?? 'Unknown') .
                            '</span>'
                            : '',
                        'interviewer'   => optional($result->interViewer)->full_name ?? '',
                        'status'        => $result->status ?? '',
                    ];
                }),
            ]);
        }

        $data['meta_title'] = 'RRF Approvals';
        return view('recruitments.rrf_approvals', $data);
    }

    public function rrf_approve($rrfTd){
        $recruitment = Recruitment::find($rrfTd);
        $recruitment->approval_status = 1;
        $recruitment->approval_reason = "Approved by " . Auth::user()->full_name . ' on '. date('d-m-Y');
        $recruitment->save();
        return redirect()->route('recruitments.rrf-approvals')->with('success', 'RRF Approved Successfully');
    }

    public function reject(Request $request){

        $request->validate([
            'rrf_id' => 'required',
            'reason' => 'required',
        ]);

        $recruitment = Recruitment::find($request->rrf_id);
        $recruitment->approval_status = 2;
        $recruitment->approval_reason = $request->reason;
        $recruitment->save();

        return response()->json(['success' => true]);
    }
}
