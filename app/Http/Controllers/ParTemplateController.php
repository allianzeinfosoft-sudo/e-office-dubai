<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ParQuestion;
use App\Models\ParTemplate;
use App\Models\ParUserAssign;
use App\Models\PerformanceAppraisalReport;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $data['meta_title'] = 'PAR Questions';
        return view('par.partemplate-index',  $data);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
             'id' => 'nullable|exists:sar_templates,id',
            'template_name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',

        ]);

        // If editing, update the existing template
        if ($request->filled('id')) {
            $template = ParTemplate::findOrFail($request->id);

            if ($template->userAssignments()->exists()) {
                            return redirect()->back()->with('error', 'This template is already assigned to employees and cannot be modified.');
                        }
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


        // Store each question under the template
        foreach ($request->questions as $q) {

            $template->questions()->create([
                'question' => $q['question']
            ]);
        }
        return redirect()->back()->with('success', 'PAR Template saved successfully!');
    }


    public function show(ParTemplate $parTemplate)
    {
        //
    }

    public function edit(string $id)
    {

        $template = ParTemplate::with(['questions', 'department_info'])->findOrFail($id);
        // Check if this template is assigned to employees
        $isAssigned = $template->userAssignments()->exists(); // adjust relation name accordingly

        return response()->json([
            'questions' => $template->questions,
            'department' => $template->department_info->id ?? null,
            'name' => $template->template_name,
            'locked' => $isAssigned,
        ]);
    }


    public function update(Request $request, ParTemplate $parTemplate)
    {
        //
    }


    public function destroy($parTemplate)
    {
        $par = ParTemplate::findOrFail($parTemplate);
        try {

              if ($par->userAssignments()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This template is assigned to one or more users and cannot be deleted.',
                ], 400);
             }

            $par->questions()->delete(); // optional: if cascading needed
            $par->delete();

            return response()->json(['success' => true, 'message' => 'PAR Template deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete PAR Template.'], 500);
        }
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
                        'status' => $parUsers->status ?? '',
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
            'employee.department',
            'employee.designation',
            'template.department_info',
            'template.creator',
            'template.questions',
            'assigned_user',
        ])->findOrFail($id);

        // Get all answers for this SAR
        $answers = PerformanceAppraisalReport::where('par_id', $id)->get();
        // Optionally join with questions for enriched info
         $questionAnswers = $par->template->questions->map(function ($question) use ($answers) {
            $answer = $answers->firstWhere('question', $question->id);
            return [
                'question_id'   => $question->id,
                'question_text' => $question->question,
                'answer'        => $answer?->comment ?? null,
                'mark'          => $answer?->mark ?? null,
            ];
        });

         // Calculate scores
        $totalScore     = $par->total_score;
        $maximumScore   = $par->maximum_score;
        $averageScore   = $answers->count() > 0 ? round($totalScore / $answers->count(), 2) : 0;
        $grade          = $par->grade;
        $scorePercent   = $par->score_percentage;

        // Format employee details
        $employee = $par->employee;

        $employeeDetails = [
            'full_name'       => $employee?->full_name,
            'employee_code'   => $employee?->employeeID,
            'department'      => $employee?->department?->department,
            'designation'     => $employee?->designation?->designation,
        ];

        // PAR Dates
        $parDates = [
            'start_date'  => $par->par_start_date,
            'end_date'    => $par->par_end_date,
            'submit_date' => $par->par_submit_date,
        ];


        return response()->json([
            'par_info'        => $par,
            'employee_details'=> $employeeDetails,
            'par_dates'       => $parDates,
            'answers'         => $questionAnswers,
            'total_score'     => $totalScore,
            'maximum_score'   => $maximumScore,
            'average_score'   => $averageScore,
            'score_percent'   => $scorePercent,
            'grade'           => $grade,
        ]);
    }

    // print sar report pdf
    public function generatePdf($id)
    {
        $par = ParUserAssign::with([
            'employee',
            'template.department_info',
            'template.creator',
            'template.questions',
            'assigned_user',
        ])->findOrFail($id);

        $answers = PerformanceAppraisalReport::where('par_id', $id)->get()->keyBy('question');

        $questionAnswers = $par->template->questions->map(function ($question) use ($answers) {
            $answer = $answers->get($question->id);
            return [
                'question_id'   => $question->id,
                'question_text' => $question->question,
                'answer'        => $answer?->comment ?? null,
                'mark'          => $answer?->mark ?? null,
            ];
        });

        $data = [
            'par_info' => $par,
            'answers' => $questionAnswers,
            'total_score' => $par->total_score,
            'maximum_score' => $par->maximum_score,
            'score_percent' => $par->score_percentage,
        ];

        $pdf = Pdf::loadView('pdf.performance_appraisal_report', $data);
        return $pdf->download("Performance_Appraisal_Report_{$id}.pdf");
    }
}
