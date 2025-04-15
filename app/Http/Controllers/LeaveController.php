<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Mail\LeaveApplication;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\User;
use App\Notifications\LeaveNotification;
use App\Traits\DateFormatter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    use DateFormatter;
    public function index()
    {

            $user_id = Auth::user()->id;
            $current_year = date('Y');
            $leave_account_details = LeaveAllocation::where(['user_id'=>$user_id, 'year' => $current_year])->first();
            // dd($leave_account_details);
            return view('leave.summary',compact('leave_account_details'));


    }
    public function leave_list()
    {
        $leaves = Leave::with('employee','user')
                ->get()
                ->map(function ($leaves) {
                    return [
                        'id' => $leaves->id,
                        'full_name' => $leaves->employee->full_name ?? '',
                        'employee_id' => $leaves->employee->id ?? '',
                        'leave_from' => $leaves->leave_from ? $this->formatDateDayMonthYear($leaves->leave_from) : '',
                        'leave_to' => $leaves->leave_to ? $this->formatDateDayMonthYear($leaves->leave_to) : '',
                        'leave_type' => $leaves->leave_type ?? '',
                        'leave_reason' => $leaves->reason ?? '',
                        'apply_date' => $leaves->created_at ? $this->formatDateDayMonthYear($leaves->created_at) : '',
                        'approved_cancel_date' => $leaves->approved_cancel_date ? $this->formatDateDayMonthYear($leaves->approved_cancel_date) : '',
                        'leave_count' => $this->getDaysBetween($leaves->leave_from, $leaves->leave_to) ?? '',
                        'status' => $leaves->status ?? ''
                    ];
                });

        $response = response()->json(['data' => $leaves]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('leave.apply');
    }

    public function custom_leave()
    {
        $users = User::with('employee')->get();
        return view('leave.custom_leave_apply',compact('users'));
    }


    public function show($id)
    {

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'       => 'required|exists:users,id',
            'leave_from'    => 'required|date|before_or_equal:leave_to',
            'leave_to'      => 'required|date|after_or_equal:leave_from',
            'reason'        => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }



        $user_id = $request['user_id'];
        $leaveData = [
            'user_id'        => $request->user_id,
            'leave_from'     => $request->leave_from,
            'leave_to'       => $request->leave_to,
            'reason'         => $request->reason,
            'leave_type'     => $request->leave_type,
            'leave_category' => $request->leave_category,
        ];

        $leave = Leave::create($leaveData);
        $user_details = Employee::select('full_name', 'employeeID')
                            ->where('user_id', $request->user_id)
                            ->first();

        if (!$user_details) {
            return redirect()->back()->with('error', 'Invalid user!');
        }

        $startDate = Carbon::parse($leave->leave_from);
        $endDate   = Carbon::parse($leave->leave_to);

        $totalLeaveDays = $leave->leave_type === 'half_day'
        ? 0.5
        : $endDate->diffInDays($startDate) + 1;


        $leaveDetails = [
            'manager_name'    => 'John Doe', // Optional: Get dynamically
            'employee_name'   => $user_details->full_name,
            'employee_id'     => $user_details->employeeID,
            'leave_type'      => $leave->leave_type,
            'start_date'      => $leave->leave_from,
            'end_date'        => $leave->leave_to,
            'days_count'      => $totalLeaveDays,
            'leave_reason'    => $leave->reason,
            'employee_email'  => Auth::user()->email ?? 'no-email@example.com',
        ];

        // Mail::to('allianzeinfosoftsdu@gmail.com')->send(new LeaveApplication($leaveDetails));
        $admin = User::where('role', 'Admin')->get();
        Notification::send($admin, new LeaveNotification($leave, $user_details));
        return redirect()->back()->with('success', 'Leave created successfully!');

    }

    public function show_leave_status()
    {
       return view('leave.leave_status');
    }

    public function leave_status($user_id)
    {

        $leaves = Leave::with('employee','user')
        ->where('status','=',1)->where('user_id',$user_id)
        ->with('employee','user') // Ensure roles relationship is loaded
        ->get()
        ->map(function ($leaves) {
            return [
                'id' => $leaves->id,
                'full_name' => $leaves->employee->full_name ?? '',
                'employee_id' => $leaves->employee->id ?? '',
                'leave_from' => $leaves->leave_from ? $this->formatDateDayMonthYear($leaves->leave_from) : '',
                'leave_to' => $leaves->leave_to ? $this->formatDateDayMonthYear($leaves->leave_to) : '',
                'leave_type' => $leaves->leave_type ?? '',
                'leave_reason' => $leaves->reason ?? '',
                'apply_date' => $leaves->created_at ? $this->formatDateDayMonthYear($leaves->created_at) : '',
                'approved_cancel_date' => $leaves->approved_cancel_date ? $this->formatDateDayMonthYear($leaves->approved_cancel_date) : '',
                'leave_count' => $this->getDaysBetween($leaves->leave_from, $leaves->leave_to) ?? '',
                'status' => $leaves->status ?? ''
            ];
        });

    $response = response()->json(['data' => $leaves]);
    $json_data = json_decode($response->getContent(), true)['data'];
    return json_encode(['data' => $json_data]);

    }

    public function leave_pending_show()
    {
        return view('leave.pending_leaves');
    }

    public function pending_leaves()
    {
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        // Get count of leaves for this month
        $thisMonthLeaveCount = Leave::where('status', 1)
                            ->get()
                            ->sum(function ($leave) use ($currentMonthStart, $currentMonthEnd) {
                                $leaveFrom = Carbon::parse($leave->leave_from);
                                $leaveTo = Carbon::parse($leave->leave_to);

                                // Get the effective leave period within this month
                                $effectiveFrom = $leaveFrom->greaterThanOrEqualTo($currentMonthStart) ? $leaveFrom : $currentMonthStart;
                                $effectiveTo = $leaveTo->lessThanOrEqualTo($currentMonthEnd) ? $leaveTo : $currentMonthEnd;

                                // Ensure valid range
                                if ($effectiveFrom->greaterThan($effectiveTo)) {
                                    return 0;
                                }

                                // Calculate the number of leave days in this month
                                return $effectiveFrom->diffInDays($effectiveTo) + 1;
                            });


            $leaves = Leave::with('employee','user')
            ->where('status','=',1)
            ->get()
            ->map(function ($leaves) use ($thisMonthLeaveCount) {
                return [
                    'id' => $leaves->id,
                    'user_id' =>$leaves->user_id,
                    'full_name' => $leaves->employee->full_name ?? 'N/A',
                    'avatar' => $leaves->employee->profile_image ?? '',
                    'employee_id' => $leaves->employee->id ?? 'N/A',
                    'leave_from' => $this->formatDateDayMonthYear($leaves->leave_from) ?? '',
                    'leave_to' => $this->formatDateDayMonthYear($leaves->leave_to) ?? '',
                    'leave_type' => $leaves->leave_type ?? '',
                    'leave_reason' => strip_tags($leaves->reason ?? ''),
                    'apply_date' => $leaves->created_at ? $this->formatDateDayMonthYear($leaves->created_at) : '',
                    'approved_cancel_date' => $leaves->approved_cancel_date ? $this->formatDateDayMonthYear($leaves->approved_cancel_date) : '',
                    'leave_count' => $this->getDaysBetween($leaves->leave_from, $leaves->leave_to) ?? '',
                    'status' => $leaves->status ?? '',
                    'this_month_leave_count' => $thisMonthLeaveCount,
                ];
            });

        $response = response()->json(['data' => $leaves]);
        $json_data = json_decode($response->getContent(), true)['data'];

        return json_encode(['data' => $json_data]);

    }


    public function leave_action(Request $request)
    {
        $request->validate([

            'modalLeaveId' => 'required',
            'modalFunctionType' => 'required'
        ]);

        $id = $request['modalLeaveId'];
        $action = $request['modalFunctionType'];

        if($action == 1)
        {

            $leave = Leave::find($id);
            if ($leave) {
                $leave->status = 2;
                $leave->approved_cancel_date = date('Y-m-d H:i:s');
                if($leave->save())
                {
                    $startDate = Carbon::parse($leave->leave_from);
                    $endDate = Carbon::parse($leave->leave_to);
                    $leaveDays = $leaveDays = $startDate->diffInDays($endDate) + 1;

                    LeaveAllocation::where('user_id', $leave->user_id)
                    ->decrement('remaining_leaves', $leaveDays);

                    LeaveAllocation::where('user_id', $leave->user_id)
                    ->increment('used_leaves', $leaveDays);

                    return redirect()->back()->with('success', 'Leave Approved successfully!');
                }
                return redirect()->back()->with('error', 'Failed to update leave status!');
            } else {
                return redirect()->back()->with('error', 'Invalid user!');
            }
        }
        elseif($action == 2)
        {
            $leave = Leave::find($id);
            if ($leave) {
                $leave->status = 3;
                $leave->approved_cancel_date = date('Y-m-d H:i:s');
                $leave->save();
                return redirect()->back()->with('success', 'Leave Rejected successfully!');
            } else {
                return redirect()->back()->with('error', 'Invalid user!');
            }
        }



    }

    public function leave_allocation()
    {
        return view('leave.leave_allocate');
    }

    public function allocated_leaves()
    {

        // $users_leaves = LeaveAllocation::with('employee','user')->get()
        //             ->map(function ($users_leaves) {
        //                 return [
        //                     'id' => $users_leaves->id,
        //                     'user_id' => $users_leaves->user_id ?? '',
        //                     'full_name' => $users_leaves->employee->full_name ?? '',
        //                     'total_leaves' => $users_leaves->total_leaves ?? '',
        //                     'used_leaves' => $users_leaves->used_leaves ?? '',
        //                     'remaining_leaves' => $users_leaves->remaining_leaves ?? '',
        //                     'year' => $users_leaves->leave_type ?? '',
        //                 ];
        //             });

        $thisyear = date('Y');
        $users_leaves = Employee::leftJoin('leave_allocations', function($join) use ($thisyear) {
            $join->on('employees.user_id', '=', 'leave_allocations.user_id')
            ->where('leave_allocations.year', $thisyear);
                })
                ->Where('employees.status',2)
                ->select(
                    'employees.user_id as userId', // User ID
                    'employees.full_name',
                    DB::raw('COALESCE(leave_allocations.id, 0) as leave_id'), // Leave ID from AllotedLeave
                    DB::raw('COALESCE(leave_allocations.total_leaves, 0) as total_leaves'),
                    DB::raw('COALESCE(leave_allocations.used_leaves, 0) as used_leaves'),
                    DB::raw('COALESCE(leave_allocations.remaining_leaves, 0) as remaining_leaves'),
                    DB::raw('COALESCE(leave_allocations.year, 0) as year'),
                    'leave_allocations.created_at',
                    'leave_allocations.updated_at'
                )->get()
                ->map(function ($users_leaves) {
                        return [
                            'id' => $users_leaves->leave_id,
                            'user_id' => $users_leaves->userId ?? '',
                            'full_name' => $users_leaves->full_name ?? '',
                            'total_leaves' => $users_leaves->total_leaves ?? '',
                            'used_leaves' => $users_leaves->used_leaves ?? '',
                            'remaining_leaves' => $users_leaves->remaining_leaves ?? '',
                            'year' => $users_leaves->year ?? '',
                        ];
                    });




        $response = response()->json(['data' => $users_leaves]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);
    }

    public function checkLeave(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer|min:2020|max:2029',
        ]);

        // Check if a leave record exists for the given user and year
        $leaveExists = LeaveAllocation::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->exists();

        return response()->json([
            'leave_exists' => $leaveExists,
            'message' => $leaveExists
                ? 'Leave record found for this year.'
                : 'No leave record found for this year.'
        ]);
    }


    public function getLeaveDetails(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer|min:2020|max:2029',
        ]);

        $leaveDetails = LeaveAllocation::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->first();

        if ($leaveDetails) {
            return response()->json([
                'total_leaves' => $leaveDetails->total_leaves,
                'used_leaves' => $leaveDetails->used_leaves,
                'remaining_leaves' => $leaveDetails->remaining_leaves
            ]);
        }

        return response()->json(null, 404); // Return an error if no record exists
    }

    public function updateLeaveAllocation(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'user_id' => 'required', // Allow null for new records
            'year' => 'required|integer',
            'total_leaves' => 'required|integer|min:0',
            'remaining_leaves' => 'required|integer|min:0',
        ]);

        try {
            // Find the leave record or create a new one
            $leave = LeaveAllocation::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'year' => $request->year,
                ],
                [
                    'total_leaves' => $request->total_leaves,
                    'remaining_leaves' => $request->remaining_leaves,
                ]
            );

            return response()->json(['success' => true, 'leave' => $leave]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();
        return response()->json(['success' => true, 'message' => 'Leave deleted successfully']);
    }




}
