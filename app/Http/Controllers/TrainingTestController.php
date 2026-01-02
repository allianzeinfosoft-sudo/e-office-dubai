<?php

namespace App\Http\Controllers;

use App\Mail\TrainingTestInvitationMail;
use App\Models\Training;
use App\Models\TrainingTest;
use App\Models\TrainingTestAnswer;
use App\Models\TrainingTestQuestion;
use App\Models\TrainingTestUser;
use App\Models\TrainingUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class TrainingTestController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $user = Auth::user();
            $adminRoles = ['Developer', 'HR', 'G1'];

            /** ----------------------------
             *  ADMIN USERS
             * ---------------------------- */
            if (in_array($user->role, $adminRoles)) {

                $trainingTests = TrainingTest::with([
                    'trainingTestUsers' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ])->latest()->get();

            }
            /** ----------------------------
             *  NORMAL USERS
             * ---------------------------- */
            else {

                $trainingTests = TrainingTest::whereHas('trainingTestUsers', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->with([
                        'trainingTestUsers' => function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        }
                    ])
                    ->latest()
                    ->get();
            }

            /** ----------------------------
             *  Map Data for DataTable
             * ---------------------------- */
            $data = $trainingTests->map(function ($test) use ($user) {

                $testUser = $test->trainingTestUsers->first();

                return [
                    'id' => $test->id,
                    'title' => $test->title ?? '-',
                    'training_title' => $test->training ? $test->training->training_title : '-',
                    'start_date' => $test->start_at ? Carbon::parse($test->start_at)->format('d-m-Y') : '-',
                    'end_date' => $test->end_at ? Carbon::parse($test->end_at)->format('d-m-Y') : '-',
                    'total_marks' => $test->total_marks ?? '-',
                    'score' => $testUser?->score ?? '-',
                    'result' => $testUser?->result ? ucfirst($testUser?->result) : '-',
                    'attempt_status' => $testUser?->attempt_status ?? 'Not Started',
                    'assign_status' => $testUser ? 'Assigned' : 'Not Assigned',
                    'status' => $test->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-secondary">Inactive</span>',

                    'acceptance_status' => $testUser
                        ? ucfirst($testUser->acceptance_status)
                        : 'Not Assigned',

                    /** ----------------------------
                     *  ACTION BUTTONS
                     * ---------------------------- */
                    'action' => view('training-test.partials.actions', [
                        'test' => $test,
                        'testUser' => $testUser,
                        'user' => $user
                    ])->render(),
                ];
            });

            return response()->json(['data' => $data]);
        }

        return view('training-test.index', [
            'meta_title' => 'Training Tests'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'training_id'        => 'required|numeric',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after:start_date',
            'questions'          => 'nullable|array',
            'questions.*.title'  => 'nullable|string',
            'questions.*.marks'  => 'nullable|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {

            /** --------------------------------
             *  Create Training Test
             * -------------------------------- */
            $trainingTest = TrainingTest::create([
                'training_id' => $request->training_id,
                'title'       => $request->title,
                'start_at'    => $request->start_date,
                'end_at'      => $request->end_date,
                'status'      => 1,
                'total_marks' => 0,
            ]);

            /** --------------------------------
             *  Store Questions
             * -------------------------------- */
            $totalMarks = 0;

            if ($request->filled('questions')) {
                foreach ($request->questions as $question) {

                    if (empty($question['title'])) {
                        continue;
                    }

                    $marks = $question['marks'] ?? 0;

                    TrainingTestQuestion::create([
                        'training_test_id' => $trainingTest->id,
                        'question'         => $question['title'],
                        'option_a'         => $question['options'][1] ?? null,
                        'option_b'         => $question['options'][2] ?? null,
                        'option_c'         => $question['options'][3] ?? null,
                        'option_d'         => $question['options'][4] ?? null,
                        'correct_option'   => $question['correct_answer'] ?? null,
                        'marks'            => $marks,
                    ]);

                    $totalMarks += $marks;
                }
            }

            $trainingTest->update(['total_marks' => $totalMarks]);

            /** --------------------------------
             *  Assign Test to Training Users
             * -------------------------------- */
            $trainingUsers = TrainingUser::where('training_id', $request->training_id)
                ->where('attendance_status', 'present')
                ->where('acceptance_status', 'accepted') // optional but recommended
                ->get();

            foreach ($trainingUsers as $trainingUser) {

                 // 1. Create test-user mapping
                TrainingTestUser::create([
                    'training_test_id' => $trainingTest->id,
                    'user_id'          => $trainingUser->user_id,
                    'acceptance_status'=> 'pending',
                    'attempt_status'   => 'not_started'
                ]);

                // 2. Send invitation email
                $user = User::with('employee')
                    ->where('id', $trainingUser->user_id)
                    ->first();

                if ($user && $user->email) {
                    Mail::to($user->email)->send(
                        new TrainingTestInvitationMail($trainingTest, $user)
                    );
                }
            }

            DB::commit();

            return redirect()
                ->route('training-tests.index')
                ->with('success', 'Training Test created and assigned to users successfully');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }


    public function show($id)
    {
        $trainingTest = TrainingTest::with('questions')->findOrFail($id);

        return view('training-test.show', compact('trainingTest'));
    }

    public function questionPaper($id)
    {
        $test = TrainingTest::with('questions')->findOrFail($id);

        return response()->json([
            'title'        => $test->title,
            'total_marks'  => $test->total_marks,
            'questions'    => $test->questions
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $trainingTest = TrainingTest::with([
                'questions',
                'trainingTestUsers.answers'
            ])->findOrFail($id);

            /** --------------------------------
             *  Delete answers
             * -------------------------------- */
            foreach ($trainingTest->trainingTestUsers as $testUser) {
                $testUser->answers()->delete();
            }

            /** --------------------------------
             *  Delete assigned users
             * -------------------------------- */
            $trainingTest->trainingTestUsers()->delete();

            /** --------------------------------
             *  Delete questions
             * -------------------------------- */
            $trainingTest->questions()->delete();

            /** --------------------------------
             *  Delete test
             * -------------------------------- */
            $trainingTest->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Training test deleted successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete training test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function attend($id)
    {
        $user = auth()->user();

        $test = TrainingTest::with('questions')->find($id);
        if (!$test) {
            return redirect()->back()->with('error', 'Training test not found.');
        }

        $testUser = TrainingTestUser::where('training_test_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$testUser) {
            return redirect()->back()->with('error', 'You are not assigned to this test.');
        }

        // Prevent re-attempt
        if ($testUser->attempt_status === 'submitted') {
            return redirect()->route('training-tests.certificate', $id);
        }

        $testUser->update(['attempt_status' => 'in_progress']);

        return view('training-test.attend', [
                        'test' => $test,
                        'testUser' => $testUser,
                        'meta_title' => 'Training Test'
                    ]);
    }



    public function submit(Request $request, $id)
    {
        $user = auth()->user();

        $test = TrainingTest::with('questions')->findOrFail($id);

        $testUser = TrainingTestUser::where([
            'training_test_id' => $id,
            'user_id' => $user->id
        ])->firstOrFail();

        $score = 0;

        foreach ($test->questions as $question) {

            $selected = $request->answers[$question->id] ?? null;

            $isCorrect = $selected === $question->correct_option;

            TrainingTestAnswer::create([
                'training_test_user_id' => $testUser->id,
                'training_test_question_id' => $question->id,
                'selected_option' => $selected,
                'is_correct' => $isCorrect
            ]);

            if ($isCorrect) {
                $score += $question->marks;
            }
        }

        // PASS / FAIL (example: 50%)
        $percentage = ($score / $test->total_marks) * 100;
        $result = $percentage >= 50 ? 'pass' : 'fail';

        $testUser->update([
            'attempt_status' => 'submitted',
            'score' => $score,
            'result' => $result
        ]);

        return redirect()
                ->route('training-tests.completed', $id)
                ->with([
                    'score' => $score,
                    'percentage' => round($percentage, 2),
                    'result' => $result
                ]);

    }

    public function certificate($id)
    {
        $user = auth()->user();

        $testUser = TrainingTestUser::with('test')
            ->where('training_test_id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();


        return view('training-test.certificate', [
                    'testUser'   => $testUser,
                    'meta_title' => 'Certificate of Completion | Training Test'
                ]);
    }



    public function downloadCertificate($id)
    {
        $testUser = TrainingTestUser::with(['test', 'user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->whereNotNull('result')
            ->firstOrFail();

        $pdf = Pdf::loadView(
            'training-test.certificate-pdf',
            compact('testUser')
        )->setPaper('A4', 'landscape');

        return $pdf->download(
            'certificate-'.$testUser->user->name.'.pdf'
        );
    }

    // test completed
    public function completed($id)
    {
        $user = auth()->user();

        $testUser = TrainingTestUser::with('test')
            ->where('training_test_id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('training-test.completed', [
            'testUser'   => $testUser,
            'meta_title' => 'Test Completed | Training Test'
        ]);
    }

    public function report_view()
    {
        $trainings = Training::select('id', 'training_title')->orderBy('training_title')->get();

        return view('training-test.training_test_report', [
            'trainings'   => $trainings,
            'meta_title' => 'Training Test Report'
        ]);
    }

       /**
     * Fetch Training Test Report Data (AJAX)
     */
    public function data(Request $request)
    {
            $query = TrainingTestUser::with([
            'test:id,training_id,title',
            'test.training:id,training_title',
            'user.employee:user_id,full_name'
        ]);

        // Filter by Training
        if ($request->filled('trainings')) {
            $query->whereHas('test.training', function ($q) use ($request) {
                $q->where('training_title', $request->trainings);
            });
        }

        $data = $query->get()->map(function ($row, $key) {
            return [
                'id' => $key + 1,
                'training_title' => $row->test->training->training_title ?? '-',
                'test_title' => $row->test->title ?? '-',
                'employee_name' => $row->user->employee
                    ? $row->user->employee->full_name
                    : $row->user->email,
                'attendance' => ucfirst($row->attempt_status),
                'score' => $row->score ?? '0',
                'result' => ucfirst($row->result ?? 'Pending'),
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }

}
