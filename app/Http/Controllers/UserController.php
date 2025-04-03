<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveAllocation;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Workshift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
    public function store(Request $request)
    {

        $validatedData = $request->validate([

            'employeeID' => 'required|unique:employees,employeeID',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email|email',
            'full_name' => 'required',
            'phonenumber' => 'required|unique:employees',
            'mobile_number' => 'nullable|unique:employees',
            'landline' => 'nullable',
            'personal_email' => 'nullable|unique:employees|email',
            'aadhaar' => 'nullable|unique:employees',
            'date_of_birth' => 'date',
            'join_date' => 'date',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',


        ]);

        $user = User::create([
            'username'  => $request->username,
            'email'     => $request->email,
            'role'      => 'Employee',
            'password'  => Hash::make($request->email),
        ]);

        $user->assignRole('Employee');
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
                'reporting_to' => !empty($request->reporting_to) ? $request->reporting_to : null,
                'personal_email' => !empty($request->personal_email) ? $request->personal_email : null,
                'gender'    => !empty($request->gender) ? $request->gender : null,
                'blood_group' => !empty($request->blood_group) ? $request->blood_group : null,
                'qualification' => !empty($request->qualification) ? $request->qualification : null,
                'esi_no' => !empty($request->esi_no) ? $request->esi_no : null,
                'aadhaar' => !empty($request->aadhaar) ? $request->aadhaar : null,
                'pf_no' => !empty($request->pf_no) ? $request->pf_no : null,
                'electoral_id' => !empty($request->electoral_id) ? $request->electoral_id : null,
                'pan' => !empty($request->pan) ? $request->pan : null,
                'dob' => !empty($request->dob) ? $request->dob : null,
                'group' => !empty($request->group) ? $request->group : null,
                'address' => !empty($request->address) ? $request->address : null,
                'profile_image' => !empty($profileImagePath) ? $profileImagePath : null,
                'mobile_number' => !empty($request->mobile_number) ? $request->mobile_number : null,
                'mobile_relationship' => !empty($request->mobile_relationship) ? $request->mobile_relationship : null ,
                'landline' => !empty($request->landline) ? $request->landline : null,
                'landline_relationship' => !empty($request->landline_relationship) ? $request->landline_relationship : null,
                'department_id' => !empty($request->department_id) ? $request->department_id : null,
                'designation_id' => !empty($request->designation_id) ? $request->designation_id : null,
                'join_date' => !empty($request->join_date) ? $request->join_date : null,
                'shift_id' => !empty($request->shift_id) ? $request->shift_id : null,
                'role' => !empty($request->role) ? $request->role : null,
                'status' => !empty($request->status) ? $request->status : null,
                'login_limited_time' => !empty($request->login_limited_time) ? $request->login_limited_time : null,
                'appointment_status' => !empty($request->appointment_status) ? $request->appointment_status : null,
                'team_lead' => !empty($request->team_lead) ? $request->reporting_to : null,
                'bank_name' => !empty($request->bank_name) ? $request->bank_name : null,
                'bank_branch' => !empty($request->bank_branch) ? $request->bank_branch : null,
                'beneficiary_name' => !empty($request->beneficiary_name) ? $request->beneficiary_name : null,
                'account_number' => !empty($request->account_number) ? $request->account_number : null,
            ]);


            // Update user profile_pic separately (if needed)
            if ($employee && $profileImagePath) {
                $user->save();
            }
            Cache::forget('users');
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
        $user = User::with('employee')->findOrFail($id);
        if($user->employee->department_id != null)
        {
            $departmentId = $user->employee->department_id;
            $designations = Designation::where('department_id',$departmentId)->get();
        } else { $designations = []; }
        $employees = Employee::all();
        $departments = Department::all();
        $work_shifts = Workshift::all();
        $roles = Role::all();
        $user_statuses = UserStatus::all();
        return view('users.edit', compact('user','employees','departments','work_shifts','roles','user_statuses','designations'));
    }


    public function update(Request $request, User $user)
    {
        $employee = $user->employee;
        $request->validate([
            'username'  => ['required','string','max:255',
                                Rule::unique('users','username')->ignore($user->id),
                           ],
            'email'     => ['required','email',
                                Rule::unique('users','email')->ignore($user->id),
                           ],
            'profile_image' => 'nullable','image','mimes:jpeg,png,jpg,gif,svg','max:2048',
            'full_name' => 'required',
            'phonenumber' => ['required',
                                Rule::unique('employees','phonenumber')->ignore(optional($employee)->id),
                             ],
            'landline' => 'nullable',
            'personal_email' => ['nullable',
                                    Rule::unique('employees','personal_email')->ignore(optional($employee)->id),
                                ],
            'mobile_number' => ['nullable',
                                   Rule::unique('employees','mobile_number')->ignore(optional($employee)->id),
                                ],

            'aadhaar' => ['nullable',
                            Rule::unique('employees','aadhaar')->ignore(optional($employee)->id),
                            ],
            'join_date' => 'required|date',
            ]);




        // Update user details
        $user->update([
            'username'  => $request->username,
            'email'     => $request->email,
        ]);

        // Update the user's role if needed
        $user->syncRoles([$request->role ?? 'user']);

        // Handle profile image update
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('profile_pics', $filename, 'public');

            // Update profile_image in the Employee record
            $user->employee->update(['profile_image' => $profileImagePath]);
        }

        // Update Employee details
        $user->employee->update([
            'employeeID' => $request->employeeID,
            'full_name' => $request->full_name,
            'phonenumber' => $request->phonenumber,
            'reporting_to' => !empty($request->reporting_to) ? $request->reporting_to : null,
            'personal_email' => !empty($request->personal_email) ? $request->personal_email : null,
            'gender' => !empty($request->gender) ? $request->gender : null,
            'blood_group' => !empty($request->blood_group) ? $request->blood_group : null,
            'qualification' => !empty($request->qualification) ? $request->qualification : null,
            'esi_no' => !empty($request->esi_no) ? $request->esi_no : null,
            'aadhaar' => !empty($request->aadhaar) ? $request->aadhaar : null,
            'pf_no' => !empty($request->pf_no) ? $request->pf_no : null,
            'electoral_id' => !empty($request->electoral_id) ? $request->electoral_id : null,
            'pan' => !empty($request->pan) ? $request->pan : null,
            'dob' => !empty($request->dob) ? $request->dob : null,
            'group' => !empty($request->group) ? $request->group : null,
            'address' => !empty($request->address) ? $request->address : null,
            'mobile_number' => !empty($request->mobile_number) ? $request->mobile_number : null,
            'mobile_relationship' => !empty($request->mobile_relationship) ? $request->mobile_relationship : null,
            'landline' => !empty($request->landline) ? $request->landline : null,
            'landline_relationship' => !empty($request->landline_relationship) ? $request->landline_relationship : null,
            'department_id' => !empty($request->department_id) ? $request->department_id : null,
            'designation_id' => !empty($request->designation_id) ? $request->designation_id : null,
            'join_date' => !empty($request->join_date) ?  $request->join_date : null,
            'shift_id' => !empty($request->shift_id) ? $request->shift_id : null,
            'role' => !empty($request->role) ? $request->role : null,
            'status' => !empty($request->status) ? $request->status : null,
            'login_limited_time' => !empty($request->login_limited_time) ? $request->login_limited_time : null,
            'appointment_status' => !empty($request->appointment_status) ? $request->appointment_status : null,
            'team_lead' => !empty($request->team_lead) ? $request->team_lead : null,
            'bank_name' => !empty($request->bank_name) ? $request->bank_name : null,
            'bank_branch' => !empty($request->bank_branch) ? $request->bank_branch : null,
            'beneficiary_name' => !empty($request->beneficiary_name) ? $request->beneficiary_name : null,
            'account_number' => !empty($request->account_number) ? $request->account_number : null,
        ]);
        Cache::forget('users');
        return redirect()->route('users.edit', $user->id)->with('success', 'User details updated successfully!');



    }


    public function destroy(string $id)
    {
        $user = User::find($id);
        $employee = Employee::where('user_id',$id)->fist();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }
        $employee->delete();
        $user->delete();
        Cache::forget('users');
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
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
        return view('users.profile', compact('user'));
    }

    public function checkEmail(Request $request)
    {

        $exists = User::where('email', $request->email)->exists();
        return response()->json($exists ? false : true);
    }
}
