<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\SarQuestion;
use App\Models\SarTemplate;
use App\Models\SarUserAssign;
use App\Models\SelfAppraisalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Ui\Presets\React;

class SarTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $sarTemplate = SarTemplate::get()
            ->map(function ($sarTemplate) {
                return [
                    'id' => $sarTemplate->id,
                    'template_name' => $sarTemplate->template_name ? $sarTemplate->template_name : '',
                    'department' => $sarTemplate->department_id ? $sarTemplate->department_info->department  : '',
                    'created_by' => $sarTemplate->created_by ? $sarTemplate->creator->full_name : '',
                    'created_date' => $sarTemplate->created_at->format('Y-m-d')
                ];
            });

            return response()->json([
                'data' => $sarTemplate
            ]);

        }

        $data['meta_title'] = 'SAR Questions Templates';
        return view('sar.index',  $data);
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
            $template = SarTemplate::findOrFail($request->id);

            $template->update([
                'template_name' => $request->template_name,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(), // optional: update on edit
            ]);

            // Delete old questions before adding new ones
            $template->questions()->delete();
        } else {
            // Create new SAR template
            $template = SarTemplate::create([
                'template_name' => $request->template_name,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(), // current logged-in user ID
            ]);
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

            $template->questions()->create([
                'question' => $q['question'],
                'answer_type' => $q['answer_type'],
                'options' => !empty($options) ? json_encode(array_values($options)) : null,
            ]);
        }

        return redirect()->back()->with('success', 'SAR Template saved successfully!');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }


    public function fetch($id)
    {
        $template = SarTemplate::with(['questions', 'department_info', 'creator'])->findOrFail($id);

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

    public function sarsfetch($id)
    {
        $sar = SarUserAssign::find($id);
        if($sar){
            $template = SarTemplate::with(['questions', 'department_info', 'creator'])->findOrFail($sar->template_id);
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
            return redirect()->back()->with('error', 'SAR not exist.');
        }

    }

    public function assign_template(Request $request)
    {

         if ($request->ajax()) {

            $sarUsers = SarUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->get()
                ->map(function ($sarUsers) {
                    return [
                        'id' => $sarUsers->id,
                        'template_name' => $sarUsers->template?->template_name ?? '',
                        'department' => $sarUsers->template?->department_info?->department ?? '',
                        'employees' => $sarUsers->employee?->full_name ?? '',
                        'sar_start_date' => $sarUsers->sar_start_date ?? '',
                        'sar_end_date' => $sarUsers->sar_end_date ?? '',
                        'created_by' => $sarUsers->assigned_user?->full_name ?? '',
                    ];
                });

            return response()->json([
                'data' => $sarUsers
            ]);
        }

        $data['meta_title'] = 'Assign SAR';
        return view('sar.assign-sar',  $data);
    }

    public function sar_edit()
    {

    }

    public function store_assign_template(Request $request)
    {

        $templateId = $request->input('template');
        $startDate = $request->input('sar_start_date');
        $endDate = $request->input('sar_end_date');
        $assignedBy = Auth::user()->id; // current user
        $status = 1; // 1 = pending
        $submitDate = null; // initially null

        foreach ($request->employee as $userId) {
            SarUserAssign::create([
                'user_id'        => $userId,
                'template_id'    => $templateId,
                'assigned_by'    => $assignedBy,
                'sar_start_date' => $startDate,
                'sar_end_date'   => $endDate,
                'sar_submit_date'=> $submitDate,
                'status'         => $status,
            ]);
        }

        return redirect()->back()->with('success', 'SAR template assigned successfully.');

    }

     public function getTemplates($departmentId)
    {
        $data['templates'] = SarTemplate::select('id','template_name')->where('department_id', $departmentId)->get();
        $data['employees'] = Employee::select('user_id','full_name')->where('department_id', $departmentId)->get();

        return response()->json($data);
    }

    public function destroyAssign($id)
    {
        try {
            $assign = SarUserAssign::findOrFail($id);
            $assign->delete();

            return response()->json(['success' => true, 'message' => 'Assignment deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete assignment.'], 500);
        }
    }


    public function user_sars(Request $request)
    {


         if ($request->ajax()) {
            $userId = Auth::user()->id;
            $sarUsers = SarUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->where('user_id',$userId)
                ->get()
                ->map(function ($sarUsers) {
                    return [
                        'id' => $sarUsers->id,
                        'template_name' => $sarUsers->template?->template_name ?? '',
                        'template_id' => $sarUsers->template_id ?? '',
                        'department' => $sarUsers->template?->department_info?->department ?? '',
                        'employees' => $sarUsers->employee?->full_name ?? '',
                        'sar_start_date' => $sarUsers->sar_start_date ?? '',
                        'sar_end_date' => $sarUsers->sar_end_date ?? '',
                        'status' => $sarUsers->status ?? '',
                        'created_by' => $sarUsers->assigned_user?->full_name ?? '',
                    ];
                });

            return response()->json([
                'data' => $sarUsers
            ]);
        }

        $data['meta_title'] = 'Assign SAR';
        return view('sar.user-sars',  $data);
    }

   public function sarAnswerfetch($id)
    {
        $sar = SarUserAssign::with([
            'employee',
            'template.department_info',
            'template.questions',
            'assigned_user',
        ])->findOrFail($id);

        // Get all answers for this SAR
        $answers = SelfAppraisalReport::where('sar_id', $id)->get();

        // Optionally join with questions for enriched info
        $answers = $answers->map(function ($answer) {
            $question = SarQuestion::find($answer->question);
            return [
                'question_id'   => $answer->question,
                'question_text' => $question?->question,
                // 'answer_type'   => $answer->answer_type,
                'answer'        => $answer->answer,
                'options'       => $question?->options,
            ];
        });

        return response()->json([
            'sar_info' => $sar,
            'answers'  => $answers,
        ]);
    }




}
