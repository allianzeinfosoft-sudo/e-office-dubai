<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Requests\LeaveRequest;
use App\Mail\LeaveApplication;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveAllocation;
use App\Models\LeaveApprovalLevel;
use App\Models\LeaveApprover;
use App\Models\User;
use App\Notifications\LeaveNotification;
use App\Traits\DateFormatter;
use App\Traits\HasLeaveRecipients;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Helpers\NotificationHelpers;

class LeaveController extends Controller
{
    use DateFormatter, HasLeaveRecipients;
    public function index()
    {

            $user_id = Auth::user()->id;
            $current_year = (string) date('Y');
            $leave_account_details = LeaveAllocation::whereRaw("TRIM(user_id) = ? AND TRIM(year) = ?", [$user_id, $current_year])->first();
            return view('leave.summary',compact('leave_account_details'));


    }
    public function leave_list()
    {
        $user_id = Auth::user()->id;
        $leaves = Leave::with('employee', 'user')
                    ->when(auth()->user()->hasRole('G5'), function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->when(auth()->user()->hasAnyRole(['G4', 'G3']), function ($query) use ($user_id) {
                        // Subquery to get user_ids of employees who report to current user
                        $reportingUserIds = Employee::where('reporting_to', $user_id)
                        ->pluck('user_id')
                        ->toArray();
                        $userIds = collect($reportingUserIds)
                        ->push($user_id)
                        ->map(fn($id) => (int) $id)
                        ->unique()
                        ->values()
                        ->toArray();

                        $query->whereIn('user_id', $userIds);
                    })
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
                        'leave_count' => $leaves->leave_day_count ?? '0.0',
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
        $users = User::with('employee')->where('username','!=','administrator')->get();
        return view('leave.custom_leave_apply',compact('users'));
    }


    public function show($id)
    {

    }

    public function edit($id)
    {
        $leave = Leave::where('id',$id)->first();
        return view('leave.apply',compact('leave'));
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
        if($request->leave_category === 'leave')
        {
            if($request->leave_type === 'half_day')
            {
                $leave_days = 0.5;
                $leave_type = 'half_day';
            }
            else
            {
                $leave_days = Leave::calculateDaysBetween($request->leave_from, $request->leave_to);
                $leave_type = 'full_day';
            }

        }else{

            if($request->leave_type === 'half_day')
            {
                $leave_days = 0.5;
                $leave_type = 'off_day';
            }
            else
            {
                $leave_days = Leave::calculateDaysBetween($request->leave_from, $request->leave_to);
                $leave_type = 'off_day';
            }
        }


        $user_info = User::find($user_id);
        $user_department = $user_info->employee->department_id;
        $current_leave_days = Leave::getTotalLeavesTakenInCurrentMonth();

        $total_leave_days = $leave_days +  $current_leave_days;


        if($total_leave_days <= 1)
         {
            $approver = $user_info->employee->reporting_to;
         }
         elseif($total_leave_days > 1 && $total_leave_days <= 3)
         {
            $approver = LeaveApprovalLevel::where('department',$user_department)->where('approval_level',2)->value('approver');
         }
         else
         {
            $approver = User::where('email', 'binojn@mail.allianzegroup.com')->first()?->id;
         }

        $leaveData = [
            'user_id'        => $request->user_id,
            'leave_from'     => $request->leave_from,
            'leave_to'       => $request->leave_to,
            'reason'         => $request->reason,
            'leave_type'     => $leave_type,
            'leave_day_count' => $leave_days,
            'leave_category' => $request->leave_category,
            'initial_approver_id' => $approver,
        ];

        $leave = Leave::create($leaveData);
        $leaveId = $leave->id;

        // if($request->leave_type === 'half_day')
        // {
        CustomHelper::updateHalfdayLeaveIncompleteStatus($request->user_id, $request->leave_from);
        // }

        // store approver
        $user_details = Employee::select('full_name', 'employeeID','reporting_to')
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
              // Optional: Get dynamically
            'employee'   => $user_details,
            'leave_details'   => $leave,
            'days_count'      => $totalLeaveDays,
            'employee_email'  => Auth::user()->email ?? 'no-email@example.com',
        ];

        $type = 'leave_apply';
        // Mail::to('allianzeinfosoftsdu@gmail.com')->send(new LeaveApplication($leaveDetails));

        // $recipients = $this->getLeaveRecipients($user_details)->toArray();
        // $message = 'New leave application received from'.$user_details->full_name;
        // NotificationHelpers::createNotification([
        //     'type' => 'leave',
        //     'recipients' => $recipients,
        //     'message' => $message,
        // ]);

        // email notification
        $data['details'] = [
            'start_date' => $request->leave_from,
            'end_date' => $request->leave_to,
            'leave_reason' => strip_tags($request->reason),
            'employee_name' =>  $user_details->full_name,
            'employeeID' => $user_details->employeeID,
            'leave_type' => $leave_type,
            'days_count' => $leave_days,

        ];
        // Send notification email
        $email=[];
        $apporver_email = '';

        $htmlBody = view('emails.leave_application_template', $data)->render();
        $apporver_email = User::find($approver)?->email;
        $hr_mail =  'hr@mail.allianzegroup.com';
        if (!empty($approver_email))
        {
            $email[] = $approver_email;
        }
        $email[] = $hr_mail;

        if($email)
        {
            CustomHelper::sendNotificationMail(
                $email,
                'New Leave Application',
                $htmlBody,
            );
        }

        // $recipient_info = User::where('id', $user_details->reporting_to)->get();
        // Notification::send($recipient_info, new LeaveNotification($leave, $user_details, $type, $recipient_info));
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
                'leave_count' => $leaves->leave_day_count ?? '',
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
        $user_id = Auth::user()->id;
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


            $leaves = Leave::with('employee','user','initialApprover')

                    ->when(auth()->user()->hasRole('G5'), function ($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->when(auth()->user()->hasAnyRole(['G4', 'G3']), function ($query) use ($user_id) {
                        // Subquery to get user_ids of employees who report to current user
                        $reportingUserIds = Employee::where('reporting_to', $user_id)
                        ->pluck('user_id')
                        ->toArray();
                        $userIds = collect($reportingUserIds)
                        ->push($user_id)
                        ->map(fn($id) => (int) $id)
                        ->unique()
                        ->values()
                        ->toArray();

                        $query->whereIn('user_id', $userIds);
                    })

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
                    'leave_count' => $leaves->leave_day_count ?? '0',
                    'status' => $leaves->status ?? '',
                    'this_month_leave_count' => $thisMonthLeaveCount,
                    'leave_approver' => $leaves->initial_approver_id,
                    'initial_approver_name' => $leaves->initialApprover?->full_name,
                    'initial_approver_role' => $leaves->initialApprover?->role,
                    'initial_approver_designation' => $leaves->initialApprover?->designation?->designation ?? 'N/A',
                    'init_appr_status' => $leaves->initial_approve_status,
                    'login_user' => Auth::user()->id,
                    'login_user_group' => Auth::user()->employee?->group,
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
        $approver = Auth::user();
        $id = $request['modalLeaveId'];
        $action = $request['modalFunctionType'];

        if($action == 1)
        {
            $leave = Leave::find($id);
            if ($leave) {

                if($approver->role != 'HR')
                {
                    $leave->initial_approve_status = 1;
                    $leave->initial_approver_id = $approver->id;
                    $leave->initial_approved_date = date('Y-m-d');
                    if($leave->save())
                    {
                        // return redirect()->back()->with('success', 'Leave Approved successfully!');
                        return response()->json(['success' => true, 'message' => 'Leave Approved successfully!']);

                    }

                }
                $leave->status = 2;
                $leave->comment = $request['comment'];
                $leave->approved_cancel_date = date('Y-m-d H:i:s');
                if($leave->save())
                {
                    $startDate = Carbon::parse($leave->leave_from);
                    $endDate = Carbon::parse($leave->leave_to);


                     if($leave->leave_type === 'half_day')
                    {
                        $leaveDays = 0.5;
                    }
                    else
                    {
                        $leaveDays = $leaveDays = $startDate->diffInDays($endDate) + 1;

                    }

                    if($leave->leave_category != 'off_day')
                    {
                        LeaveAllocation::where('user_id', $leave->user_id)
                            ->update([
                                'remaining_leaves' => DB::raw('remaining_leaves - ' . $leaveDays),
                                'used_leaves' => DB::raw('used_leaves + ' . $leaveDays),
                            ]);
                    }



                    // $recipients = [(string) $leave->user_id];
                    // $message = 'Leave application approved';

                    // NotificationHelpers::createNotification([
                    //     'type' => 'leave',
                    //     'recipients' => $recipients,
                    //     'message' => $message,
                    // ]);

                    $data['details'] = [
                        'start_date' => $leave->leave_from,
                        'end_date' => $leave->leave_to,
                        'leave_reason' => strip_tags($leave->reason),
                        'employee_name' =>  $leave->employee->full_name,
                        'employeeID' => $leave->employee->employeeID,
                        'leave_type' => $leave->leave_type,
                        'days_count' => $leave->leave_day_count,

                    ];
                    // Send notification email
                    $htmlBody = view('emails.leave_approve_template', $data)->render();
                    $email = $leave->user->email ?? '';
                    if($email)
                    {
                        CustomHelper::sendNotificationMail(
                            $email,
                            'Your Leave Application Is Approved',
                            $htmlBody,
                        );
                    }

                    // return redirect()->back()->with('success', 'Leave Approved successfully!');
                    return response()->json(['success' => true, 'message' => 'Leave Approved successfully!']);

                }
                return response()->json(['error' => true, 'message' => 'Failed to update leave status!']);
            } else {
                return response()->json(['error' => true, 'message' => 'Invalid user!']);
            }
        }
        elseif($action == 2)
        {
            $leave = Leave::find($id);
            if ($leave) {

                if($approver->role != 'HR')
                {
                    $leave->initial_approve_status = 1;
                    $leave->initial_approver_id = $approver->id;
                    $leave->initial_approver_id = $approver->id;
                    $leave->initial_approved_date = date('Y-m-d');
                    $leave->approved_cancel_date = date('Y-m-d');
                    $leave->status = 3;
                    if($leave->save())
                    {
                        return redirect()->back()->with('success', 'Leave Rejected successfully!');
                    }

                }

                $leave->status = 3;
                $leave->comment = $request['comment'];
                $leave->approved_cancel_date = date('Y-m-d H:i:s');
                $leave->save();

                // $recipients = [(string) $leave->user_id];
                // $message = 'Leave application rejected';
                // NotificationHelpers::createNotification([
                //     'type' => 'leave',
                //     'recipients' => $recipients,
                //     'message' => $message,
                // ]);

                $data['details'] = [
                    'start_date' => $leave->leave_from,
                    'end_date' => $leave->leave_to,
                    'leave_reason' => strip_tags($leave->reason),
                    'employee_name' =>  $leave->employee->full_name,
                    'employeeID' => $leave->employee->employeeID,
                    'leave_type' => $leave->leave_type,
                    'days_count' => $leave->leave_day_count,

                ];
                // Send notification email
                $htmlBody = view('emails.leave_reject_template', $data)->render();
                $email = $leave->user->email ?? '';
                if($email)
                {
                    CustomHelper::sendNotificationMail(
                        $email,
                        'Your Leave Application Is Rejected',
                        $htmlBody,
                    );
                }


                // return redirect()->back()->with('success', 'Leave Rejected successfully!');
                return response()->json(['success' => true, 'message' => 'Leave Approved successfully!']);




            } else {
                return response()->json(['error' => true, 'message' => 'Invalid user!']);
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
            'total_leaves' => 'required|numeric|min:0',
            'remaining_leaves' => 'required|numeric|min:0',
        ]);

        try {

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
        $leave = Leave::findOrFail($id); // Will throw 404 if not found

        $leave_count = $leave->leave_day_count;
        $year = Carbon::parse($leave->leave_from)->year;
        $user_id = $leave->user_id;

        if ($leave->delete()) {

            $leaveBalance = LeaveAllocation::where('user_id', $user_id)
            ->where('year', $year)
            ->first();

            if ($leaveBalance) {
                $leaveBalance->used_leaves = (float) $leaveBalance->used_leaves - $leave_count;
                $leaveBalance->remaining_leaves = (float) $leaveBalance->remaining_leaves + $leave_count;
                $leaveBalance->save();
            }


            return response()->json(['success' => true, 'message' => 'Leave deleted successfully']);
        } else {
            return response()->json(['error' => true, 'message' => 'Leave deletion failed']);
        }

    }

    public function leave_approver(Request $request)
    {
          /* ajax request */

        if ($request->ajax()) {

            $leaveApprover = LeaveApprovalLevel::get()
            ->map(function ($leaveApprover) {
                return [
                    'id' => $leaveApprover->id,
                    'department' => $leaveApprover->department ? $leaveApprover->dept->department : '',
                    'approver' => $leaveApprover->employee ? $leaveApprover->employee->full_name : '',
                    'level' => $leaveApprover->approval_level ? $leaveApprover->approval_level : '',
                    // 'count' => $leaveApprover->approve_count ? $leaveApprover->approve_count : '',
                ];
            });

            return response()->json([
                'data' => $leaveApprover
            ]);

        }

        //
        $data['meta_title'] = 'Leave Approvers';
        return view('settings.leave_approval_level', $data);
    }

    public function leave_approval_store(Request $request)
    {
        $banner = LeaveApprovalLevel::create([
                'department'   => $request->department,
                'approver'    => $request->approver,
                'approval_level'  => $request->approval_level,
                // 'approve_count'  => $request->approve_count
            ]);

        return redirect()->back()->with('success', 'Leave approver created successfully!');
    }

   public function leave_approval_delete($id)
    {
        $leave_approver = LeaveApprovalLevel::find($id);
        if (!$leave_approver) {
            return response()->json(['success' => false, 'message' => 'Approver not found'], 404);
        }

        $leave_approver->delete();

        return response()->json(['success' => true, 'message' => 'Leave deleted successfully']);
    }

    public function checkOverlap(Request $request)
    {
        $userId = $request->user_id;
        $from = $request->leave_from;
        $to = $request->leave_to;

        $overlap = Leave::where('user_id', $userId)
            ->whereIn('status', [1, 2])
            ->where(function($q) use ($from, $to) {
                $q->whereBetween('leave_from', [$from, $to])
                ->orWhereBetween('leave_to', [$from, $to])
                ->orWhere(function ($query) use ($from, $to) {
                    $query->where('leave_from', '<=', $from)
                            ->where('leave_to', '>=', $to);
                });
            })
            ->exists();

        return response()->json(['overlap' => $overlap]);
    }


    public function leave_summary_filter( Request $request)
    {

        $user = auth()->user();
        $user_id = $user->id;
        $query = Leave::with(['employee', 'user','initialApprover']);

        if ($user->hasRole('G5')) {
            $query->where('user_id', $user_id);
        }

        if ($user->hasAnyRole(['G4', 'G3'])) {
            $reportingUserIds = Employee::where('reporting_to', $user_id)
                ->pluck('user_id')
                ->toArray();

            $userIds = collect($reportingUserIds)
                ->push($user_id)
                ->unique()
                ->values()
                ->toArray();

            $query->whereIn('user_id', $userIds);
        }

        if ($request->filled('filter_from_date')) {
            $month = $request->input('filter_from_date'); // e.g. "2024-05"
            $startDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            $query->whereBetween('leave_from', [$startDate, $endDate]);
        }

        $leave_summary = $query->get()->map(function ($leave) {
            return [
                'id' => $leave->id,
                'full_name' => $leave->employee->full_name ?? '',
                'employee_id' => $leave->employee->id ?? '',
                'leave_from' => $leave->leave_from ? $this->formatDateDayMonthYear($leave->leave_from) : '',
                'leave_to' => $leave->leave_to ? $this->formatDateDayMonthYear($leave->leave_to) : '',
                'leave_type' => $leave->leave_type ?? '',
                'leave_reason' => $leave->reason ?? '',
                'apply_date' => $leave->created_at ? $this->formatDateDayMonthYear($leave->created_at) : '',
                'approved_cancel_date' => $leave->approved_cancel_date ? $this->formatDateDayMonthYear($leave->approved_cancel_date) : '',
                'leave_count' => $leave->leave_day_count ?? '0.0',
                'initial_approve_status' => $leave->initial_approve_status,
                'initial_approver' => $leave->initialApprover->full_name ?? 'N/A',
                'initial_approver_role' => $leave->initialApprover?->role ?? 'N/A',
                'initial_approver_designation' => $leave->initialApprover?->designation?->designation ?? 'N/A',
                'status' => $leave->status ?? ''
            ];
        });

        return response()->json(['data' => $leave_summary]);

    }

    public function check_leave_allocated($userId)
    {
        $year = request()->get('year', now()->year); // fallback to current year
        $allocation = LeaveAllocation::where('user_id', $userId)
            ->where('year', $year)
            ->first();

        if (!$allocation) {
            return response()->json(['error' => 'Leave is not allocated for this user for year ' . $year], 404);
        }

        return response()->json(['success' => true]);
    }


}
