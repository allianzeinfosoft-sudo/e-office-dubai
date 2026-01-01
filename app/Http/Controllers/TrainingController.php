<?php

namespace App\Http\Controllers;

use App\Mail\TrainingAssignedMail;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $user = Auth::user();
            $adminRoles = ['Developer', 'HR', 'G1'];

            if (in_array($user->role, $adminRoles)) {

                $trainings = Training::with([
                    'trainingUsers' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ])->latest()->get();

            } else {

                $trainings = Training::whereHas('trainingUsers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with([
                    'trainingUsers' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }
                ])
                ->latest()
                ->get();
            }

            $now = Carbon::now();

            $trainings = $trainings->map(function ($training) use ($now) {

                $trainingUser = $training->trainingUsers->first();

                $startDate = Carbon::parse($training->start_date_time);
                $endDate   = Carbon::parse($training->end_date_time);

                if ($now->lt($startDate)) {
                    $trainingStatus = 'start_soon';
                } elseif ($now->between($startDate, $endDate)) {
                    $trainingStatus = 'ongoing';
                } else {
                    $trainingStatus = 'ended';
                }

                return [
                    'id'                  => $training->id,
                    'trainings_title'     => $training->training_title ?? '-',
                    'trainings_startdate' => $training->start_date_time ?? '-',
                    'trainings_enddate'   => $training->end_date_time ?? '-',
                    'trainings_details'   => $training->training_details ?? '-',
                    'document'            => $training->document ?? 'N/A',
                    'training_status'     => $trainingStatus,
                    'acceptance_status'   => $trainingUser->acceptance_status ?? 'Not Assigned',
                ];
            });

            return response()->json([
                'data' => $trainings
            ]);
        }

        return view('training.index', ['meta_title' => 'Trainings']);
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

        // --------------------------
        // Validation (same for store & update)
        // --------------------------
        $validated = $request->validate([
            'trainings_title'     => 'required|string|max:255',
            'department'          => 'required',
            'start_date_time'     => 'required|date',
            'end_date_time'       => 'required|date|after_or_equal:start_date_time',
            'trainings_details'   => 'required|string',
            'document'            => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // --------------------------
            // Find or Create Training
            // --------------------------
            $id = $request->input('id');
            $training = $id
                ? Training::findOrFail($id)
                : new Training();

            // --------------------------
            // Handle File Upload
            // --------------------------
            $fileName = $training->document ?? null;

            if ($request->hasFile('document')) {

                // Delete old file (only on update)
                if (!empty($training->document)) {
                    Storage::disk('public')->delete('trainings/' . $training->document);
                }

                // Upload new file
                $fileName = time() . '_' . $request->file('document')->getClientOriginalName();
                $request->file('document')->storeAs('trainings', $fileName, 'public');
            }

            // --------------------------
            // Save Training
            // --------------------------
            $training->fill([
                'training_title'   => $validated['trainings_title'],
                'department_id'    => $validated['department'],
                'start_date_time'  => $validated['start_date_time'],
                'end_date_time'    => $validated['end_date_time'],
                'training_details' => $validated['trainings_details'],
                'document'         => $fileName,
                'status'           => $training->exists ? $training->status : 1,
            ])->save();

            // --------------------------
            // Sync Employees
            // --------------------------
            if (empty($id)) {

                TrainingUser::where('training_id', $training->id)->delete();

                foreach ($validated['employee'] as $empId) {
                    TrainingUser::create([
                        'training_id' => $training->id,
                        'user_id'     => $empId,
                    ]);
                    $user = User::with('employee')->where('id', $empId)->first();

                    Mail::to($user->email)->send(
                        new TrainingAssignedMail($training, $user)
                    );

                }

            }


            DB::commit();

            return redirect()->back()->with(
                'success',
                $id ? 'Training updated successfully!' : 'Training created successfully!'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong!');
        }


    }


    public function view($id)
    {
        $user = Auth::user();
        $adminRoles = ['Developer', 'HR', 'G1'];

        $training = Training::with('trainingUsers.user', 'trainingUsers.employee')
                            ->findOrFail($id);

        $isAdmin = in_array($user->role, $adminRoles);

        /** -------------------------------
         *  TRAINING STATUS (DATE BASED)
         *  -------------------------------
         */
        $now = now();
        $startDate = \Carbon\Carbon::parse($training->start_date_time);
        $endDate   = \Carbon\Carbon::parse($training->end_date_time);

        if ($now->lt($startDate)) {
            $trainingStatus = 'start_soon';
        } elseif ($now->between($startDate, $endDate)) {
            $trainingStatus = 'ongoing';
        } else {
            $trainingStatus = 'ended';
        }

        /** -------------------------------
         *  USERS DATA
         *  -------------------------------
         */
        if ($isAdmin) {

            // Admin → show all users
            $users = $training->trainingUsers->map(function ($tu) use ($isAdmin, $trainingStatus) {
                return [
                    'id' => $tu->id,
                    'name' => $tu->employee->full_name ?? $tu->user->email,
                    'email' => $tu->user->email,
                    'acceptance_status' => $tu->acceptance_status ?? 'pending',
                    'attendance_status' => $tu->attendance_status,

                    // Attendance allowed only when training is ongoing & accepted
                    'can_mark_attendance' =>
                        $isAdmin &&
                        $trainingStatus === 'ongoing' &&
                        $tu->acceptance_status === 'accepted',
                ];
            });

        } else {

            // Normal user → show ONLY own record
            $tu = $training->trainingUsers
                            ->where('user_id', $user->id)
                            ->first();

            $users = [];

            if ($tu) {
                $users[] = [
                    'name' => $tu->employee->employee_name ?? $user->name,
                    'email' => $user->email,
                    'acceptance_status' => $tu->acceptance_status ?? 'pending',

                    // Can attend only during ongoing training
                    'can_attend' =>
                        $trainingStatus === 'ongoing' &&
                        $tu->acceptance_status === 'accepted',
                ];
            }
        }

        return response()->json([
            'title'   => $training->training_title,
            'start'   => $training->start_date_time,
            'end'     => $training->end_date_time,
            'details'=> $training->training_details,

            // ✅ UPDATED STATUS (date-based)
            'training_status' => $trainingStatus,

            'users' => $users
        ]);
    }


    public function markAttendance(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:training_users,id',
            'attendance_status' => 'required|in:present,absent'
        ]);

        $tu = TrainingUser::findOrFail($request->id);

        if ($tu->acceptance_status !== 'accepted') {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $tu->attendance_status = $request->attendance_status;
        $tu->save();

        return response()->json(['message' => 'Attendance updated']);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $training = Training::with('trainingUsers')->findOrFail($id);

        return response()->json([
            'training' => $training,
            'selected_employees' => $training->trainingUsers->pluck('user_id'), // array of selected employees
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
    {

        $training = Training::findOrFail($id);

        $validated = $request->validate([
            'trainings_title'     => 'required|string|max:255',
            'department'          => 'required',
            // 'employee'            => 'required|array|min:1',
            // 'employee.*'          => 'exists:users,id',
            'start_date_time'     => 'required|date',
            'end_date_time'       => 'required|date|after:start_date_time',
            'trainings_details'   => 'required|string',
            'document'            => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        // --------------------------
        // Handle File Update
        // --------------------------
        $fileName = $training->document; // keep old file by default

        if ($request->hasFile('document')) {

            // Delete old file if exists
            if (!empty($training->document)) {
                Storage::disk('public')->delete('trainings/' . $training->document);
            }

            // Upload new file
            $fileName = time() . '_' . $request->file('document')->getClientOriginalName();
            $request->file('document')->storeAs('trainings', $fileName, 'public');
        }

        // --------------------------
        // Update Trainings Table
        // --------------------------
        $training->update([
            'training_title'   => $validated['trainings_title'],
            'department_id'    => $validated['department'],
            'start_date_time'  => $validated['start_date_time'],
            'end_date_time'    => $validated['end_date_time'],
            'training_details' => $validated['trainings_details'],
            'document'         => $fileName,
        ]);

        // --------------------------
        // Update training_users Table
        // --------------------------
        // Remove old employees


        // TrainingUser::where('training_id', $training->id)->delete();

        // // Insert updated employees
        // foreach ($validated['employee'] as $empId) {
        //     TrainingUser::create([
        //         'training_id' => $training->id,
        //         'user_id'     => $empId,
        //     ]);
        // }

        return redirect()->back()->with('success', 'Training updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $training = Training::findOrFail($id);

        if (!empty($training->document)) {
            $filePath = 'trainings/' . $training->document;
            Storage::disk('public')->delete($filePath);
        }

        // Delete related training users
        TrainingUser::where('training_id', $training->id)->delete();

        // Delete training
        $training->delete();

        return response()->json([
            'message' => 'Training deleted successfully!'
        ]);
    }



    public function getEmployees($departmentId)
    {
         if($departmentId == 0)
        {
            $data['employees'] = Employee::select('user_id','full_name')->get();
        }
        else
        {
            $data['employees'] = Employee::select('user_id','full_name')->where('department_id', $departmentId)->get();
        }
        return response()->json($data);
    }

    public function updateAcceptance(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'status'      => 'required|in:accepted,rejected',
        ]);

        $updated = TrainingUser::where('training_id', $request->training_id)
            ->where('user_id', Auth::id())
            ->update([
                'acceptance_status' => $request->status,
            ]);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Training invitation ' . $request->status . ' successfully'
        ]);
    }

    public function availableUsers(Training $training)
    {
        $assignedIds = $training->trainingUsers()->pluck('user_id');

        $users = Employee::whereNotIn('user_id', $assignedIds)
                    ->select('user_id', 'full_name')
                    ->get();

        return response()->json([
            'users' => $users
        ]);
    }

    public function assignUsers(Request $request)
    {
        $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'users' => 'required|array'
        ]);

        $existing = TrainingUser::where('training_id', $request->training_id)
                                ->pluck('user_id')
                                ->toArray();

        $newUsers = array_diff($request->users, $existing);

        $insert = [];

        foreach ($newUsers as $uid) {
            $insert[] = [
                'training_id' => $request->training_id,
                'user_id' => $uid,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($insert) {
            TrainingUser::insert($insert);
        }

        return response()->json([
            'message' => 'Users assigned successfully'
        ]);
    }


}
