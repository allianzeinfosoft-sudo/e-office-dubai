<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\DesignationRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
      $branches = Branch::all();
      return view('branch.index',compact('branches'));
    }

    public function getBranches()
    {
        $branch = Branch::all()->map(function ($branch) {
            return [
                'id' => $branch->id,
                'branch' => $branch->name,

            ];
        });

        $response = response()->json(['data' => $branch]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $branch_name =  $request->branch_name;
        $branch_location = $request->branch_location;
        Branch::create([
            'branch' => $branch_name,
            'location' => $branch_location,
        ]);
        return back()->with('success', 'Branch created successfully!');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDepartments($branchId)
    {
        $departments = Department::select('id','department')->where('branch_id', $branchId)->get();
        return response()->json($departments);
    }

    public function getDesignations($departmentId)
    {

        $designations = Designation::select('id','designation')->where('department_id', $departmentId)->get();
        return response()->json($designations);
    }
}
