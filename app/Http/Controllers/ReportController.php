<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\workReport;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Helpers\CustomHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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

    /* My overview */
    public function my_overview(Request $request) {
        $selected_user = $request->input('user') ?? auth()->id();
        $selected_year = $request->input('year'); // optional, pass to helpers

        // Monthly hours
        $monthlyData = CustomHelper::getMonthlyTotalHours($selected_user, $selected_year);
        $data['labels']        = $monthlyData['months'];
        $data['average_hours'] = $monthlyData['total_hours'];

        // Reports
        $data['work_analysis']  = CustomHelper::getWorkRatingAnalysis($selected_user, $selected_year);
        $data['monthly_report'] = CustomHelper::getMonthlyWorkReport($selected_user, $selected_year);

        // Attendance + Leave
        $data['attendance_analytics'] = CustomHelper::currentAttendanceAnalytics($selected_user, $selected_year);
        $data['leave_stats']          = CustomHelper::getEmployeeLeaveStats($selected_user, $selected_year);

        // Other info
        $data['current_user'] = Employee::with('designation')->where('user_id', $selected_user)->first();
        $data['employees']    = Employee::all();
        $data['meta_title']   = 'User Overview';

        return view('reports.my-over-view', $data);
    }

    /* My Attendnce Report */

    public function myAttendanceReport(Request $request){
        $data['meta_title'] = 'My Attendance Report';
        $data['current_user'] = Employee::where('user_id', auth()->user()->id)->first();
        $data['month'] = $request->input('month') ?? date('m');
        $data['year']  = $request->input('year') ?? date('Y');

        $employee = $data['current_user'];

        $startDate = Carbon::createFromDate($data['year'], $data['month'], 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($data['year'], $data['month'], 1)->endOfMonth();

        // Attendance data keyed by signin_date
        $attendances = Attendance::where('emp_id', $employee->user_id)
            ->whereBetween('signin_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->signin_date)->format('Y-m-d');
            });

        // Holidays in the month
        $holidays = DB::table('holidays')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('date')
            ->toArray();

        // Final report rows
        $report = [];
        $currentDate = $startDate->copy();
        $serial = 1;

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayName = $currentDate->format('l'); // Sunday, Monday, etc.

            $row = [
                '#' => $serial++,
                'signin_date'   => date('d-m-Y', strtotime($dateStr)),
                'signin_time'   => '',
                'signout_time'  => '',
                'break_time'    => '',
                'working_hours' => '',
                'signin_note'   => '',
                'signout_note'  => '',
                'status'        => '',
            ];

            if (isset($attendances[$dateStr])) {
                $att = $attendances[$dateStr];

                $row['signin_date']   = date('d-m-Y', strtotime($att->signin_date));
                $row['signin_time']   = $att->signin_time;
                $row['signout_time']  = $att->signout_time;
                $row['break_time']    = $att->break_time;
                $row['working_hours'] = $att->working_hours;
                $row['signin_note']   = $att->signin_note;
                $row['signout_note']  = $att->signout_note;

                if (in_array($dateStr, $holidays)) {
                    $row['status'] = '<span class="badge bg-label-info mt-1">Holiday Worked</span>';
                } elseif (in_array($dayName, ['Saturday', 'Sunday'])) {
                    $row['status'] = '<span class="badge bg-label-primary mt-1">' . $dayName . ' Worked</span>';
                } else {
                    $row['status'] = $att->signout_time
                        ? '<span class="badge bg-label-success mt-1">Complete</span>'
                        : '<span class="badge bg-label-warning mt-1">Incomplete</span>';
                }
            } elseif (in_array($dateStr, $holidays)) {
                $row['status'] = '<span class="badge bg-label-info mt-1">Holiday</span>';
            } elseif (in_array($dayName, ['Saturday', 'Sunday'])) {
                $row['status'] = '<span class="badge bg-label-warning mt-1">' . $dayName . '</span>';
            } else {
                $row['status'] = '<span class="badge bg-label-danger mt-1">Leave</span>';
            }

            $report[] = $row;
            $currentDate->addDay();
        }

        $data['attendance_report'] = $report;
        $data['barChartData'] = CustomHelper::getMonthlyWorkBreakDataForBarChart(auth()->user()->id);

        return view('reports.my-attendance-report', $data);
    }

    /* My work Report */
      public function myWorkReport(){
        $data['meta_title'] = 'My Work Report';
        $data['employees'] = Employee::Where('user_id', auth()->user()->id)->get();
        $tasks = ProjectTask::whereRaw("FIND_IN_SET(?, members)", [auth()->user()->id])->with('project')->get();
        // Optional: return only project IDs or names
        $data['projects'] = $tasks->pluck('project')->filter()->unique('id')->values();
        return view('reports.all-work-report.my-work-report', $data);
    }

     public function userWorkReport(string $id){
        $data['meta_title'] = 'My Work Report';
        $data['employees'] = Employee::Where('user_id', auth()->user()->id)->get();
        $tasks = ProjectTask::whereRaw("FIND_IN_SET(?, members)", [auth()->user()->id])->with('project')->get();
        // Optional: return only project IDs or names
        $data['projects'] = $tasks->pluck('project')->filter()->unique('id')->values();
        return view('reports.all-work-report.my-work-report', $data);
    }


    public function myWorkReportsData(Request $request){
        $query = WorkReport::with(['project', 'projectTask', 'tasks'])
            ->where('emergency', 0);

        if ($request->filled('employee_id')) {
            $query->where('emp_id', $request->employee_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_name', $request->project_id); // Confirm if 'project_name' is correct
        }

        if ($request->filled('task_id')) {
            $query->where('type_of_work', $request->task_id); // Confirm if 'type_of_work' is correct
        }

        if ($request->filled('day')) {
            $query->whereDay('report_date', $request->day);
        }

        if ($request->filled('month')) {
            $query->whereMonth('report_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('report_date', $request->year);
        }

        $reports = $query->orderBy('report_date', 'desc')->get()->map(function ($report) {
            $report->report_date = Carbon::parse($report->report_date)->format('d-m-Y');
            return $report;
        });

        return response()->json($reports);
    }

    /* user overview */
    public function user_overview(Request $request) {
        $selected_user = $request->input('user') ?? auth()->id();
        $selected_year = $request->input('year'); // optional, pass to helpers

        // Monthly hours
        $monthlyData = CustomHelper::getMonthlyTotalHours($selected_user, $selected_year);
        $data['labels']        = $monthlyData['months'];
        $data['average_hours'] = $monthlyData['total_hours'];

        // Reports
        $data['work_analysis']  = CustomHelper::getWorkRatingAnalysis($selected_user, $selected_year);
        $data['monthly_report'] = CustomHelper::getMonthlyWorkReport($selected_user, $selected_year);

        // Attendance + Leave
        $data['attendance_analytics'] = CustomHelper::currentAttendanceAnalytics($selected_user, $selected_year);
        $data['leave_stats']          = CustomHelper::getEmployeeLeaveStats($selected_user, $selected_year);

        // Other info
        $data['current_user'] = Employee::where('user_id', $selected_user)->first();
        $data['employees']    = Employee::all();
        $data['meta_title']   = 'User Overview';

        return view('reports.user-overview.index', $data);
    }

    /* */
    public function monthlyOverview() {
        $data['meta_title'] = 'Monthly Overview';
        $data['employees']    = Employee::all();
        return view('reports.monthly-overview.index', $data);
    }

    public function monthlyOverviewReport(Request $request){
        $month = $request->month ?? now()->format('m');
        $year = $request->year ?? now()->format('Y');

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $users = Employee::all();

        $data = [];
        $minTotalHours = null;

        // 1. Get all weekdays in the month
        $allDates = CarbonPeriod::create($startDate, $endDate);
        $weekdays = collect($allDates)->filter(function ($date) {
            return !in_array($date->dayOfWeek, [0, 6]); // Exclude Sunday (0) and Saturday (6)
        });

        // 2. Get holidays in the month
        $holidays = Holiday::whereBetween('date', [$startDate, $endDate])->pluck('date')->map(fn ($d) => Carbon::parse($d)->toDateString());

        // 3. Calculate working days excluding holidays
        $workingDays = $weekdays->filter(function ($date) use ($holidays) {
            return !$holidays->contains($date->toDateString());
        })->count();

        foreach ($users as $index => $user) {
            $attendances = Attendance::where('emp_id', $user->id)
                ->whereBetween('signin_date', [$startDate, $endDate])
                ->get();

            $leaves = Leave::where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('leave_from', [$startDate, $endDate])
                        ->orWhereBetween('leave_to', [$startDate, $endDate]);
                })->count();

            $totalHours = $attendances->sum(function ($a) {
                return is_numeric($a->working_hours) ? (float) $a->working_hours : 0;
            });

            $daysWorked = $attendances->count();
            $avgHours = $daysWorked > 0 ? $totalHours / $daysWorked : 0;

            $minTotalHours = is_null($minTotalHours) ? $totalHours : min($minTotalHours, $totalHours);

            $name = $user->full_name ?? 'NA';
            $initials = collect(explode(' ', $name))
                ->filter()
                ->map(function ($word) {
                    return isset($word[0]) ? strtoupper($word[0]) : '';
                })
                ->join('');

            $initials = substr($initials, 0, 2);

            if ($user && $user->profile_image) {
                $profileImage = '<img src="' . asset('storage/' . $user->profile_image) . '" alt="Profile" width="40" height="40" class="rounded-circle">';
            } else {
                $profileImage = '<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">' . $initials . '</div>';
            }

            $data[] = [
                'index' => $index + 1,
                'name' => $user->full_name,
                'month' => $startDate->format('F Y'),
                'avg_working_hours' => number_format($avgHours, 2),
                'total_working_hours' => number_format($totalHours, 2),
                'month_vs_min_hours' => ($workingDays * 8.00) . " / " . ($minTotalHours > 0 ? number_format($totalHours / $minTotalHours, 2) : 'N/A'),
                'days_worked' => $daysWorked,
                'working_days' => $workingDays,
                'leaves' => $leaves,
                'profile_image' => $profileImage,
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function dailyAttendanceReport(){
        $data['meta_title'] = 'Daily Attendance Report';
        return view('reports.daily-attendance.index', $data);
    }
    public function dailyAttendanceData(Request $request){

        $reportDate = Carbon::createFromFormat('d-m-Y', $request->report_date ?? now()->format('d-m-Y'))->format('Y-m-d');

        // Get all active employees
        $employees = Employee::with('user')->get();

        // Get all attendances for the day
        $attendances = Attendance::whereDate('signin_date', $reportDate)->get()->keyBy('emp_id');

        // Get all leaves for the day
        $leaves = Leave::whereDate('leave_from', '<=', $reportDate)
            ->whereDate('leave_to', '>=', $reportDate)
            ->get()
            ->groupBy('user_id');

        $data = [];

        foreach ($employees as $index => $employee) {
            $user = $employee->user;
            $userId = $employee->user_id;
            $name = $employee->full_name ?? 'NA';

            $initials = collect(explode(' ', trim($name)))
                ->filter(fn($w) => !empty($w))
                ->map(fn($w) => strtoupper($w[0]))
                ->join('');
            $initials = substr($initials, 0, 2);

            $image = $user->profile_image
                ? '<img src="' . asset('storage/' . $user->profile_image) . '" width="40" height="40" class="rounded-circle" />'
                : "<div class='rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center' style='width: 40px; height: 40px;'>$initials</div>";

            $attendance = $attendances[$userId] ?? null;
            $leave = $leaves[$userId][0] ?? null;

            if ($attendance) {
                $status = $attendance->status;

                if ($status === 'mark-out') {
                    $finalStatus = ($attendance->working_hours < '08:00:00') ? 'Incomplete' : 'Complete';
                } elseif ($status === 'mark-in') {
                    $finalStatus = 'Ongoing';
                } else {
                    $finalStatus = ucfirst($status ?? '-');
                }

                $data[] = [
                    'index'         => $index + 1,
                    'name'          => $name,
                    'image'         => $image,
                    'signin_time'   => $attendance->signin_time ? $attendance->signin_time . '<br /> <span class="badge bg-label-warning">' . $attendance->punchin_type . '</span>' : '-',
                    'signout_time'  => $attendance->signout_time ?? '-',
                    'break_time'    => $attendance->break_time ?? '-',
                    'working_hours' => $attendance->working_hours ?? '-',
                    'signin_note'   => $attendance->signin_late_note ?? '-',
                    'signout_note'  => $attendance->signout_late_note ?? '-',
                    'status'        => $finalStatus
                ];
            } elseif ($leave) {
                $data[] = [
                    'index'         => $index + 1,
                    'name'          => $name,
                    'image'         => $image,
                    'signin_time'   => '-',
                    'signout_time'  => '-',
                    'break_time'    => '-',
                    'working_hours' => '-',
                    'signin_note'   => '-',
                    'signout_note'  => '-',
                    'status'        => 'On Leave'
                ];
            } else {
                $data[] = [
                    'index'         => $index + 1,
                    'name'          => $name,
                    'image'         => $image,
                    'signin_time'   => '-',
                    'signout_time'  => '-',
                    'break_time'    => '-',
                    'working_hours' => '-',
                    'signin_note'   => '-',
                    'signout_note'  => '-',
                    'status'        => 'Absent'
                ];
            }
        }

        return response()->json(['data' => $data]);
    }

    public function leaveReport(){
        $data['meta_title'] = 'Leave Report';
        $data['employees'] = Employee::all();
        return view('reports.leave-report.index', $data);
    }

    public function leaveReportData(Request $request){
        $query = Leave::with('user', 'employee');

        if ($request->filled('username')) {
            $query->where('user_id', $request->username);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('leave_from', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('leave_to', '<=', $request->end_date);
        }

        $leaves = $query->get()->map(function ($leave) {
            $leaveBadge = match ($leave->leave_type) {
                'full_day' => '<span class="badge bg-label-danger">Full Day</span>',
                'half_day' => '<span class="badge bg-label-success">Half Day</span>',
                'off_day'  => '<span class="badge bg-label-info">Off Day</span>',
                default    => '<span class="badge bg-label-secondary">Unknown</span>',
            };

            return [
                'id'            => $leave->id,
                'username'      => $leave->Employee->full_name ?? '-',
                'leave_from'    => Carbon::parse($leave->leave_from)->format('d-m-Y'),
                'leave_to'      => Carbon::parse($leave->leave_to)->format('d-m-Y'),
                'leave_count'   => Carbon::parse($leave->leave_from)->diffInDays($leave->leave_to) + 1,
                'leave_type'    => $leaveBadge,
                'reason'        => $leave->reason,
                'apply_date'    => $leave->created_at->format('d-m-Y'),
                'status'        => ($leave->status == 3) ? 'Rejected' : (($leave->status == 2) ? 'Approved' : 'Pending'),
                'action'        => '<a href="#" class="btn btn-sm btn-info">Edit</a>'
            ];
        });

        return response()->json(['data' => $leaves]);
    }

    public function allAttendanceReport(){
        $data['meta_title'] = 'All Attendance Report';
        $data['employees'] = Employee::all();
        return view('reports.all-attendance-report.index', $data);
    }

    /* public function allAttendanceData(Request $request){
        $data['current_user'] = Employee::where('user_id', $request->employee_id)->first();
        $data['day'] = $request->day;
        $data['month'] = $request->month;
        $data['year'] = $request->year;

        $employeeId = $request->employee_id;

        $report = [];

        if ($request->filled('day')) {
            $startDate = Carbon::createFromDate($request->year, $request->month, $request->day);
            $endDate = $startDate;
        } else {
            $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth();
        }

        $attendances = Attendance::with('employee', 'employee.user')
            ->where('emp_id', $employeeId)
            ->whereBetween('signin_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->signin_date)->format('Y-m-d');
            });

        $holidays = DB::table('holidays')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('date')
            ->toArray();

        $serial = 1;
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayName = $currentDate->format('l');

            $row = (object)[
                'index' => $serial++,
                'signin_date' => $currentDate->format('d-m-Y'),
                'signin_time' => 'N/A',
                'signout_time' => 'N/A',
                'break_time' => 'N/A',
                'working_hours' => 'N/A',
                'signin_note' => 'N/A',
                'signout_note' => 'N/A',
                'status' => 'leave',
                'statusText' => 'Leave'
            ];

            if (isset($attendances[$dateStr])) {
                $att = $attendances[$dateStr];
                $row->signin_date = date('d-m-Y', strtotime($att->signin_date));
                $row->signin_time = $att->signin_time ?? '';
                $row->signout_time = $att->signout_time ?? '';
                $row->break_time = $att->break_time ?? '';
                $row->working_hours = $att->working_hours ?? '';
                $row->signin_note = $att->signin_note ?? '';
                $row->signout_note = $att->signout_note ?? '';

                if (in_array($dateStr, $holidays)) {
                    $row->status = 'holiday';
                    $row->statusText = 'Holiday Worked';
                } elseif (in_array($dayName, ['Saturday', 'Sunday'])) {
                    $row->status = 'weekend';
                    $row->statusText = $dayName . ' Worked';
                } else {
                    $row->status = $att->signout_time ? 'mark-out' : 'mark-in';
                    $row->statusText = $att->signout_time ? 'Completed' : 'Incomplete';
                }
            } elseif (in_array($dateStr, $holidays)) {
                $row->status = 'holiday';
                $row->statusText = 'Holiday';
            } elseif (in_array($dayName, ['Saturday', 'Sunday'])) {
                $row->status = 'weekend';
                $row->statusText = $dayName;
            }

            $report[] = $row;
            $currentDate->addDay();
        }

        $data['attendances'] = collect($report);
        $html = view('reports.all-attendance-report.report-view', $data)->render();

        return response()->json(['html' => $html]);
    } */

    public function allAttendanceData(Request $request){
        
        $data['current_user'] = Employee::where('user_id', $request->employee_id)->first();
        $data['day'] = $request->day;
        $data['month'] = $request->month;
        $data['year'] = $request->year;

        $employeeId = $request->employee_id;
        $report = [];

        if ($request->filled('day')) {
            $startDate = Carbon::createFromDate($request->year, $request->month, $request->day);
            $endDate = $startDate;
        } else {
            $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth();
           // $endDate = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth();
            $endDate = $endDate = Carbon::now();
        }

        $attendances = Attendance::with('employee', 'employee.user')
            ->where('emp_id', $employeeId)
            ->whereBetween('signin_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->signin_date)->format('Y-m-d'));

        $leaves = Leave::where('user_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('leave_from', [$startDate, $endDate])
                    ->orWhereBetween('leave_to', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('leave_from', '<=', $startDate)
                            ->where('leave_to', '>=', $endDate);
                    });
            })
            ->get();

        $leaveDates = [];
        foreach ($leaves as $leave) {
            $from = Carbon::parse($leave->leave_from);
            $to = Carbon::parse($leave->leave_to);
            while ($from->lte($to)) {
                $leaveDates[] = $from->format('Y-m-d');
                $from->addDay();
            }
        }

        $holidays = DB::table('holidays')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('date')
            ->toArray();

        $employee = Employee::with('user')->where('user_id', $employeeId)->first();
        $user = $employee->user;
        $name = $employee->full_name ?? 'NA';

        $initials = collect(explode(' ', trim($name)))
            ->filter()
            ->map(fn($w) => strtoupper($w[0]))
            ->join('');
        $initials = substr($initials, 0, 2);

        $image = $user->profile_image
            ? '<img src="' . asset('storage/' . $user->profile_image) . '" width="40" height="40" class="rounded-circle" />'
            : "<div class='rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center' style='width: 40px; height: 40px;'>$initials</div>";

        $serial = 1;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayName = $currentDate->format('l');

            $row = (object)[
                'index'         => $serial++,
                'name'          => $name,
                'image'         => $image,
                'signin_date'   => $currentDate->format('d-m-Y'),
                'signin_time'   => 'N/A',
                'signout_time'  => 'N/A',
                'break_time'    => 'N/A',
                'working_hours' => 'N/A',
                'signin_note'   => 'N/A',
                'signout_note'  => 'N/A',
                'status'        => 'leave',
                'statusText'    => 'Leave'
            ];

            if (isset($attendances[$dateStr])) {
                $att = $attendances[$dateStr];
                $row->signin_time = $att->signin_time ?? '-';
                $row->signout_time = $att->signout_time ?? '-';
                $row->break_time = $att->break_time ?? '-';
                $row->working_hours = $att->working_hours ?? '-';
                $row->signin_note = $att->signin_note ?? '-';
                $row->signout_note = $att->signout_note ?? '-';

                if (in_array($dateStr, $holidays)) {
                    $row->status = 'holiday';
                    $row->statusText = '<span class="badge bg-label-secondary mt-1">Holiday Worked</span> ';
                } elseif (in_array($dayName, ['Saturday', 'Sunday'])) {
                    $row->status = 'weekend';
                    $row->statusText = '<span class="badge bg-label-primary mt-1">'.$dayName . ' Worked </span>';
                } elseif (!$att->signout_time) {
                    $row->status = 'mark-in';
                    $row->statusText = '<span class="badge bg-label-info mt-1">On Going</span>';
                } elseif ($att->working_hours && $att->working_hours < '08:00:00') {
                    $row->status = 'incomplete';
                    $row->statusText = '<span class="badge bg-label-warning mt-1"> In-complete </span>';
                } else {
                    $row->status = 'mark-out';
                    $row->statusText = '<span class="badge bg-label-info mt-1">Completed</span>';
                }
            } elseif (in_array($dateStr, $leaveDates)) {
                $row->status = 'leave';
                $row->statusText = '<span class="badge bg-label-danger mt-1"> On Leave </span>';
            } elseif (in_array($dateStr, $holidays)) {
                $row->status = 'holiday';
                $row->statusText = '<span class="badge bg-label-secondary mt-1">Holiday</span>';
            } elseif (in_array($dayName, ['Saturday', 'Sunday'])) {
                $row->status = 'weekend';
                $row->statusText = '<span class="badge bg-label-primary mt-1">'. $dayName .'</span>';
            } else {
                $row->status = 'absent';
                $row->statusText = '<span class="badge bg-label-primary mt-1"> Absent </span>';
            }

            $report[] = $row;
            $currentDate->addDay();
        }

        $data['attendances'] = collect($report);
        $html = view('reports.all-attendance-report.report-view', $data)->render();

        return response()->json(['html' => $html]);
    }

    public function allWorkReport(){
        $data['meta_title'] = 'All Work Report';
        $data['employees'] = Employee::all();
        return view('reports.all-work-report.index', $data);
    }

    public function allWorkReportData(Request $request){

        $request->validate([
            'employee_id' => 'nullable|integer',
            'day' => 'nullable|string', // optional day filter
            'month' => 'required|string', // padded month
            'year' => 'required|integer|min:2000',
        ]);

        $employeeId = $request->employee_id;
        $day = $request->day;
        $month = $request->month;
        $year = $request->year;

        // Get attendance data
        $attendanceQuery = Attendance::query()
            ->whereMonth('signin_date', $month)
            ->whereYear('signin_date', $year);

        if ($day) {
            $attendanceQuery->whereDay('signin_date', $day);
        }

        if ($employeeId) {
            $attendanceQuery->where('emp_id', $employeeId);
        }

        $attendanceData = $attendanceQuery->get()->keyBy(function ($item) {
            return $item->emp_id . '_' . $item->signin_date;
        });

        // Get work report data
        $workReportQuery = WorkReport::with('project', 'projectTask', 'tasks')
            ->whereMonth('report_date', $month)
            ->whereYear('report_date', $year);

        if ($day) {
            $workReportQuery->whereDay('report_date', $day);
        }

        if ($employeeId) {
            $workReportQuery->where('emp_id', $employeeId);
        }

        $groupedReports = $workReportQuery->get()->groupBy(function ($item) {
            return $item->emp_id . '_' . $item->report_date;
        });

        $mergedData = [];

        foreach ($groupedReports as $key => $reports) {
            [$empId, $reportDate] = explode('_', $key);
            $attendance = $attendanceData->get($key);

            $mergedData[] = [
                'emp_id'        => $empId,
                'report_date'   => $reportDate,
                'signin_time'   => $attendance->signin_time ?? null,
                'signout_time'  => $attendance->signout_time ?? null,
                'working_hours' => $attendance->working_hours ?? null,
                'punchin_note'  => $attendance->signin_late_note ?? null,
                'punchout_note' => $attendance->signout_late_note ?? null,
                'reports'       => $reports->map(function ($report) {
                    $totalHours = 0;
                    if (!empty($report->total_time) && strpos($report->total_time, ':') !== false) {
                        [$hours, $minutes] = explode(':', $report->total_time);
                        $totalHours = ((int)$hours) + ((int)$minutes / 60);
                    }

                    $records = is_numeric($report->total_records) ? (float)$report->total_records : 0;
                    $achievedHour = $totalHours > 0 ? ($records / $totalHours) : 0;

                    $productivity = is_numeric($report->productivity_hour) ? (float)$report->productivity_hour : 0;
                    $grade = $productivity > 0 ? number_format(($achievedHour / $productivity) * 100, 2) : 0;

                    return [
                        'project_name' => $report->project->project_name ?? 'N/A',
                        'type_of_work' => $report->tasks->name ?? 'N/A',
                        'time_of_work' => $report->time_of_work,
                        'total_records' => $report->total_records,
                        'total_time' => $report->total_time,
                        'productivity_hour' => $report->productivity_hour,
                        'achieved_hour' => number_format($achievedHour, 2),
                        'comments' => $report->comments,
                        'grade' => $grade,
                        'performance' => $this->getPerformanceCategory($grade),
                        'report_date' => Carbon::parse($report->report_date)->format('d-m-Y'), // Convert to d-m-Y format $report->report_date,
                    ];
                }),
            ];
        }

        return view('reports.all-work-report.index', [
            'mergedData' => $mergedData,
            'meta_title' => 'All Work Report',
            'employees' => Employee::all(),
            'request' => $request, // Pass this if Blade needs it for old form values
        ]);
    }

    private function getPerformanceCategory($grade)
    {
        if ($grade >= 90) return 'Excellent';
        if ($grade >= 75) return 'Good';
        if ($grade >= 50) return 'Average';
        return 'Poor';
    }

    public function overAllWorkReport(){
        $data['meta_title'] = 'Over All Work Report';
        $data['employees'] = Employee::all();
        return view('reports.all-work-report.over-all-work-report', $data);
    }

    public function getProjectsByEmployee(Request $request){

        $employeeId = $request->input('employee_id');
        // Search tasks where employee is in the comma-separated members
        $tasks = ProjectTask::whereRaw("FIND_IN_SET(?, members)", [$employeeId])->with('project')->get();
        // Optional: return only project IDs or names
        $projects = $tasks->pluck('project')->filter()->unique('id')->values();
        return response()->json($projects);
    }

    public function getFilteredReports(Request $request)
{
    $query = WorkReport::with(['project', 'projectTask', 'tasks', 'employee'])
        ->where('emergency', 0);

    if ($request->filled('employee_id')) {
        $query->where('emp_id', $request->employee_id);
    }

    if ($request->filled('project_id')) {
        $query->where('project_name', $request->project_id); // Confirm if 'project_name' is correct
    }

    if ($request->filled('task_id')) {
        $query->where('type_of_work', $request->task_id); // Confirm if 'type_of_work' is correct
    }

    if ($request->filled('day')) {
        $query->whereDay('report_date', $request->day);
    }

    if ($request->filled('month')) {
        $query->whereMonth('report_date', $request->month);
    }

    if ($request->filled('year')) {
        $query->whereYear('report_date', $request->year);
    }

    return response()->json($query->get());
}

    public function emergencyAttendanceReport(){
        $data['meta_title'] = 'Emergency Attendance Report';
        return view('reports.emergency-report.index', $data);
    }

    public function getEmergencyAttendance(Request $request)
    {
        $query = Attendance::query()
            ->where(function ($q) {
                $q->where('status', 'emergency')
                ->orWhere('punchin_type', 'emergency')
                ->orWhere('punchout_type', 'emergency');
            });

            if ($request->filled('month') && $request->filled('year')) {
                $query->whereMonth('signin_date', $request->month);
            }

            if ($request->filled('month') && $request->filled('year')) {
                $query->whereYear('signin_date', $request->year);
            }

        $records = $query->orderByDesc('signin_date')->get();

        return response()->json($records);
    }

    public function overAllEmergencyWorkReport(){
        $data['meta_title'] = 'Over All Work Report';
        $data['employees'] = Employee::all();
        return view('reports.emergency-report.over-all-work-report', $data);
    }

    public function getEmergencyFilteredReports(Request $request){
        $query = WorkReport::with(['project', 'projectTask', 'tasks'])
            ->where('emergency', 1);

        if ($request->filled('employee_id')) {
            $query->where('emp_id', $request->employee_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_name', $request->project_id); // Confirm if 'project_name' is correct
        }

        if ($request->filled('task_id')) {
            $query->where('type_of_work', $request->task_id); // Confirm if 'type_of_work' is correct
        }

        if ($request->filled('day')) {
            $query->whereDay('report_date', $request->day);
        }

        if ($request->filled('month')) {
            $query->whereMonth('report_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('report_date', $request->year);
        }

        return response()->json($query->get());
    }
}
