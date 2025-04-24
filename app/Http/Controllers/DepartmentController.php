<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function index(Request $request)
    {
         /* ajax request */
         if ($request->ajax()) {
            // Handle the AJAX request here
            $designation = Designation::with('department')->get()
            ->map(function ($designation) {
                return [
                    'id' => $designation->id,
                    'department' => $designation->department ? $designation->department->department : '',
                    'designation' =>  $designation->designation ? $designation->designation : '',
                    'created_at' => $designation->created_at ? $designation->created_at : '',
                ];
            });

            return response()->json([
                'data' => $designation
            ]);

        }

        //
        $data['meta_title'] = 'Departments & Designations';
        return view('department.index', $data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $branch = $request->branch_select ? $request->branch_select : null;
        $department_name = $request->department_name ? $request->department_name : null;

        Department::create([
            'branch_id' => $branch,
            'department' => $department_name,
        ]);
        return back()->with('success', 'Department created successfully!');

    }

    public function designation_store(Request $request)
    {
        $designationId = $request->id ?? null;
        $department = $request->department_id ?? null;
        $designationName = $request->designation ?? null;

        if ($designationId) {
            // Update existing designation
            $designation = Designation::find($designationId);

            if ($designation) {
                $designation->update([
                    'department_id' => $department,
                    'designation' => $designationName,
                ]);
                return back()->with('success', 'Designation updated successfully!');
            } else {
                return back()->with('error', 'Designation not found.');
            }
        } else {
            // Create new designation
            Designation::create([
                'department_id' => $department,
                'designation' => $designationName,
            ]);
            return back()->with('success', 'Designation created successfully!');
        }

    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $designation = Designation::with('department.branch')->find($id);
        $data['id'] = $designation->id ?? null;
        $data['branch'] = $designation->department->branch->id ?? null;
        $data['department'] = $designation->department ?? null;
        $data['designation'] = $designation->designation;

        return response()->json($data);
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy($id)
    {
        $designation = Designation::find($id);
        $designation->delete();
        return response()->json(['message' => 'Designation deleted successfully']);
    }
}
