<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Feedback;
use App\Models\FeedbackAssign;
use App\Models\FeedbackReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
   public function index(Request $request)
    {

        if ($request->ajax()) {

            $feedback = Feedback::get()
            ->map(function ($feedback) {
                return [
                    'id' => $feedback->id,
                    'feedback_title' => $feedback->feedback_title ? $feedback->feedback_title : '',
                    'department' => $feedback->department_id ? $feedback->department_info->department  : '',
                    'created_by' => $feedback->created_by ? $feedback->creator->full_name : '',
                    'created_date' => $feedback->created_at->format('Y-m-d')
                ];
            });

            return response()->json([
                'data' => $feedback
            ]);

        }

        $data['meta_title'] = 'Feedback Questions';
        return view('feedback.feedback-index',  $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'nullable|exists:feedback,id',
            'feedback_title' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
        ]);

        // If editing, update the existing template
        if ($request->filled('id')) {
            $feedback = Feedback::findOrFail($request->id);

             if ($feedback->userAssignments()->exists()) {
                return redirect()->back()->with('error', 'This feedback is already assigned to employees and cannot be modified.');
            }

            $feedback->update([
                'feedback_title' => $request->feedback_title,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(), // optional: update on edit
            ]);

            // Delete old questions before adding new ones
            $feedback->questions()->delete();
        } else {
            // Create new feedback feedback
            $feedback = Feedback::create([
                'feedback_title' => $request->feedback_title,
                'department_id' => $request->department_id,
                'created_by' => auth()->id(), // current logged-in user ID
            ]);
        }

        // Store each question under the feedback
        foreach ($request->questions as $q) {

            $feedback->questions()->create([
                'question' => $q['question']
            ]);
        }

        return redirect()->back()->with('success', 'Feedback saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        //
    }

    public function edit(string $id)
    {
        $feedback = Feedback::with(['questions', 'department_info'])->findOrFail($id);
        $isAssigned = $feedback->userAssignments()->exists();

        return response()->json([
            'questions' => $feedback->questions,
            'department' => $feedback->department_info->id ?? null,
            'name' => $feedback->feedback_title,
            'locked' => $isAssigned,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        //
    }

   public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        try {
             if ($feedback->userAssignments()->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This feedback is assigned to one or more users and cannot be deleted.',
                ], 400);
             }

            $feedback->questions()->delete(); // optional: if cascading needed
            $feedback->delete();

            return response()->json(['success' => true, 'message' => 'Feedback deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete Feedback.'], 500);
        }
    }

     public function fetch($id)
    {
        $feedback = Feedback::with(['questions', 'department_info', 'creator'])->findOrFail($id);

        return response()->json([
            'feedback_title' => $feedback->feedback_title,
            'department' => optional($feedback->department_info)->department,
            'created_by' => optional($feedback->creator)->full_name,
            'questions' => $feedback->questions->map(function ($q) {
                return [
                    'question' => $q->question,
                ];
            }),
        ]);
    }

    public function feedbackfetch($id)
    {
        $feedback = FeedbackAssign::find($id);
        if($feedback){
            $template_feedback = Feedback::with(['questions', 'department_info', 'creator'])->findOrFail($feedback->feedback_id);
            return response()->json([
                'feedback_title' => $template_feedback->feedback_title,
                'department' => optional($template_feedback->department_info)->department,
                'created_by' => optional($template_feedback->creator)->full_name,
                'questions' => $template_feedback->questions->map(function ($q) {
                    return [
                        'question' => $q->question,
                        'question_id' => $q->id,

                    ];
                }),
            ]);
        }else{
            return redirect()->back()->with('error', 'Feedback not exist.');
        }

    }

    public function assign_feedback(Request $request)
    {

         if ($request->ajax()) {

            $feedbackUsers = FeedbackAssign::with([
                    'feedback.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->get()
                ->map(function ($feedbackUsers) {
                    return [
                        'id' => $feedbackUsers->id,
                        'feedback_title' => $feedbackUsers->feedback?->feedback_title ?? '',
                        'department' => $feedbackUsers->feedback?->department_info?->department ?? '',
                        'employees' => $feedbackUsers->employee?->full_name ?? '',
                        'feedback_start_date' => $feedbackUsers->feedback_start_date ?? '',
                        'feedback_end_date' => $feedbackUsers->feedback_end_date ?? '',
                        'created_by' => $feedbackUsers->assigned_user?->full_name ?? '',
                        'status' => $feedbackUsers->status ?? '',
                    ];
                });

            return response()->json([
                'data' => $feedbackUsers
            ]);
        }

        $data['meta_title'] = 'Assign Feedback';
        return view('feedback.assign-feedback',  $data);
    }

    public function store_assign_feedback(Request $request)
    {

        $templateId = $request->input('template');
        $startDate = $request->input('feedback_start_date');
        $endDate = $request->input('feedback_end_date');
        $assignedBy = Auth::user()->id; // current user
        $status = 1; // 1 = pending
        $submitDate = null; // initially null

        foreach ($request->employee as $userId) {
            FeedbackAssign::create([
                'user_id'        => $userId,
                'feedback_id'    => $templateId,
                'assigned_by'    => $assignedBy,
                'feedback_start_date' => $startDate,
                'feedback_end_date'   => $endDate,
                'feedback_submit_date'=> $submitDate,
                'status'         => $status,
            ]);
        }

        return redirect()->back()->with('success', 'Feedback assigned successfully.');

    }

    public function getFeedbacks($departmentId)
    {

        $data['templates'] = Feedback::select('id','feedback_title')->where('department_id', $departmentId)->get();
        $data['employees'] = Employee::select('user_id','full_name')->where('department_id', $departmentId)->get();

        return response()->json($data);
    }

     public function destroyAssign($id)
    {
        try {
            $assign = FeedbackAssign::findOrFail($id);
            $assign->delete();

            return response()->json(['success' => true, 'message' => 'Assignment deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete assignment.'], 500);
        }
    }


    public function user_feedbacks(Request $request)
    {
         if ($request->ajax()) {
            $userId = Auth::user()->id;
            $feedbackUsers = FeedbackAssign::with([
                    'feedback.department_info',
                    'employee',
                    'assigned_user'
                ])
                ->where('user_id',$userId)
                ->get()
                ->map(function ($feedbackUsers) {
                    return [
                        'id' => $feedbackUsers->id,
                        'feedback_title' => $feedbackUsers->feedback?->feedback_title ?? '',
                        'feedback_id' => $feedbackUsers->feedback_id ?? '',
                        'department' => $feedbackUsers->feedback?->department_info?->department ?? '',
                        'employees' => $feedbackUsers->employee?->full_name ?? '',
                        'feedback_start_date' => $feedbackUsers->feedback_start_date ?? '',
                        'feedback_end_date' => $feedbackUsers->feedback_end_date ?? '',
                        'status' => $feedbackUsers->status ?? '',
                        'created_by' => $feedbackUsers->assigned_user?->full_name ?? '',
                    ];
                });

            return response()->json([
                'data' => $feedbackUsers
            ]);
        }

        $data['meta_title'] = 'Assign Feedback';
        return view('feedback.user-feedback',  $data);
    }

    public function feedbackAnswerfetch($id)
    {
        // Load feedback with related data
        $feedback = FeedbackAssign::with([
            'employee.department',
            'employee.designation',
            'feedback.department_info',
            'feedback.creator',
            'feedback.questions',
            'assigned_user',
        ])->findOrFail($id);

        // Get all answers for this feedback
        $answers = FeedbackReport::where('feedback_assign_id', $id)->get()->keyBy('question');

        // Combine questions with their corresponding answers
        $questionAnswers = $feedback->feedback->questions->map(function ($question) use ($answers) {
            $answer = $answers->firstWhere('question', $question->id);

            return [
                'question_id'   => $question->id,
                'question_text' => $question->question,
                'answer'        => $answer?->comment ?? null,
                'mark'          => $answer?->mark ?? null,
            ];
        });

        // Calculate scores
        $totalScore     = $feedback->total_score;
        $maximumScore   = $feedback->maximum_score;
        $averageScore   = $answers->count() > 0 ? round($totalScore / $answers->count(), 2) : 0;
        $grade          = $feedback->grade;
        $scorePercent   = $feedback->score_percentage;

        // Format employee details
        $employee = $feedback->employee;

        $employeeDetails = [
            'full_name'       => $employee?->full_name,
            'employee_code'   => $employee?->employeeID,
            'department'      => $employee?->department?->department,
            'designation'     => $employee?->designation?->designation,
        ];

        // feedback Dates
        $feedbackDates = [
            'start_date'  => $feedback->feedback_start_date,
            'end_date'    => $feedback->feedback_end_date,
            'submit_date' => $feedback->feedback_submit_date,
        ];

        return response()->json([
            'feedback_info'    => $feedback,
            'employee_details' => $employeeDetails,
            'feedback_dates'  => $feedbackDates,
            'answers'         => $questionAnswers,
            'total_score'     => $totalScore,
            'maximum_score'   => $maximumScore,
            'average_score'   => $averageScore,
            'score_percent'   => $scorePercent,
            'grade'           => $grade,
        ]);
    }



    // print feedback report pdf
    public function generatePdf($id)
    {

        $feedback = FeedbackAssign::with([
            'employee',
            'feedback.department_info',
            'feedback.creator',
            'feedback.questions',
            'assigned_user',
        ])->findOrFail($id);

        $answers = FeedbackReport::where('feedback_assign_id', $id)->get()->keyBy('question');

        $questionAnswers = $feedback->feedback->questions->map(function ($question) use ($answers) {
            $answer = $answers->get($question->id);
            return [
                'question_id'   => $question->id,
                'question_text' => $question->question,
                'answer'        => $answer?->comment ?? null,
                'mark'          => $answer?->mark ?? null,
            ];
        });

        $data = [
            'feedback_info' => $feedback,
            'answers' => $questionAnswers,
            'total_score' => $feedback->total_score,
            'maximum_score' => $feedback->maximum_score,
            'score_percent' => $feedback->score_percentage,
        ];

        $pdf = Pdf::loadView('pdf.feedback_report', $data);
        return $pdf->download("Feedback_Report_{$id}.pdf");
    }

}
