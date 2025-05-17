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
        $lastEmployee = Employee::latest('id')->first();
        $departments = Department::all();
        $employees = Employee::all();
        $positions = Position::all();
        $lastEmployeeId = Employee::orderBy('id', 'desc')->value('employeeID');

        if ($lastEmployeeId) {
            // Extract numeric part from last ID
            preg_match('/^([A-Z]+)(\d+)$/', $lastEmployeeId, $matches);
            $prefix = $matches[1] ?? 'AIS';
            $number = isset($matches[2]) ? (int) $matches[2] + 1 : 1;
        } else {
            $prefix = 'AIS';
            $number = 1;
        }

        $nextEmployeeId = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

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
                'holidayGroup' => !empty($request->holidayGroup) ? $request->holidayGroup : null,
                'role' => !empty($request->role) ? $request->role : null,
                'status' => !empty($request->status) ? $request->status : null,
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
            'address' => !empty($request->address) ? $request->address : null,
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

    public function userProfile($userid){

        $user = User::with('employee','leave_allocation','user_leaves')->find($userid);
        $user_leaves = $user->user_leaves;

        $pending_leave = $user_leaves->where('status', 1)->sum('leave_day_count');
        $approved_leave = $user_leaves->where('status', 2)->sum('leave_day_count');

        // This month's leave (based on from_date in current month)
        $this_month_leave = $user_leaves->filter(function ($leave) {
            return \Carbon\Carbon::parse($leave->leave_from)->format('Y-m') === now()->format('Y-m');
        })->sum('leave_day_count');

        $leave_alloted = $user->leave_allocation?->total_leaves;

        $leave_info = (object) [

            'pending_leaves' => $pending_leave,
            'approved_leaves' => $approved_leave,
            'this_month_leave' => $this_month_leave,
            'leave_alloted' => $leave_alloted
        ];

        return view('users.profile', compact('user','leave_info'));

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
        session()->invalidate();
        session()->regenerateToken();

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

        $users = User::with('employee')->get()
        ->map(function ($users) {
            return [
                'id' => $users->id,
                'picture' => $users->employee ? $users->employee->profile_image : '',
                'full_name' => $users->employee ? $users->employee->full_name : 'N/A',
                'open_work_status' => $users->employee ? $users->employee->open_work_status : 0,
                'updated_date' => $users->employee ? date('d-m-Y', strtotime($users->employee->open_work_setdate)) : 'N/A'
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





}
