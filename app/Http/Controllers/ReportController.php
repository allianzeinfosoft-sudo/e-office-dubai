<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\workReport;
use App\Helpers\CustomHelper;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


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
        $attendances = Attendance::with('employee')
            ->whereDate('signin_date', $reportDate)
            ->get();

        $data = [];

        foreach ($attendances as $index => $attendance) {
            $user = $attendance->employee;
            $name = $user->full_name ?? 'NA';
            $initials = collect(explode(' ', $name))->map(fn($w) => strtoupper($w[0]))->join('');
            $initials = substr($initials, 0, 2);

            if ($user && $user->profile_image) {
                $image = '<img src="'.asset('storage/'.$user->profile_image).'" width="40" height="40" class="rounded-circle" />';
            } else {
                $image = "<div class='rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center' style='width: 40px; height: 40px;'>$initials</div>";
            }

            $status = $attendance->status;

            if ($status === 'markout') {
                $finalStatus = ($attendance->working_hours < '08:00:00') ? 'Incomplete' : 'Complete';
            } elseif ($status === 'markin') {
                $finalStatus = 'Ongoing';
            } else {
                $finalStatus = ucfirst($status ?? '-');
            }

            $data[] = [
                'index'         => $index + 1,
                'name'          => $name,
                'image'         => $image,
                'signin_time'   => ($attendance->signin_time) ? $attendance->signin_time . '<br /> <span class="badge badge-success">' . $attendance->punchin_type . '</span>' : '-',
                'signout_time'  => $attendance->signout_time ?? '-',
                'break_time'    => $attendance->break_time ?? '-',
                'working_hours' => $attendance->working_hours ?? '-',
                'signin_note'   => $attendance->signin_late_note ?? '-',
                'signout_note'  => $attendance->signout_late_note ?? '-',
                'status'        => $finalStatus
            ];
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
            return [
                'id'            => $leave->id,
                'username'      => $leave->Employee->full_name ?? '-',
                'leave_from'    => Carbon::parse($leave->leave_from)->format('d-m-Y'), 
                'leave_to'      => Carbon::parse($leave->leave_to)->format('d-m-Y'), 
                'leave_count'   => Carbon::parse($leave->leave_from)->diffInDays($leave->leave_to) + 1,
                'leave_type'    => ucfirst($leave->leave_type),
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

    public function allAttendanceData(Request $request){
        $data['current_user']   = Employee::where('user_id', $request->employee_id)->first();
        $data['day']            = $request->day;
        $data['month']          = $request->month;
        $data['year']           = $request->year;

        $query = Attendance::with('employee', 'employee.user')->where('emp_id', $request->employee_id);

        if ($request->filled('day')) {
            // Specific date
            $date = Carbon::createFromDate($request->year, $request->month, $request->day)->format('Y-m-d');
            $query->whereDate('signin_date', $date);
        } else {
            // Entire month
            $startDate = Carbon::createFromDate($request->year, $request->month, 1)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::createFromDate($request->year, $request->month, 1)->endOfMonth()->format('Y-m-d');
            $query->whereBetween('signin_date', [$startDate, $endDate]);
        }
        $data['attendances'] = $query->get();
        $html = view('reports.all-attendance-report.report-view',  $data)->render();

        return response()->json([
            'html' => $html
        ]);
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
                'reports' => $reports->map(function ($report) {
                    $totalHours = 0;
                    if (!empty($report->total_time) && strpos($report->total_time, ':') !== false) {
                        [$hours, $minutes] = explode(':', $report->total_time);
                        $totalHours = ((int)$hours) + ((int)$minutes / 60);
                    }

                    $records = is_numeric($report->total_records) ? (float)$report->total_records : 0;
                    $achievedHour = $totalHours > 0 ? ($records / $totalHours) : 0;

                    $productivity = is_numeric($report->productivity_hour) ? (float)$report->productivity_hour : 0;
                    $grade = $productivity > 0
                        ? number_format(($achievedHour / $productivity) * 100, 2)
                        : 0;

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
                        'report_date' => $report->report_date,
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
}
