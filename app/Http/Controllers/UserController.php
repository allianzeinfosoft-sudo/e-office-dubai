<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\LeaveAllocation;
use App\Models\Position;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Workshift;
use App\Models\UserEntryBlockList;
use App\Models\Attendance;
use App\Models\Leave;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Illuminate\Support\Facades\Log;

use App\Helpers\CustomHelper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {

        $users = Cache::remember('users', now()->addMinutes(60), function () {
           // return User::with('employee')->orderBy('employeeID', 'asc')->get();
           return User::join('employees', 'users.id', '=', 'employees.user_id')
            ->with('employee')
            ->orderBy('employees.employeeID', 'asc')
            ->get();

        });

        return view('users.index', compact('users'));
    }

    public function locked_index()
    {
        return view('users.resigned-users');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastEmployee = Employee::latest('id')->first();
        $departments = Department::all();
        $employees = Employee::all();
        $positions = Position::all();
        $lastEmployeeId = Employee::orderBy('employeeID', 'desc')->value('employeeID');

        if ($lastEmployeeId) {

            // preg_match('/^([A-Z]+)(\d+)$/', $lastEmployeeId, $matches);
            // $prefix = $matches[1] ?? 'AIS';
             $number = $lastEmployeeId ? (int) $lastEmployeeId + 1 : 1;
        } else {
            // $prefix = 'AIS';
            $number = 1;
        }

        $nextEmployeeId = str_pad($number, 3, '0', STR_PAD_LEFT);

        $user_statuses = UserStatus::all();
        $work_shifts = Workshift::all();
        // $nextId = $lastEmployee ? ((int) filter_var($lastEmployee->id, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;

        $roles = Role::all();

        return view('users.create', compact('nextEmployeeId',
        'employees','departments',
        'user_statuses','work_shifts','roles','positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        // $validatedData = $request->validate([

        //     'employeeID' => 'required|unique:employees,employeeID',
        //     'username' => 'required|unique:users,username',
        //     'email' => 'required|unique:users,email|email',
        //     'full_name' => 'required',
        //     'phonenumber' => 'required|unique:employees',
        //     'mobile_number' => 'nullable|unique:employees',
        //     'landline' => 'nullable',
        //     'personal_email' => 'nullable|unique:employees|email',
        //     'aadhaar' => 'nullable|unique:employees',
        //     'date_of_birth' => 'date',
        //     'join_date' => 'date',
        //     'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',



        // ]);

        $user = User::create([
            'username'  => $request->username,
            'email'     => $request->email,
            'role'      => $request->group,
            'password'  => Hash::make($request->email),
        ]);

        $user->assignRole($request->group);
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
                'address' => !empty($request->address) ? trim($request->address) : null,
                'profile_image' => !empty($profileImagePath) ? $profileImagePath : null,
                'mobile_number' => !empty($request->mobile_number) ? $request->mobile_number : null,
                'mobile_relationship' => !empty($request->mobile_relationship) ? $request->mobile_relationship : null ,
                'landline' => !empty($request->landline) ? $request->landline : null,
                'landline_relationship' => !empty($request->landline_relationship) ? $request->landline_relationship : null,
                'department_id' => !empty($request->department_id) ? $request->department_id : null,
                'designation_id' => !empty($request->designation_id) ? $request->designation_id : null,
                'join_date' => !empty($request->join_date) ? $request->join_date : null,
                'shift_id' => !empty($request->shift_id) ? $request->shift_id : null,
                'holidayGroup' => !empty($request->holidayGroup) ? $request->holidayGroup : null,
                'role' => !empty($request->role) ? $request->role : null,
                'status' => !empty($request->status) ? $request->status : null,
                'resigned_date' =>!empty($request->resigned_date) ? $request->resigned_date : null,
                'login_limited_time' => !empty($request->login_limited_time) ? $request->login_limited_time : null,
                'appointment_status' => !empty($request->appointment_status) ? $request->appointment_status : null,
                'team_lead' => !empty($request->team_lead) ? $request->reporting_to : null,
                'bank_name' => !empty($request->bank_name) ? $request->bank_name : null,
                'bank_branch' => !empty($request->bank_branch) ? $request->bank_branch : null,
                'beneficiary_name' => !empty($request->beneficiary_name) ? $request->beneficiary_name : null,
                'account_number' => !empty($request->account_number) ? $request->account_number : null,
                'ifsc' => !empty($request->ifsc) ? $request->ifsc : null,
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
        if ($user->employee && $user->employee->department_id !== null) {
            $departmentId = $user->employee->department_id;
            $designations = Designation::where('department_id', $departmentId)->get();
        } else {
            $designations = collect(); // or [] if you prefer plain array
        }
        $employees = Employee::all();
        $departments = Department::all();
        $work_shifts = Workshift::all();
        $positions = Position::all();
        $roles = Role::all();
        $user_statuses = UserStatus::all();

        return view('users.edit', compact('user','employees','departments','work_shifts','roles','user_statuses','designations','positions'));
    }

    public function limited_edit(string $id)
    {

        $user = User::with('employee')->findOrFail($id);
        if ($user->employee && $user->employee->department_id !== null) {
            $departmentId = $user->employee->department_id;
            $designations = Designation::where('department_id', $departmentId)->get();
        } else {
            $designations = collect(); // or [] if you prefer plain array
        }
        $employees = Employee::all();
        $departments = Department::all();
        $work_shifts = Workshift::all();
        $positions = Position::all();
        $roles = Role::all();
        $user_statuses = UserStatus::all();

        return view('users.limited-edit-profile', compact('user','employees','departments','work_shifts','roles','user_statuses','designations','positions'));
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
            'role' => $request->group,
        ]);

        // Update the user's role if needed
        $user->syncRoles([$request->group]);

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
            'address' => !empty($request->address) ? trim($request->address) : null,
            'mobile_number' => !empty($request->mobile_number) ? $request->mobile_number : null,
            'mobile_relationship' => !empty($request->mobile_relationship) ? $request->mobile_relationship : null,
            'landline' => !empty($request->landline) ? $request->landline : null,
            'landline_relationship' => !empty($request->landline_relationship) ? $request->landline_relationship : null,
            'department_id' => !empty($request->department_id) ? $request->department_id : null,
            'designation_id' => !empty($request->designation_id) ? $request->designation_id : null,
            'join_date' => !empty($request->join_date) ?  $request->join_date : null,
            'shift_id' => !empty($request->shift_id) ? $request->shift_id : null,
            'holidayGroup' => !empty($request->holidayGroup) ? $request->holidayGroup : null,
            'role' => !empty($request->role) ? $request->role : null,
            'status' => !empty($request->status) ? $request->status : null,
            'resigned_date' =>!empty($request->resigned_date) ? $request->resigned_date : null,
            'login_limited_time' => !empty($request->login_limited_time) ? $request->login_limited_time : null,
            'appointment_status' => !empty($request->appointment_status) ? $request->appointment_status : null,
            'team_lead' => !empty($request->team_lead) ? $request->team_lead : null,
            'bank_name' => !empty($request->bank_name) ? $request->bank_name : null,
            'bank_branch' => !empty($request->bank_branch) ? $request->bank_branch : null,
            'beneficiary_name' => !empty($request->beneficiary_name) ? $request->beneficiary_name : null,
            'account_number' => !empty($request->account_number) ? $request->account_number : null,
            'ifsc'  => !empty($request->ifsc) ? $request->ifsc : null,
        ]);
        Cache::forget('users');

        return redirect()->route('users.index', $user->id)->with('success', 'User details updated successfully!');

    }

    public function limitedUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->employee()->updateOrCreate(
            [],
            [
                'personal_email' => $request->personal_email,
                'phonenumber' => $request->phonenumber,
                'address' => trim($request->address),
            ]
        );


        Cache::forget('user');
        return redirect()->route('user.profile', $user->id)->with('success', 'User details updated successfully!');
    }

    public function destroy(string $id)
    {

        $user = User::find($id);
        $employee = Employee::where('user_id',$id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $user->delete();
        if ($employee) {
            $employee->status = 4; // e.g., 'active', 'inactive', 1, 0, etc.
            $employee->save();
        }
        Cache::forget('users');
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    public function restore_user(string $id)
    {
        $user = User::withTrashed()->find($id);
        $employee = Employee::where('user_id',$id)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        if ($user->trashed()) {
            $user->restore();
            // if ($employee) {
            //     $employee->status = 2; // e.g., 'active', 'inactive', 1, 0, etc.
            //     $employee->save();
            // }
            Cache::forget('users');
            return response()->json(['success' => true, 'message' => 'User restored successfully.']);
        }else{
            return response()->json(['success' => false, 'message' => 'User is not restored.']);
        }

    }




    public function getUsers()
    {
        $users = User::with('roles','employee')
                ->get()
                ->map(function ($users) {
                    return [
                        'id' => $users->id,
                        'full_name' => $users->employee?->full_name ?? '',
                        'group' => $users->role,
                        'role' => $users->employee?->role ?? '',
                        'username' => $users->username,
                        'employeeID' => 'AIS-'.$users->employee?->employeeID ?? '',
                        'phonenumber' => $users->employee?->phonenumber ?? '',
                        'email' => $users->email,
                        'current_plan' => 'Enterprise',
                        'profile_image' => $users->employee?->profile_image ?? '',
                        'status' => $users->employee?->status,
                         "avatar" => "",
                    ];
                });



        $response = response()->json(['data' => $users]);

        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    public function lockedUsers()
    {
        $users = User::onlyTrashed()
                ->with('roles','employee') // Ensure roles relationship is loaded
                ->get()
                ->map(function ($users) {
                    return [
                        'id' => $users->id,
                        'full_name' => $users->employee->full_name,
                        'group' => $users->role,
                        'role' => $users->employee->role,
                        'username' => $users->username,
                        'employeeID' => 'AIS-'.$users->employee->employeeID,
                        'phonenumber' => $users->employee->phonenumber,
                        'email' => $users->email,
                        'current_plan' => 'Enterprise',
                        'profile_image' => $users->employee->profile_image,
                        'status' => $users->employee->status,
                         "avatar" => "",
                    ];
                });



        $response = response()->json(['data' => $users]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    public function userProfile($userid){

        $user = User::with('employee','leave_allocation','user_leaves')->find($userid);
        $user_leaves = $user->user_leaves;

        $pending_leave = $user_leaves->where('status', 1)->sum('leave_day_count');
        $approved_leave = $user_leaves->where('status', 2)->where('leave_type','!=','off_day')->sum('leave_day_count');

        // This month's leave (based on from_date in current month)
        $this_month_leave = $user_leaves->filter(function ($leave) {
            return \Carbon\Carbon::parse($leave->leave_from)->format('Y-m') === now()->format('Y-m');
        })->sum('leave_day_count');

        $leave_alloted = $user->leave_allocation?->total_leaves;
        $balance_leave = $user->leave_allocation?->remaining_leaves;
        // Past year leave count
        $now = now();
        $pastYear = $now->subYear()->format('Y');
        $pastyear_leave_count = $user_leaves->filter(function ($leave) use ($pastYear) {
            return \Carbon\Carbon::parse($leave->leave_from)->format('Y') === $pastYear;
        })->sum('leave_day_count');

        $off_day_leavecount = $user_leaves->where('leave_type', 'off_day')->where('status',2)->sum('leave_day_count');
        $full_day_leavescount = $user_leaves->where('leave_type','full_day')->where('status',2)->sum('leave_day_count');
        $half_day_leavescount = $user_leaves->where('leave_type','half_day')->where('status',2)->sum('leave_day_count');
        $leave_info = (object) [

            'pending_leaves' => $pending_leave,
            'approved_leaves' => $approved_leave,
            'this_month_leave' => $this_month_leave,
            'leave_alloted' => $leave_alloted,
            'balance_leave' => $balance_leave,
            'past_year_leavecount' => $pastyear_leave_count,
            'off_day_leavecount' => $off_day_leavecount,
            'full_day_leavecount' => $full_day_leavescount,
            'half_day_leavecount' => $half_day_leavescount,
        ];
        $attendance_analytics = CustomHelper::currentAttendanceAnalytics($userid);
        $experiance = CustomHelper::getExperience($user);

        return view('users.profile', compact('user','leave_info', 'attendance_analytics', 'experiance'));

    }


    public function profileEdit($userId){
        $lastEmployee = User::latest('id')->first();
        $nextId = $lastEmployee ? ((int) filter_var($lastEmployee->id, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;
        $data['nextEmployeeId'] = $nextId;

        $data['meta_title'] = 'Edit Update Profile';
        $data['loginUser'] = User::where('id', $userId)->get()->first();
        $data['user'] = Employee::where('user_id', $userId)->get()->first();
        $data['employees'] = Employee::all();
        $data['departments'] = Department::all();
        $data['work_shifts'] = Workshift::all();
        $data['roles'] = Position::all();
        $data['user_statuses'] = UserStatus::all();
        $data['designations'] = [];

        return view('users.edit-user-profile', $data);

    }


public function storeOrUpdate(Request $request, $id = null)
{
    try {

        $request->merge(array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $request->all()));


        $validatedData = $request->validate([
            'employeeID' => [
                'required',
                Rule::unique('employees', 'employeeID')->ignore($id)
            ],
            'full_name' => 'required',
            'phonenumber' => [
                'required',
                Rule::unique('employees', 'phonenumber')->ignore($id)
            ],
            'mobile_number' => [
                'nullable',
                Rule::unique('employees', 'mobile_number')->ignore($id)
            ],
            'landline' => 'nullable',
            'personal_email' => [
                'nullable',
                Rule::unique('employees', 'personal_email')->ignore($id)
            ],
            'aadhaar' => [
                'nullable',
                Rule::unique('employees', 'aadhaar')->ignore($id)
            ],
            'esi_no' => [
                'nullable',
                Rule::unique('employees', 'esi_no')->ignore($id),
            ],
            'date_of_birth' => 'nullable|date',
            'join_date' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $profileImagePath = null;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('profile_pics', $filename, 'public');
        }

        $employee = Employee::updateOrCreate(
            ['id' => $id],
            [
                'user_id' => Auth::user()->id,
                'employeeID' => $request->employeeID,
                'full_name' => $request->full_name,
                'phonenumber' => $request->phonenumber,
                'reporting_to' => $request->reporting_to,
                'personal_email' => $request->personal_email,
                'gender' => $request->gender,
                'blood_group' => $request->blood_group,
                'qualification' => $request->qualification,
                'esi_no' => !empty($request->esi_no) ? $request->esi_no : null,
                'aadhaar' => $request->aadhaar,
                'pf_no' => $request->pf_no,
                'electoral_id' => $request->electoral_id,
                'pan' => $request->pan,
                'date_of_birth' => $request->date_of_birth,
                'group' => $request->group,
                'address' => $request->address,
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
                'profile_image' => $profileImagePath,
            ]
        );

        $message = $employee->wasRecentlyCreated ? 'Employee created successfully!' : 'Employee updated successfully!';
        return redirect()->back()->with('success', $message);

    } catch (ValidationValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    }
}

public function lockProfile($id)
{
    $user = User::find($id);

    Auth::logout();
        if (session()->isStarted()) {
            session()->invalidate();
            session()->regenerateToken();
        } else {
            \Log::warning("Session not started when trying to invalidate.");
        }


    return view('auth.lock',compact('user'));
}

public function change_password(Request $request)
{

    $user = Auth::user();
    // Update new password
    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->back()->with('success', 'Password changed successfully.');
}

public function checkOldPassword(Request $request)
{
    $request->validate([
        'old_password' => ['required'],
    ]);

    $user = Auth::user();

    if (Hash::check($request->old_password, $user->password)) {
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false]);
    }
}

public function assign_open_work(Request $request)
{

     if ($request->ajax()) {

        $users = Employee::with('user')->orderBy('id', 'desc')->get()
        ->map(function ($users) {
            return [
                'id' => $users->user_id,
                'picture' => $users->profile_image ? $users->profile_image : '',
                'full_name' => $users->full_name ? $users->full_name : 'N/A',
                'open_work_status' => $users->open_work_status ? $users->open_work_status : 0,
                'updated_date' => $users->open_work_setdate ? date('d-m-Y', strtotime($users->open_work_setdate)) : 'N/A'
            ];
        });

        return response()->json([
            'data' => $users
        ]);

    }

    //
    $data['meta_title'] = 'Assign open work';
    return view('settings.assign_open_work', $data);
}

public function open_work_assign(Request $request)
{


    $employee = Employee::where('user_id',$request->id)->first();

    if (!$employee) {
        return response()->json(['message' => 'Employee not found.'], 404);
    }

    if($request->status == 'true'){ $status = 1;}else{ $status = 0;}
    $employee->open_work_status = $status;
    $employee->open_work_setdate = date('Y-m-d');
    $employee->save();

    return response()->json(['message' => 'Status updated successfully.']);
}


public function users_birthday()
{
    $employees = Employee::whereNotNull('dob')->get();
    $today = Carbon::now();

    // 🔹 Today's Birthdays
    $todaysBirthdays = $employees->filter(function ($emp) use ($today) {
        $dob = Carbon::parse($emp->dob);
        return $dob->day == $today->day && $dob->month == $today->month;
    })->map(function ($emp) {
        return [
            'full_name' => $emp->full_name,
            'profile_image' => $emp->profile_image,
            'birth_date' => Carbon::parse($emp->dob)->format('F-d'),
        ];
    });

    // 🔹 Upcoming Birthdays (Current Month Onwards)
    $currentMonth = $today->month;

    $upcoming = $employees->filter(function ($emp) use ($currentMonth) {
        return Carbon::parse($emp->dob)->month >= $currentMonth;
    });

    $grouped = $upcoming->sortBy(function ($emp) {
        return Carbon::parse($emp->dob)->format('m-d');
    })->groupBy(function ($emp) {
        return Carbon::parse($emp->dob)->format('F');
    });

    $birthdays = $grouped->map(function ($group) {
        return $group->map(function ($emp) {
            return [
                'full_name' => $emp->full_name,
                'profile_image' => $emp->profile_image,
                'birth_date' => Carbon::parse($emp->dob)->format('F-d'),
            ];
        });
    });

    return view('views.birthday_view', [
        'birthdays' => $birthdays,
        'todaysBirthdays' => $todaysBirthdays,
    ]);
}



public function checkEmployeeId(Request $request)
{
    // $exists = Employee::where('employeeID', $request->employeeID)->exists();

    // return response()->json([
    //     'valid' => !$exists
    // ]);

    $employeeID = $request->employeeID;
    $user_id = $request->user_id;

    $query = Employee::where('employeeID', $employeeID);
    if (!empty($user_id)) {
        $query->where('user_id', '!=', $user_id);
    }
    $exists = $query->exists();
    return response()->json([
        'valid' => !$exists
    ]);
}

public function checkUsename(Request $request)
{
    // $exists = User::where('username', $request->username)->exists();
    // return response()->json([
    //     'valid' => !$exists
    // ]);

    $username = $request->username;
    $userId = $request->user_id;

    $query = User::where('username', $username);
    if (!empty($userId)) {
        $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();
    return response()->json([
        'valid' => !$exists
    ]);


}

public function checkEmail(Request $request)
{

    // $exists = User::where('email', $request->email)->exists();

    // return response()->json([
    //     'valid' => !$exists // FormValidation expects `valid: true` if value is allowed
    // ]);

    $email = $request->email;
    $userId = $request->user_id;

    $query = User::where('email', $email);
    if (!empty($userId)) {
        $query->where('id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

public function checkAadhar(Request $request)
{

    $aadhar = $request->aadhar;
    $userId = $request->user_id;

    $query = Employee::where('aadhaar', $aadhar);
    if (!empty($userId)) {
        $query->where('user_id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

public function checkEsi(Request $request)
{

    $esi_no = $request->esi_no;
    $userId = $request->user_id;

    $query = Employee::where('esi_no', $esi_no);
    if (!empty($userId)) {
        $query->where('user_id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

public function checkPf(Request $request)
{

    $pf_no = $request->pf_no;
    $userId = $request->user_id;

    $query = Employee::where('pf_no', $pf_no);
    if (!empty($userId)) {
        $query->where('user_id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

public function checkElectoral(Request $request)
{

    $electoral_id = $request->electoral_id;
    $userId = $request->user_id;

    $query = Employee::where('electoral_id', $electoral_id);
    if (!empty($userId)) {
        $query->where('user_id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

public function checkPAN(Request $request)
{

    $pan = $request->pan;
    $userId = $request->user_id;

    $query = Employee::where('pan', $pan);
    if (!empty($userId)) {
        $query->where('user_id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

public function checkAccountNumber(Request $request)
{

    $account_number = $request->account_number;
    $userId = $request->user_id;

    $query = Employee::where('account_number', $account_number);
    if (!empty($userId)) {
        $query->where('user_id', '!=', $userId);
    }

    $exists = $query->exists();

    return response()->json([
        'valid' => !$exists
    ]);

}

    /* Bolcked users list */

    public function blockedUsers(){
        $data['meta_title'] = 'Blocked Users';
        $data['employees'] = UserEntryBlockList::where('status', 1)->get();
        return view('black-list-users.index', $data);
    }

    public function unblockUser($id){
        $user = UserEntryBlockList::where('id', $id)->first();
        $user->status = 0;
        $user->save();
        return redirect()->back()->with('success', 'User unblocked successfully');
    }

    public function listOfLatecomers(){
        $data['meta_title'] = 'Latecomers';
        return view('latecomers-users.index', $data);
    }
    public function lateOfComersData(Request $request)
{
    $fromDate = $request->input('from_date')
        ? date('Y-m-d', strtotime($request->input('from_date')))
        : date('Y-m-d');

    $toDate = $request->input('to_date')
        ? date('Y-m-d', strtotime($request->input('to_date')))
        : date('Y-m-d');

    $employees = Employee::with('workshift')->whereNotIn('status', ['4'])->get();

    $data = $employees->map(function ($employee, $index) use ($fromDate, $toDate) {
        $shift = $employee->workshift;

        if (!$shift) return null;

        // Get list of leave dates (with off_type) for this employee
        $leaveDates = Leave::where('user_id', $employee->user_id)
            ->where('leave_type', 'off_type')
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('leave_from', [$fromDate, $toDate])
                    ->orWhereBetween('leave_to', [$fromDate, $toDate])
                    ->orWhere(function ($q) use ($fromDate, $toDate) {
                        $q->where('leave_from', '<=', $fromDate)
                            ->where('leave_to', '>=', $toDate);
                    });
            })
            ->get()
            ->flatMap(function ($leave) {
                return \Carbon\CarbonPeriod::create($leave->leave_from, $leave->leave_to)->toArray();
            })
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();

        $lateCount = Attendance::where('emp_id', $employee->user_id)
            ->whereBetween('signin_date', [$fromDate, $toDate])
            ->whereTime('signin_time', '>', $shift->shift_start_time)
            ->whereNotIn('signin_date', $leaveDates)
            ->count();

        return [
            'DT_RowIndex' => $index + 1,
            'id' => $employee->id,
            'user' => $employee->profile_image
                ? '<img src="' . asset('storage/' . $employee->profile_image) . '" alt="Avatar" class="rounded-circle" width="40" height="40" />'
                : '<img src="' . asset('assets/img/avatars/default-avatar.png') . '" alt="Avatar" class="rounded-circle" width="40" height="40" />',
            'fullname' => $employee->full_name,
            'count' => $lateCount,
            'action' => $lateCount > 0
                ? '<a href="javascript:void(0);" class="btn btn-sm btn-primary" title="View More" onclick="viewMoreModal(' . $employee->id . ')"> More Details </a>'
                : '<a href="javascript:void(0);" class="btn btn-sm btn-secondary disabled" title="No Details Available"> More Details </a>',
        ];
    })->filter()->values(); // Remove null and reindex

    return response()->json([
        'data' => $data
    ]);
}


    public function userLateCommers(Request $request){
        $id = $request->id;
        $fromDate = date('Y-m-d', strtotime($request->from_date)) ?? now()->format('Y-m-d');
        $toDate = date('Y-m-d', strtotime($request->to_date)) ?? now()->format('Y-m-d');

        // Fetch employee and their shift
        $employee = Employee::with('workshift', 'user')->where('id', $id)->first();

        if (!$employee) {
            return response()->json([
                'html' => '<p>Employee not found.</p>',
                'meta_title' => 'Error'
            ]);
        }

        $shift = $employee->workshift;

        if (!$shift) {
            return response()->json([
                'html' => '<p>No shift assigned for this employee.</p>',
                'meta_title' => 'Latecomer Details'
            ]);
        }

        // Get list of leave dates
        $leaveDates = Leave::where('user_id', $employee->user_id)
            ->where('leave_type', 'off_type')
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('leave_from', [$fromDate, $toDate])
                    ->orWhereBetween('leave_to', [$fromDate, $toDate])
                    ->orWhere(function ($q) use ($fromDate, $toDate) {
                        $q->where('leave_from', '<=', $fromDate)
                            ->where('leave_to', '>=', $toDate);
                    });
            })
            ->get()
            ->flatMap(function ($leave) {
                return \Carbon\CarbonPeriod::create($leave->leave_from, $leave->leave_to)->toArray();
            })
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        // Fetch late attendance records
        $lateAttendances = Attendance::where('emp_id', $employee->user_id)
            ->whereBetween('signin_date', [$fromDate, $toDate])
            ->whereTime('signin_time', '>', $shift->shift_start_time)
            ->whereNotIn('signin_date', $leaveDates)
            ->orderBy('signin_date', 'desc')
            ->get();

        // Load modal HTML
        $html = view('latecomers-users.view-details', [
            'employee' => $employee,
            'lateAttendances' => $lateAttendances,
        ])->render();

        return response()->json([
            'html' => $html,
            'meta_title' => $employee->full_name . "'s Late Arrival Details",
        ]);
    }

     public function listOfIncompleteWork(){
        $data['meta_title'] = 'Incomplete Working Hours';
        return view('incomplete-work.index', $data);
    }

    public function incompletData(Request $request){

        $month = $request->input('month') ?? date('m');  // Default to current month
        $year = $request->input('year') ?? date('Y');    // Default to current year
        $this_month_leave_hours = 0;
        $fromDate = date('Y-m-d', strtotime("$year-$month-01"));
        $toDate = date('Y-m-t', strtotime($fromDate));  // t = last day of the month
        $weekOffDays = [0, 6]; // Sunday, Saturday

        $employees = Employee::with('user')->whereIn('status', [1, 2, 5])->get();

        $data = $employees->map(function ($employee , $index) use ($fromDate, $toDate, $weekOffDays, $month, $year) {
            $workingDays = $this->getTotalWorkingDays($fromDate, $toDate, $weekOffDays);
            $this_month_leave = $this->getThisMonthLeave($month, $year,$employee->user_id);
            $this_month_leave_hours = $this_month_leave * 8;
            $expectedSeconds = ($workingDays * 8) - $this_month_leave_hours; // 8 hours per day

            $attendanceRecords = Attendance::where('emp_id', $employee->user_id)
                ->whereBetween('signin_date', [$fromDate, $toDate])
                ->get();

            $totalSeconds = 0;

            foreach ($attendanceRecords as $attendance) {
                if ($attendance->working_hours) {
                    $parts = explode(':', $attendance->working_hours);
                    if (count($parts) === 3) {
                        $hours = (int)$parts[0];
                        $minutes = (int)$parts[1];
                        $seconds = (int)$parts[2];
                        $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                    }
                }
            }

            return [
                'DT_RowIndex' => $index + 1,
                'id' => $employee->id,
                'fullname' => $employee->full_name,
                'username' => $employee->user->username ?? '',
                'profile_image' => '<img src="' . asset('storage/' . $employee->profile_image) . '" alt="Profile Image" class="rounded-circle" width="40" height="40" />' ?? '',
                'total_working_hours' => $expectedSeconds . ' : 00',
                'total_worked_hours' => floor( $totalSeconds / 3600) . ' : '. floor($totalSeconds % 3600)/60 ,
                'status' => (int) $totalSeconds/3600 <= (int) $expectedSeconds ? '<span class="badge bg-label-danger">Incomplete</span>' : '<span class="badge bg-label-success">Completed</span>',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getThisMonthLeave($month, $year, $user_id)
    {
        return $leaveCount = Leave::where('user_id', $user_id)
                ->whereYear('leave_from', $year)
                ->whereMonth('leave_from', $month)
                ->where('status', 2)
                ->sum('leave_day_count');
    }



    public function getTotalWorkingDays($fromDate, $toDate, $weekOffDays = [0, 6]){
        $from = Carbon::parse($fromDate);
        $to = Carbon::parse($toDate);
        $workingDays = 0;
        $holidays = DB::table('holidays')
            ->whereBetween('date', [$fromDate, $toDate])
            ->pluck('date')
            ->toArray();
        while ($from->lte($to)) {
            $dateStr = $from->toDateString();
            if (!in_array($from->dayOfWeek, $weekOffDays) && !in_array($dateStr, $holidays)) {
                $workingDays++;
            }
            $from->addDay();
        }
        return $workingDays;
    }





    public function getUserDetails($userId)
    {
        $employee = Employee::with('designation')->where('user_id', $userId)->first();

        if (!$employee) {
            return response()->json([], 404);
        }

        return response()->json([

            'full_name' => $employee->full_name,
            'designation' => $employee->designation->designation ?? '',
            'profile_image' => $employee->profile_image
                ? asset('storage/' . $employee->profile_image)
                : asset('assets/img/avatars/default-avatar.png')
        ]);
    }

    public function ChangeUserPassword(Request $request)
    {


        $user = User::find($request->password_user_id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->users_new_password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

   public function getAllEmployees()
    {
        $employees = Employee::pluck('full_name', 'user_id');
        return response()->json($employees);
    }

    public function getEmployeeDepartment(Request $request)
    {
        $employee = Employee::with('department')
                ->where('user_id', $request->employee_id)
                ->first();

        if (!$employee || !$employee->department) {
            return response()->json([
                'department' => '',
                'department_id' => ''
            ]);
        }

        return response()->json([
            'department' => $employee->department->department, // or 'name' based on your column
            'department_id' => $employee->department->id,
        ]);
    }

}
