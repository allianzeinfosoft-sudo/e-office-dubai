<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SurveyQuestion;
use App\Models\SurveyReport;
use App\Models\SurveyTemplate;
use App\Models\SurveyUserAssign;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyTemplateController extends Controller
{
    public function index(Request $request)
    {
         /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $surveyTemplate = SurveyTemplate::get()
            ->map(function ($surveyTemplate) {
                return [
                    'id' => $surveyTemplate->id,
                    'template_name' => $surveyTemplate->template_name ? $surveyTemplate->template_name : '',
                    'description'   => $surveyTemplate->description ? $surveyTemplate->description : '',
                    'department'    => $surveyTemplate->department_id ? $surveyTemplate->department_info->department  : '',
                    'created_by'    => $surveyTemplate->created_by ? $surveyTemplate->creator->full_name : '',
                    'created_date'  => $surveyTemplate->created_at->format('Y-m-d')
                ];
            });

            return response()->json([
                'data' => $surveyTemplate
            ]);

        }

        $data['meta_title'] = 'Survey Questions Templates';
        return view('survey.survey-template-index',  $data);
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
            'questions.*.answer_type' => 'required|in:yes_no,optional,rating,description',
        ]);

        // If editing, update the existing template
        if ($request->filled('id')) {
            $template = SurveyTemplate::findOrFail($request->id);

            $template->update([
                'template_name' => $request->template_name,
                'description' => $request->description,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(), // optional: update on edit
            ]);

            // Delete old questions before adding new ones
            $template->questions()->delete();
        } else {
            // Create new Survey template
            $template = SurveyTemplate::create([
                'template_name' => $request->template_name,
                'description' => $request->description,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(),
            ]);
        }

        // Ensure template was created successfully
        if (!$template || !$template->id) {
            return back()->with('error', 'Failed to create Survey template.');
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
            SurveyQuestion::create([
                'template_id' => $template->id,
                'question' => $q['question'],
                'answer_type' => $q['answer_type'],
                'options' => !empty($options) ? json_encode(array_values($options)) : null,
            ]);
        }

        return redirect()->back()->with('success', 'Survey template saved successfully!');
    }

    /*
     *
     * Display the specified resource.
     */
    public function show(SurveyTemplate $surveyTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = SurveyTemplate::with(['questions', 'department_info'])->findOrFail($id);
        // Check if this template is assigned to employees
        $isAssigned = $template->userAssignments()->exists(); // adjust relation name accordingly

        return response()->json([
            'questions' => $template->questions,
            'department' => $template->department_info->id ?? null,
            'name' => $template->template_name,
            'description' => $template->description,
            'locked' => $isAssigned,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyTemplate $surveyTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

       $survey = SurveyTemplate::findOrFail($id);
        try {

             if ($survey->userAssignments()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This template is assigned to one or more users and cannot be deleted.',
                ], 400);
             }

            $survey->questions()->delete(); // optional: if cascading needed
            $survey->delete();

            return response()->json(['success' => true, 'message' => 'Survey Template deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Survey Template.'], 500);
        }
    }

    public function fetch($id)
    {
        $template = SurveyTemplate::with(['questions', 'department_info', 'creator'])->findOrFail($id);

        return response()->json([
            'template_name' => $template->template_name,
            'description' => $template->description,
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

     public function surveysfetch($id)
    {
        $survey = SurveyUserAssign::find($id);
        if($survey){
            $template = SurveyTemplate::with(['questions', 'department_info', 'creator'])->findOrFail($survey->template_id);
            return response()->json([
                'template_name' => $template->template_name,
                'survey_description' => $template->description ?? '',
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
            return redirect()->back()->with('error', 'Survey not exist.');
        }

    }

    public function assign_template(Request $request)
    {

         if ($request->ajax()) {

            $surveyUsers = SurveyUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->get()
                ->map(function ($surveyUsers) {
                    return [
                        'id' => $surveyUsers->id,
                        'template_name' => $surveyUsers->template?->template_name ?? '-',
                        'survey_description' => $surveyUsers->template?->description ?? '-',
                        'survey_name' => $surveyUsers->survey_name ?? '-',
                        'department' => $surveyUsers->template?->department_info?->department ?? '-',
                        'employees' => $surveyUsers->employee?->full_name ?? '-',
                        'survey_start_date' => $surveyUsers->survey_start_date ?? '-',
                        'survey_end_date' => $surveyUsers->survey_end_date ?? '-',
                        'created_by' => $surveyUsers->assigned_user?->full_name ?? '-',
                        'status' => $surveyUsers->status ?? '-',
                    ];
                });

            return response()->json([
                'data' => $surveyUsers
            ]);
        }

        $data['meta_title'] = 'Assign Survey';
        return view('survey.survey-assign',  $data);
    }

//     public function sar_edit()
//     {

//     }

    public function store_assign_template(Request $request)
    {
         $department = $request->input('department');
        $selectedEmployees = $request->input('employee');
        // $surveyName = $request->input('survey_name');
        $templateId = $request->input('template');
        $startDate = $request->input('survey_start_date');
        $endDate = $request->input('survey_end_date');
        $assignedBy = Auth::user()->id; // current user
        $status = 1; // 1 = pending
        $submitDate = null; // initially null

        if (in_array(0, $selectedEmployees)) {
            if($department == 0)
            {
                $employeeIds = Employee::pluck('user_id')
                                ->toArray();
            }else{
                $employeeIds = Employee::where('department_id', $department)
                                ->pluck('user_id')
                                ->toArray();
            }

        } else {
            $employeeIds = $selectedEmployees;
        }


         do{
            $survey_code = 'SURVEY-' . mt_rand(100000, 999999);
        }   while (SurveyUserAssign::where('survey_code', $survey_code)->exists());


         foreach ($employeeIds as $userId) {
            SurveyUserAssign::create([
                'user_id'        => $userId,
                'template_id'    => $templateId,
                // 'survey_name'    => $surveyName,
                'survey_code'    => $survey_code,
                'assigned_by'    => $assignedBy,
                'survey_start_date' => $startDate,
                'survey_end_date'   => $endDate,
                'survey_submit_date'=> $submitDate,
                'status'         => $status,
            ]);
        }

        return redirect()->back()->with('success', 'Survey template assigned successfully.');

    }

     public function getTemplates($departmentId)
    {
         if($departmentId == 0)
        {
            $data['templates'] = SurveyTemplate::select('id','template_name')->get();
            $data['employees'] = Employee::select('user_id','full_name')->get();
        }
        else
        {
            $data['templates'] = SurveyTemplate::select('id','template_name')->where('department_id', $departmentId)->get();
            $data['employees'] = Employee::select('user_id','full_name')->where('department_id', $departmentId)->get();
        }


        return response()->json($data);
    }

    public function destroyAssign($id)
    {
        try {
            $assign = SurveyUserAssign::findOrFail($id);
            $assign->delete();

            return response()->json(['success' => true, 'message' => 'Assignment deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete assignment.'], 500);
        }
    }


    public function user_surveys(Request $request)
    {

         if ($request->ajax()) {
            $userId = Auth::user()->id;
            $surveyUsers = SurveyUserAssign::with([
                    'template.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->where('user_id',$userId)
                ->get()
                ->map(function ($surveyUsers) {
                    return [
                        'id' => $surveyUsers->id,
                        'template_name' => $surveyUsers->template?->template_name ?? '',
                        'description' => $surveyUsers->template?->description ?? '',
                        'survey_name' => $surveyUsers->survey_name ?? '-',
                        'template_id' => $surveyUsers->template_id ?? '',
                        'department' => $surveyUsers->template?->department_info?->department ?? '',
                        'employees' => $surveyUsers->employee?->full_name ?? '',
                        'survey_start_date' => $surveyUsers->survey_start_date ?? '',
                        'survey_end_date' => $surveyUsers->survey_end_date ?? '',
                        'status' => $surveyUsers->status ?? '',
                        'created_by' => $surveyUsers->assigned_user?->full_name ?? '',
                    ];
                });

            return response()->json([
                'data' => $surveyUsers
            ]);
        }

        $data['meta_title'] = 'Survey`s';
        return view('survey.user-survey',  $data);
    }

   public function surveyAnswerfetch($id)
    {
        $survey = SurveyUserAssign::with([
            'employee',
            'template.department_info',
            'template.questions',
            'assigned_user',
        ])->findOrFail($id);

        // Get all answers for this SAR
        $answers = SurveyReport::where('survey_id', $id)->get();

        // Optionally join with questions for enriched info
        $answers = $answers->map(function ($answer) {
            $question = SurveyQuestion::find($answer->question);
            return [
                'question_id'   => $answer->question,
                'question_text' => $question?->question,
                'answer_type'   => $answer->answer_type,
                'answer'        => $answer->answer,
                'options'       => $question?->options,
            ];
        });

        return response()->json([
            'survey_info' => $survey,
            'answers'  => $answers,
        ]);
    }

     // print sar report pdf
    public function generatePdf($id)
    {
        $survey = SurveyUserAssign::with([
            'employee',
            'template.department_info',
            'template.creator',
            'template.questions',
            'assigned_user',
        ])->findOrFail($id);

        $answers = SurveyReport::where('survey_id', $id)->get()->keyBy('question');

        $questionAnswers = $survey->template->questions->map(function ($question) use ($answers) {
            $answer = $answers->get($question->id);
            return [
                'question_id'   => $question->id,
                'question_text' => $question->question,
                'answer_type' => $question->answer_type,
                'answer'        => $answer?->answer ?? null,

            ];
        });

        $data = [
            'survey_info' => $survey,
            'answers' => $questionAnswers,
        ];

        $pdf = Pdf::loadView('pdf.survey_report', $data);
        return $pdf->download("Survey_Report_{$id}.pdf");
    }
}
