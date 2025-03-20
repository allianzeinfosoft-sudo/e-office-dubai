<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveRequest;
use App\Mail\LeaveApplication;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use App\Traits\DateFormatter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    use DateFormatter;

    public function index()
    {
        return view('leave.summary');
    }
    public function leave_list()
    {

        $leaves = Leave::select(
                    'leaves.id',
                    'leaves.leave_from',
                    'leaves.leave_to',
                    'leaves.leave_type',
                    'leaves.reason',
                    'leaves.approved_cancel_date',
                    'leaves.created_at',
                    'leaves.status'
                )->where('status','!=',1)
                ->with('employee','user') // Ensure roles relationship is loaded
                ->get()
                ->map(function ($leaves) {
                    return [
                        'id' => $leaves->id,
                        'full_name' => $leaves->employee->full_name ?? '',
                        'employee_id' => $leaves->employee->id ?? '',
                        'leave_from' => $leaves->leave_from ?? '',
                        'leave_to' => $leaves->leave_to ?? '',
                        'leave_type' => $leaves->leave_type ?? '',
                        'leave_reason' => $leaves->reason ?? '',
                        'apply_date' => $leaves->created_at ? $this->formatDateTime12Hour($leaves->created_at) : '',
                        'approved_cancel_date' => $leaves->approved_cancel_date ? $this->formatDateTime12Hour($leaves->approved_cancel_date) : '',
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveRequest $request)
    {

        $user_id = Auth::user()->id;
        $leaveData = [
            'user_id'     => $user_id,
            'leave_from'  => $request->leave_from,
            'leave_to'    => $request->leave_to,
            'reason'      => $request->reason,
            'leave_type'  => $request->leave_type,
            'leave_category' => $request->leave_category,
        ];

        $leave = Leave::create($leaveData);
        $user_details = Employee::select('full_name','employeeID')->where('user_id',$user_id)->first();
       if($user_details)
       {
        $startDate = \Carbon\Carbon::parse($leave->start_date);
        $endDate = \Carbon\Carbon::parse($leave->end_date);

        $totalLeaveDays = $endDate->diffInDays($startDate) + 1;

        if ($leave->leave_type == 'half_day')
        { $totalLeaveDays = 0.5; }


        $leaveDetails = [
            'manager_name' => 'John Doe',
            'employee_name' => $user_details->full_name,
            'employee_id' => $user_details->employeeID,
            'leave_type' => $request->leave_type,
            'start_date' => $request->leave_from,
            'end_date' => $request->leave_to,
            'days_count' => $totalLeaveDays,
            'leave_reason' => $request->reason,
            'employee_email' => Auth::user()->email,
        ];

        Mail::to('allianzeinfosoftsdu@gmail.com')->send(new LeaveApplication($leaveDetails));
        return redirect()->back()->with('success', 'Leave created successfully!');
       }
       else
       {
        return redirect()->back()->with('error', 'Invalid user!');
       }
    }

    public function show_leave_status()
    {
       return view('leave.leave_status');
    }

    public function leave_status($user_id)
    {

        $leaves = Leave::select(
            'leaves.id',
            'leaves.leave_from',
            'leaves.leave_to',
            'leaves.leave_type',
            'leaves.reason',
            'leaves.approved_cancel_date',
            'leaves.created_at',
            'leaves.status'
        )->where('status','=',1)->where('user_id',$user_id)
        ->with('employee','user') // Ensure roles relationship is loaded
        ->get()
        ->map(function ($leaves) {
            return [
                'id' => $leaves->id,
                'full_name' => $leaves->employee->full_name ?? '',
                'employee_id' => $leaves->employee->id ?? '',
                'leave_from' => $leaves->leave_from ?? '',
                'leave_to' => $leaves->leave_to ?? '',
                'leave_type' => $leaves->leave_type ?? '',
                'leave_reason' => $leaves->reason ?? '',
                'apply_date' => $leaves->created_at ? $this->formatDateTime12Hour($leaves->created_at) : '',
                'approved_cancel_date' => $leaves->approved_cancel_date ? $this->formatDateTime12Hour($leaves->approved_cancel_date) : '',
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

            $leaves = Leave::select(
                'leaves.id',
                'leaves.leave_from',
                'leaves.leave_to',
                'leaves.leave_type',
                'leaves.reason',
                'leaves.approved_cancel_date',
                'leaves.created_at',
                'leaves.status'
            )->where('status','=',1)
            ->with('employee','user') // Ensure roles relationship is loaded
            ->get()
            ->map(function ($leaves) {
                return [
                    'id' => $leaves->id,
                    'full_name' => $leaves->employee->full_name ?? '',
                    'employee_id' => $leaves->employee->id ?? '',
                    'leave_from' => $leaves->leave_from ?? '',
                    'leave_to' => $leaves->leave_to ?? '',
                    'leave_type' => $leaves->leave_type ?? '',
                    'leave_reason' => $leaves->reason ?? '',
                    'apply_date' => $leaves->created_at ? $this->formatDateTime12Hour($leaves->created_at) : '',
                    'approved_cancel_date' => $leaves->approved_cancel_date ? $this->formatDateTime12Hour($leaves->approved_cancel_date) : '',
                    'leave_count' => $this->getDaysBetween($leaves->leave_from, $leaves->leave_to) ?? '',
                    'status' => $leaves->status ?? ''
                ];
            });

        $response = response()->json(['data' => $leaves]);
        $json_data = json_decode($response->getContent(), true)['data'];
        return json_encode(['data' => $json_data]);

    }



}
