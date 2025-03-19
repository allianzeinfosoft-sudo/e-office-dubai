<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveAllocation;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Workshift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = Cache::remember('users', now()->addMinutes(60), function () {
            return User::all();
        });

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastEmployee = User::latest('id')->first();
        $departments = Department::all();
        $employees = Employee::all();
        $user_statuses = UserStatus::all();
        $work_shifts = Workshift::all();
        $nextId = $lastEmployee ? ((int) filter_var($lastEmployee->id, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;
        $nextEmployeeId = 'AIS' . $nextId;
        $roles = Role::all();
        return view('users.create', compact('nextEmployeeId',
        'employees','departments',
        'user_statuses','work_shifts','roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

        $user = User::create([
            'username'  => $request->username,
            'email'     => $request->email,
            'role'      => 'user',
            'password'  => Hash::make($request->username),
        ]);

        $user->assignRole('user');
        if($user)
        {
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $profileImagePath = $file->storeAs('profile_pics', $filename, 'public');
            }

            $employee = Employee::create([
                'user_id'   => $user->id,
                'employeeID' => $request->employeeID,
                'full_name' => $request->full_name,
                'phonenumber' => $request->phonenumber,
                'reporting_to' => $request->reporting_to,
                'personal_email' => $request->personal_email,
                'gender'    => $request->gender,
                'blood_group' => $request->blood_group,
                'qualification' => $request->qualification,
                'esi_no' => $request->esi_no,
                'aadhaar' => $request->aadhaar,
                'pf_no' => $request->pf_no,
                'electoral_id' => $request->electoral_id,
                'pan' => $request->pan,
                'dob' => $request->dob,
                'group' => $request->group,
                'address' => $request->address,
                'profile_image' => $profileImagePath,
                'mobile_number' => $request->mobile_number,
                'mobile_relationship' => $request->mobile_relationship,
                'landline' => $request->landline,
                'landline_relationship' => $request->landline_relationship,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'join_date' => $request->join_date,
                'shift_id' => $request->shift_id,
                'role' => $request->role,
                'status' => $request->status,
                'login_limited_time' => $request->login_limited_time,
                'appointment_status' => $request->appointment_status,
                'team_lead' => $request->team_lead,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'beneficiary_name' => $request->beneficiary_name,
                'account_number' => $request->account_number,
            ]);


            // Update user profile_pic separately (if needed)
            if ($employee && $profileImagePath) {
                $user->save();
            }

            // update leave allocated for employee

           // Update leave allocation for the employee
            if (!empty($user) && !empty($request->leave_carry_info)) {
                LeaveAllocation::updateOrCreate(
                    ['user_id' => $user->id, 'year' => date('Y')], // Find by user_id & year
                    [
                        'total_leaves' => $request->leave_carry_info,
                        'used_leaves' => 0,
                        'remaining_leaves' => $request->leave_carry_info,
                    ]
                );
            }

        }


        return redirect()->back()->with('success', 'User created successfully!');

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


    public function getUsers()
    {
        $users = User::join('employees', 'users.id', '=', 'employees.user_id')
                ->select(
                    'users.id',
                    'users.email',
                    'employees.employeeID',
                    'users.username',
                    'employees.phonenumber',
                    'employees.status',
                    'users.role',
                    'employees.full_name',
                    'employees.profile_image'
                )
                ->with('roles') // Ensure roles relationship is loaded
                ->get()
                ->map(function ($users) {
                    return [
                        'id' => $users->id,
                        'full_name' => $users->full_name,
                        'role' => $users->role,
                        'username' => $users->username,
                        'employeeID' => $users->employeeID,
                        'phonenumber' => $users->phonenumber,
                        'email' => $users->email,
                        'current_plan' => 'Enterprise',
                        'profile_image' => $users->profile_image,
                        'status' => $users->status,
                         "avatar" => "",
                    ];
                });



        $response = response()->json(['data' => $users]);

        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    public function userProfile($userid)
    {
        $user = User::with('employee')->find($userid);
        // dd($user);
        return view('users.profile', compact('user'));
    }

    public function checkEmail(Request $request)
    {
        dd($request->email);
        $exists = User::where('email', $request->email)->exists();
        return response()->json($exists ? false : true);
    }
}
