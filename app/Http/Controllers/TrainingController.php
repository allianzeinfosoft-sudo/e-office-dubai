<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /* ajax request */
        if ($request->ajax()) {
            // Handle the AJAX request here
            $trainings = Training::get()
            ->map(function ($trainings) {
                return [
                    'id' => $trainings->id,
                    'trainings_title' => $trainings->training_title ? $trainings->training_title : '-',
                    'trainings_startdate' => $trainings->start_date_time ?  date('d-m-Y', strtotime($trainings->start_date_time)) : '-',
                    'trainings_enddate' => $trainings->end_date_time ?  date('d-m-Y', strtotime($trainings->end_date_time)) : '-',
                    'trainings_detatils' => $trainings->training_details ? $trainings->training_details : '-',
                    'document' => $trainings->document ? $trainings->document : 'N/A',
                    'status' => $trainings->status ? $trainings->status : 'N/A',
                ];
            });

            return response()->json([
                'data' => $trainings
            ]);

        }

        //
        $data['meta_title'] = 'Trainings';
        return view('training.index', $data);
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
            'department'          => 'required|exists:departments,id',
            'employee'            => 'required|array|min:1',
            'employee.*'          => 'exists:users,id',
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
            TrainingUser::where('training_id', $training->id)->delete();

            foreach ($validated['employee'] as $empId) {
                TrainingUser::create([
                    'training_id' => $training->id,
                    'user_id'     => $empId,
                ]);
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

        // $validated = $request->validate([
        //     'trainings_title'     => 'required|string|max:255',
        //     'department'          => 'required|exists:departments,id',
        //     'employee'            => 'required|array|min:1',
        //     'employee.*'          => 'exists:users,id',
        //     'start_date_time'     => 'required|date',
        //     'end_date_time'       => 'required|date|after:start_date_time',
        //     'trainings_details'   => 'required|string',
        //     'document'            => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png|max:5120',
        // ]);

        // // --------------------------
        // // File Upload
        // // --------------------------
        // $fileName = null;

        // if ($request->hasFile('document')) {
        //     $fileName = time() . '_' . $request->file('document')->getClientOriginalName();
        //     $request->file('document')->storeAs('trainings', $fileName, 'public');
        // }

        // // --------------------------
        // // Insert Into Trainings Table
        // // --------------------------
        // $training = Training::create([
        //     'training_title'   => $validated['trainings_title'],
        //     'department_id'    => $validated['department'],
        //     'start_date_time'  => $validated['start_date_time'],
        //     'end_date_time'    => $validated['end_date_time'],
        //     'training_details' => $validated['trainings_details'],
        //     'document'         => $fileName,
        //     'status'           => 1,
        // ]);

        // // --------------------------
        // // Insert Employees Into training_users Table
        // // --------------------------
        // foreach ($validated['employee'] as $empId) {
        //     TrainingUser::create([
        //         'training_id' => $training->id,
        //         'user_id'     => $empId,
        //     ]);
        // }

        // return redirect()->back()->with('success', 'Training created successfully!');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'department'          => 'required|exists:departments,id',
            'employee'            => 'required|array|min:1',
            'employee.*'          => 'exists:users,id',
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
        TrainingUser::where('training_id', $training->id)->delete();

        // Insert updated employees
        foreach ($validated['employee'] as $empId) {
            TrainingUser::create([
                'training_id' => $training->id,
                'user_id'     => $empId,
            ]);
        }

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
}
