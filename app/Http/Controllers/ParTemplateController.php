<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ParQuestion;
use App\Models\ParTemplate;
use App\Models\ParUserAssign;
use App\Models\PerformanceAppraisalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParTemplateController extends Controller
{

    public function index(Request $request)
    {
         /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $parTemplate = ParTemplate::get()
            ->map(function ($parTemplate) {
                return [
                    'id' => $parTemplate->id,
                    'template_name' => $parTemplate->template_name ? $parTemplate->template_name : '',
                    'department' => $parTemplate->department_id ? $parTemplate->department_info->department  : '',
                    'created_by' => $parTemplate->created_by ? $parTemplate->creator->full_name : '',
                    'created_date' => $parTemplate->created_at->format('Y-m-d')
                ];
            });

            return response()->json([
                'data' => $parTemplate
            ]);

        }

        $data['meta_title'] = 'PAR Questions Templates';
        return view('par.partemplate-index',  $data);
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
        $validated = $request->validate([
            'template_name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answer_type' => 'required|in:yes_no,optional,description',
        ]);

        // If editing, update the existing template
        if ($request->filled('id')) {
            $template = ParTemplate::findOrFail($request->id);

            $template->update([
                'template_name' => $request->template_name,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(), // optional: update on edit
            ]);

            // Delete old questions before adding new ones
            $template->questions()->delete();
        } else {
            // Create new PAR template
            $template = ParTemplate::create([
                'template_name' => $request->template_name,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(),
            ]);
        }

        // Ensure template was created successfully
        if (!$template || !$template->id) {
            return back()->with('error', 'Failed to create PAR template.');
        }

        // Store each question under the template
        foreach ($request->questions as $q) {


            $options = [];

            if ($q['answer_type'] === 'optional') {
                $options = array_filter([
                    $q['option1'] ?? null,
                    $q['option2'] ?? null,
                    $q['option3'] ?? null,
                    $q['option4'] ?? null,
                ]);
            }

            // Use model directly to avoid relationship issues
            ParQuestion::create([
                'template_id' => $template->id,
                'question' => $q['question'],
                'answer_type' => $q['answer_type'],
                'options' => !empty($options) ? json_encode(array_values($options)) : null,
            ]);
        }

        return redirect()->back()->with('success', 'PAR Template saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(ParTemplate $parTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParTemplate $parTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParTemplate $parTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParTemplate $parTemplate)
    {
        //
    }

    public function fetch($id)
    {
        $template = ParTemplate::with(['questions', 'department_info', 'creator'])->findOrFail($id);

        return response()->json([
            'template_name' => $template->template_name,
            'department' => optional($template->department_info)->department,
            'created_by' => optional($template->creator)->full_name,
            'questions' => $template->questions->map(function ($q) {
                return [
                    'question' => $q->question,
                    'answer_type' => $q->answer_type, // Needed for rendering inputs
                    'options' => json_decode($q->options ?? '[]') // if using custom options
                ];
            }),
        ]);
    }

     public function parsfetch($id)
    {
        $par = ParUserAssign::find($id);
        if($par){
            $template = ParTemplate::with(['questions', 'department_info', 'creator'])->findOrFail($par->template_id);
            return response()->json([
                'template_name' => $template->template_name,
                'department' => optional($template->department_info)->department,
                'created_by' => optional($template->creator)->full_name,
                'questions' => $template->questions->map(function ($q) {
                    return [
                        'question' => $q->question,
                        'question_id' => $q->id,
                        'answer_type' => $q->answer_type, // Needed for rendering inputs
                        'options' => json_decode($q->options ?? '[]') // if using custom options
                    ];
                }),
            ]);
        }else{
            return redirect()->back()->with('error', 'PAR not exist.');
        }

    }

    public function assign_template(Request $request)
    {

         if ($request->ajax()) {

            $parUsers = ParUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->get()
                ->map(function ($parUsers) {
                    return [
                        'id' => $parUsers->id,
                        'template_name' => $parUsers->template?->template_name ?? '',
                        'department' => $parUsers->template?->department_info?->department ?? '',
                        'employees' => $parUsers->employee?->full_name ?? '',
                        'par_start_date' => $parUsers->par_start_date ?? '',
                        'par_end_date' => $parUsers->par_end_date ?? '',
                        'created_by' => $parUsers->assigned_user?->full_name ?? '',
                    ];
                });

            return response()->json([
                'data' => $parUsers
            ]);
        }

        $data['meta_title'] = 'Assign PAR';
        return view('par.par-assign',  $data);
    }

//     public function sar_edit()
//     {

//     }

    public function store_assign_template(Request $request)
    {

        $templateId = $request->input('template');
        $startDate = $request->input('par_start_date');
        $endDate = $request->input('par_end_date');
        $assignedBy = Auth::user()->id; // current user
        $status = 1; // 1 = pending
        $submitDate = null; // initially null

        foreach ($request->employee as $userId) {
            ParUserAssign::create([
                'user_id'        => $userId,
                'template_id'    => $templateId,
                'assigned_by'    => $assignedBy,
                'par_start_date' => $startDate,
                'par_end_date'   => $endDate,
                'par_submit_date'=> $submitDate,
                'status'         => $status,
            ]);
        }

        return redirect()->back()->with('success', 'PAR template assigned successfully.');

    }

     public function getTemplates($departmentId)
    {
        $data['templates'] = ParTemplate::select('id','template_name')->where('department_id', $departmentId)->get();
        $data['employees'] = Employee::select('user_id','full_name')->where('department_id', $departmentId)->get();

        return response()->json($data);
    }

    public function destroyAssign($id)
    {
        try {
            $assign = ParUserAssign::findOrFail($id);
            $assign->delete();

            return response()->json(['success' => true, 'message' => 'Assignment deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete assignment.'], 500);
        }
    }


    public function user_pars(Request $request)
    {

         if ($request->ajax()) {
            $userId = Auth::user()->id;
            $parUsers = ParUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->where('user_id',$userId)
                ->get()
                ->map(function ($parUsers) {
                    return [
                        'id' => $parUsers->id,
                        'template_name' => $parUsers->template?->template_name ?? '',
                        'template_id' => $parUsers->template_id ?? '',
                        'department' => $parUsers->template?->department_info?->department ?? '',
                        'employees' => $parUsers->employee?->full_name ?? '',
                        'par_start_date' => $parUsers->par_start_date ?? '',
                        'par_end_date' => $parUsers->par_end_date ?? '',
                        'status' => $parUsers->status ?? '',
                        'created_by' => $parUsers->assigned_user?->full_name ?? '',
                    ];
                });

            return response()->json([
                'data' => $parUsers
            ]);
        }

        $data['meta_title'] = 'PAR`S';
        return view('par.user-pars',  $data);
    }

   public function parAnswerfetch($id)
    {
        $par = ParUserAssign::with([
            'employee',
            'template.department_info',
            'template.questions',
            'assigned_user',
        ])->findOrFail($id);

        // Get all answers for this SAR
        $answers = PerformanceAppraisalReport::where('par_id', $id)->get();

        // Optionally join with questions for enriched info
        $answers = $answers->map(function ($answer) {
            $question = ParQuestion::find($answer->question);
            return [
                'question_id'   => $answer->question,
                'question_text' => $question?->question,
                // 'answer_type'   => $answer->answer_type,
                'answer'        => $answer->answer,
                'options'       => $question?->options,
            ];
        });

        return response()->json([
            'par_info' => $par,
            'answers'  => $answers,
        ]);
    }
}
