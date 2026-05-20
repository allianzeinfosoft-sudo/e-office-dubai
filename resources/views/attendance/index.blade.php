@extends('layouts.app')

@section('css')
    <style>
        .w-35 {
            width: 35% !important;
        }
        .w-45 {
            width: 45% !important;
        }
        .offcanvas-close {
            position: absolute;
            top: 0px;
            left: -32px;
            /* Moves the button outside the offcanvas */
            z-index: 1055;
            /* Ensures it stays on top */
            padding: 28px 10px;
            border-radius: 0px;
        }
        .marking-card {
            background-color: #f8fafc;
            border: none;
            border-radius: 12px;
            padding: 1rem;
        }
        .marking-icon-box {
            background-color: #fff;
            border-radius: 8px;
            padding: 8px;
            display: inline-block;
            margin-bottom: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .wfh-btn-primary {
            background-color: #fb923c !important;
            border: none;
            color: white !important;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .wfh-btn-primary:hover {
            background-color: #f97316 !important;
            transform: translateY(-1px);
        }

        .status-circle-premium {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }

        .status-circle-premium.status-approved i {
            color: #22c55e;
            font-size: 60px;
        }

        .status-date-range {
            color: #f97316;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .reason-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            text-align: left;
        }

        .alert-cyan {
            background-color: #e0f7fa;
            border: none;
            color: #00838f;
        }

        .alert-soft-orange {
            background-color: #fff7ed;
            border: none;
            color: #ea580c;
        }

        .alert-soft-blue {
            background-color: #e0f2fe;
            border: none;
            color: #0369a1;
        }
        
        .wfh-status-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .wfh-status-item:hover {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }
        .wfh-request-header {
            background: linear-gradient(45deg, #7367f0, #9e95f5);
            color: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(115, 103, 240, 0.3);
        }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .status-circle-lg {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            margin: 0 auto 15px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            border: 4px solid;
        }
    </style>
@stop

@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
            <x-menu /> <!-- Load the menu component here -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <x-header />
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4 text-muted">
                            <span class="text-muted fw-light"></span>{{ $meta_title }}
                        </h4>

                        <div class="row">
                            @php
                                $today = date('Y-m-d');
                                $approvedWFHRequest = $approvedWFHRequestToday;
                                $approvedWOSRequest = $approvedWOSRequestToday;
                                $hasAnyApprovedWFH_WOS = $approvedWFHRequest || $approvedWOSRequest;
                            @endphp
                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                            <!-- Statistics -->
                            <div class="col-12 col-xl-12 col-lg-12">
                                <div class="row g-4 mb-4 justify-content-center">
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div class="content-left">
                                                        <div class="d-flex align-items-center my-1">
                                                            <h4 class="mb-0 me-2">{{ $days_of_worked ?? '0' }}</h4>
                                                        </div>
                                                        <span>No of Working Days</span>
                                                    </div>
                                                    <span class="badge bg-label-warning rounded p-2">
                                                        <i class="ti ti-calendar ti-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div class="content-left">
                                                        <div class="d-flex align-items-center my-1">
                                                            <h4 class="mb-0 me-2">{{ $totalWorkedHours ?? '0' }}</h4>
                                                        </div>
                                                        <span>Total Working Hours</span>
                                                    </div>
                                                    <span class="badge bg-label-warning rounded p-2">
                                                        <i class="ti ti-hourglass-high ti-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xl-4">
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div class="content-left">
                                                        <div class="d-flex align-items-center my-1">
                                                            <h4 class="mb-0 me-2">{{ $avgWorkedHours ?? '0' }}</h4>
                                                        </div>
                                                        <span>Avg. Working Hour(s)</span>
                                                    </div>
                                                    <span class="badge bg-label-warning rounded p-2">
                                                        <i class="ti ti-info-circle ti-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Statistics -->

                            <div class="col-12 col-xl-12 col-lg-12">
                                <div class="row g-4 mb-4 align-items-center">
                                    <!-- Markin module -->
                                    <div class="col-12 col-xl-8 col-lg-8">
                                        <div class="card card-sm">
                                            <div class="card-header">
                                                <h4 class="card-title mb-1">
                                                    <i class="ti ti-user ti-sm"></i>
                                                    {{ ucfirst(Auth::user()->employee?->full_name ?? 'N/A') }}
                                                </h4>
                                            </div>

                                            <div class="card-body">
                                                <div class="row mb-4 g-4">
                                                    <div class="col-sm-6 col-xl-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-flex align-items-start justify-content-between">
                                                                    <div class="content-left">
                                                                        <div class="d-flex align-items-center my-1">
                                                                            <h4 class="mb-0 me-2">{{ date('d-m-Y') }}</h4>
                                                                        </div>
                                                                        <span>{{ date('l') }}</span>
                                                                    </div>
                                                                    <span class="badge bg-label-warning rounded p-2">
                                                                        <i class="ti ti-calendar ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-xl-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div
                                                                    class="d-flex align-items-start justify-content-between">
                                                                    <div class="content-left">
                                                                        <div class="d-flex align-items-center my-1">
                                                                            <h4 class="mb-0 me-2">
                                                                                <span id="attendance_clock">00:00:00</span>
                                                                            </h4>
                                                                        </div>
                                                                        <span>Time</span>
                                                                    </div>
                                                                    <span class="badge bg-label-warning rounded p-2">
                                                                        <i class="ti ti-clock ti-sm"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-4">
                                                    <div class="col-lg-12">
                                                        @php
                                                            $loginLimitTime = \Carbon\Carbon::parse(
                                                                Auth::user()->employee->login_limited_time,
                                                            );
                                                            $now = \Carbon\Carbon::now();
                                                            $isLate =
                                                                $shiftType == 'day' || $shiftType == 'fullday'
                                                                    ? false
                                                                    : $now->gt($loginLimitTime);
                                                            $todayName = $now->format('l'); // E.g., "Monday"
                                                            $fixedWeekOffs = [];
                                                            $employeeWeekOffs =
                                                                Auth::user()->employee->week_off_days ?? '';
                                                            $customWeekOffs = array_map(
                                                                'trim',
                                                                explode(',', $employeeWeekOffs),
                                                            );
                                                            $allWeekOffs = array_unique(
                                                                array_merge($fixedWeekOffs, $customWeekOffs),
                                                            );
                                                            $isWeekOffToday = in_array($todayName, $allWeekOffs);
                                                        @endphp

                                                        @if (isset($attendance) || isset($attendance_current))
                                                            @if ($shiftType == 'night')
                                                                @if (
                                                                    $attendance_current?->signin_date == date('Y-m-d') &&
                                                                        in_array($attendance_current?->status, ['mark-in', 'custom', 'emergency']))
                                                                    <div class="badge bg-label-success p-3 w-100 mb-3 text-dark"
                                                                        id="last-punch-time" role="alert">
                                                                        Last Punch In Time:
                                                                        {{ date('d-m-Y', strtotime($attendance_current?->signin_date)) }}
                                                                        {{ date('h:i A', strtotime($attendance_current?->signin_time)) }}
                                                                        <input type="hidden" name="attendance_id"
                                                                            id="attendance_id"
                                                                            value="{{ $attendance_current?->id }}" />
                                                                    </div>
                                                                    <div class="text-center">
                                                                        <button type="button" id="mark-out-btn"
                                                                            class="btn p-3 btn-success w-100">
                                                                            <i class="ti ti-arrow-big-left-lines ti-sm"></i>
                                                                            Mark-out
                                                                        </button>
                                                                    </div>
                                                                @elseif($attendance_current?->status === 'mark-out')
                                                                    @php
                                                                        $nextLoginTime = \Carbon\Carbon::createFromFormat(
                                                                            'H:i:s',
                                                                            $employee->workshift->shift_start_time,
                                                                        )
                                                                            ->subMinutes(30)
                                                                            ->format('h:i A');
                                                                        $lastWorkingDate = \Carbon\Carbon::now()
                                                                            ->subDay()
                                                                            ->toDateString();
                                                                        $prevAttendance = \App\Models\Attendance::where(
                                                                            'username',
                                                                            Auth::user()->username,
                                                                        )
                                                                            ->where('signin_date', $lastWorkingDate)
                                                                            ->first();
                                                                    @endphp

                                                                    @if ($isWeekOffToday || $isHolidayToday)
                                                                        @if (!$prevAttendance || !$prevAttendance->signout_time)
                                                                            <div class="badge bg-label-danger p-3 w-100 mb-3"
                                                                                id="last-punch-time" role="alert">
                                                                                <strong>Missed Mark-out Detected:</strong>
                                                                                Please contact admin to regularize
                                                                                yesterday's attendance.
                                                                            </div>
                                                                        @else
                                                                            <div class="badge bg-label-warning p-3 w-100 mb-3"
                                                                                id="last-punch-time" role="alert">
                                                                                <strong>Next Login Time:</strong>
                                                                                {{ $nextLoginTime }} tomorrow.
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        <div class="badge bg-label-warning p-3 w-100 mb-3"
                                                                            id="last-punch-time" role="alert">
                                                                            <strong>Next Login Time:</strong>
                                                                            {{ $nextLoginTime }} tomorrow.
                                                                        </div>
                                                                    @endif
                                                                @elseif(
                                                                    $attendance?->signin_date == date('Y-m-d', strtotime('-1 day')) &&
                                                                        in_array($attendance?->status, ['mark-in', 'custom', 'emergency']))
                                                                    <div class="badge bg-label-success p-3 w-100 mb-3 text-dark"
                                                                        id="last-punch-time" role="alert">
                                                                        Last Punch In Time:
                                                                        {{ date('d-m-Y', strtotime($attendance?->signin_date)) }}
                                                                        {{ date('h:i A', strtotime($attendance?->signin_time)) }}
                                                                        <input type="hidden" name="attendance_id"
                                                                            id="attendance_id"
                                                                            value="{{ $attendance?->id }}" />
                                                                    </div>

                                                                    <div class="text-center">
                                                                        <button type="button" id="mark-out-btn"
                                                                            class="btn p-3 btn-success w-100">
                                                                            <i
                                                                                class="ti ti-arrow-big-left-lines ti-sm"></i>
                                                                            Mark-out
                                                                        </button>
                                                                    </div>
                                                                @else
                                                                    <div class="text-center">
                                                                        <button type="button" class="btn p-3 btn-primary w-100 mark-in-btn {{ $disableCustomMarkIn || $isWeekOffToday ? 'disabled' : '' }}"
                                                                            {{ $disableCustomMarkIn || $isWeekOffToday ? 'disabled' : '' }}>
                                                                            Mark-in <i
                                                                                class="ti ti-arrow-big-right-lines ti-sm"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @else
                                                                {{-- day shift --}}
                                                                @if (isset($attendance))
                                                                    @if (in_array($attendance->status, ['mark-in', 'custom', 'emergency']) && $attendance->signin_date == date('Y-m-d'))
                                                                        <div class="badge bg-label-success p-3 w-100 mb-3 text-dark"
                                                                            id="last-punch-time" role="alert">
                                                                            @if($attendance->punchin_type == 'wfh')
                                                                                <i class="ti ti-building-home me-1"></i> [WFH]
                                                                            @elseif($attendance->punchin_type == 'wfs' || $attendance->punchin_type == 'wos')
                                                                                <i class="ti ti-building me-1"></i> [WOS]
                                                                            @endif
                                                                            Last Punch In Time:
                                                                            {{ date('d-m-Y', strtotime($attendance->signin_date)) }}
                                                                            {{ date('H:i A', strtotime($attendance->signin_time)) }}
                                                                            <input type="hidden" name="attendance_id"
                                                                                id="attendance_id"
                                                                                value="{{ $attendance?->id }}" />
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <button type="button" id="mark-out-btn"
                                                                                class="btn p-3 btn-success w-100">
                                                                                <i
                                                                                    class="ti ti-arrow-big-left-lines ti-sm"></i>
                                                                                Mark-out
                                                                            </button>
                                                                        </div>
                                                                    @elseif($attendance->status === 'mark-out')
                                                                        <div class="badge bg-label-warning p-3 w-100 mb-3"
                                                                            id="last-punch-time" role="alert">
                                                                            <strong>Next Punchin Tomorrow:</strong> Please
                                                                            Co-operate.
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <div class="badge bg-label-warning p-3 w-100 mb-3 network_error"
                                                                        id="network_error" role="alert" style="display: none;"></div>
                                                                    <div class="text-center">
                                                                        <button type="button" class="btn p-3 btn-primary w-100 mark-in-btn {{ $disableCustomMarkIn || $isWeekOffToday ? 'disabled' : '' }}"
                                                                            {{ $disableCustomMarkIn || $isWeekOffToday ? 'disabled' : '' }}>
                                                                            Mark-in <i
                                                                                class="ti ti-arrow-big-right-lines ti-sm"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @elseif(!isset($attendance) || !$attendance || !in_array($attendance->status, ['mark-in', 'custom', 'emergency']))
                                                            @if ($disableCustomMarkIn || $isLate || $isWeekOffToday)
                                                                <div class="badge bg-label-warning p-3 w-100 mb-3">
                                                                    You can mark in only between
                                                                    {{ $employee->workshift->shift_start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $employee->workshift->shift_start_time)->subMinutes(30)->format('h:i A') : '' }}
                                                                    and
                                                                    {{ $employee->workshift->shift_start_time ? \Carbon\Carbon::createFromFormat('H:i:s', $employee->workshift->shift_start_time)->addMinutes(15)->format('h:i A') : '' }}.
                                                                </div>
                                                            @endif

                                                            @if ($isWeekOffToday)
                                                                <div class="badge bg-label-warning p-3 w-100 mb-3"
                                                                    id="last-punch-time" role="alert">
                                                                    <strong>Today ({{ $todayName }}) is your week
                                                                        off.</strong>
                                                                </div>
                                                            @endif
                                                            <div class="badge bg-label-warning p-3 w-100 mb-3 network_error"
                                                                id="network_error" role="alert" style="display: none;"></div>
                                                            <div class="text-center">
                                                                @if($hasAnyApprovedWFH_WOS)
                                                                    <div class="alert alert-warning p-2">
                                                                        You have an approved {{ $approvedWFHRequest ? 'WFH' : 'WOS' }} request for today. Please use the {{ $approvedWFHRequest ? 'Work From Home' : 'Work On Site' }} button.
                                                                    </div>
                                                                @endif
                                                                <button type="button"
                                                                    class="btn p-3 btn-primary w-100 mark-in-btn {{ $disableCustomMarkIn || $isLate || $isWeekOffToday || $hasAnyApprovedWFH_WOS ? 'disabled' : '' }}"
                                                                    {{ $disableCustomMarkIn || $isLate || $isWeekOffToday || $hasAnyApprovedWFH_WOS ? 'disabled' : '' }}>
                                                                    Mark-in <i
                                                                        class="ti ti-arrow-big-right-lines ti-sm"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <button type="button" id="mark-out-btn"
                                                                    class="btn p-3 btn-success w-100">
                                                                    <i class="ti ti-arrow-big-left-lines ti-sm"></i>
                                                                    Mark-out
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="text-center d-grid gap-2 col-lg-12">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ Markin module -->

                                    <!-- Custom module -->
                                    <div class="col-12 col-xl-4 col-lg-4">
                                        <div class="card card-bg pt-3">
                                            <div class="row g-4 p-3">
                                                <!-- custom -->
                                                <div class="col-12 col-md-6 col-xl-12 col-lg-12 pt-2">
                                                    <button type="button" class="btn p-3 btn-info w-100 {{ $hasAnyApprovedWFH_WOS ? 'disabled' : '' }}"
                                                        {{ $hasAnyApprovedWFH_WOS ? 'disabled' : '' }}
                                                        onclick="customModal()">
                                                        Custom <i class="mx-1 ti ti-arrow-big-right-lines ti-sm"></i>
                                                    </button>
                                                </div>
                                                <!--/ custom -->

                                                <!-- emergency -->
                                                <div class="col-12 col-md-6 col-xl-12 col-lg-12">
                                                    <button type="button" class="btn p-3 btn-warning w-100 {{ $hasAnyApprovedWFH_WOS ? 'disabled' : '' }}"
                                                        {{ $hasAnyApprovedWFH_WOS ? 'disabled' : '' }}
                                                        onclick="emergencyModal()">
                                                        Emergency <i class="mx-1 ti ti-bolt ti-sm"></i>
                                                    </button>
                                                </div>
                                                <!--/ emergency -->
                                                <hr style="margin: 1rem 0;" />
                                                <!-- work from home -->
                                                <div class="col-6 col-md-6 col-xl-6 col-lg-6">
                                                    <button type="button" id="wfh-dash-btn" class="btn {{ $wfhAttendanceToday ? ($wfhAttendanceToday->signout_time ? 'btn-label-success' : 'btn-success') : 'btn-primary' }} w-100"
                                                        onclick="wfh_attendance()">
                                                        @if($wfhAttendanceToday)
                                                            @if($wfhAttendanceToday->signout_time)
                                                                WFH Done <i class="ti ti-check ti-xs"></i>
                                                            @else
                                                                WFH In <i class="ti ti-clock ti-xs"></i>
                                                            @endif
                                                        @else
                                                            Work From Home
                                                        @endif
                                                    </button>
                                                </div>

                                                <div class="col-6 col-md-6 col-xl-6 col-lg-6">
                                                    <button type="button" id="wos-dash-btn" class="btn {{ $wosAttendanceToday ? ($wosAttendanceToday->signout_time ? 'btn-label-success' : 'btn-success') : 'btn-danger' }} w-100"
                                                        onclick="wos_attendance()">
                                                        @if($wosAttendanceToday)
                                                            @if($wosAttendanceToday->signout_time)
                                                                WOS Done <i class="ti ti-check ti-xs"></i>
                                                            @else
                                                                WOS In <i class="ti ti-clock ti-xs"></i>
                                                            @endif
                                                        @else
                                                            Work On Site
                                                        @endif
                                                    </button>
                                                </div>
                                                <!--/ work from home -->

                                                <div class="col-12 col-xl-12 col-lg-12 pb-4">
                                                    <div class="card badge bg-label-dark w-100 pt-3 pb-3">
                                                        <div class="d-flex align-items-start justify-content-between">
                                                            <div class="content-left">
                                                                <div class="d-flex align-items-center my-1">
                                                                    <h4 class="mb-0 me-2">{{ $todayWorkedHours ?? '0' }}
                                                                    </h4>
                                                                </div>
                                                                <span>Total Hours You Spent</span>
                                                            </div>
                                                            <div class="card-action-element">
                                                                <ul class="list-inline mb-0">
                                                                    <li class="list-inline-item">
                                                                        <a href="javascript:void(0);" class="card-reload">
                                                                            <i
                                                                                class="tf-icons ti ti-rotate-clockwise-2 scaleX-n1-rtl ti-sm"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Custom module -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <x-footer />
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>

                    <!-- Overlay -->
                    <div class="layout-overlay layout-menu-toggle"></div>

                    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                    <div class="drag-target"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Offcanvas for Custom Marking -->
    <div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="customMarkingOffcanvas"
        aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header bg-primary">
            <h5 class="offcanvas-title text-white" id="staticBackdropLabel">
                <i class="ti ti-hourglass float-start fs-3"></i> Custom Marking
            </h5>
            <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="offcanvas-body">

            <!-- @php
                $isOfficeNetwork = \App\Models\OfficeIp::pluck('ip_address')->contains(request()->ip());
            @endphp
            @if(!$isOfficeNetwork)
                <div class="alert alert-warning" role="alert">
                    Custom marking is allow only office net work
                </div>
            @else -->

            <div class="row">
                <form id="customMarkingForm" action="{{ route('attendance.custom-mark-in') }}" method="post">
                    @csrf
                    <div class="col-12 mb-3">
                        <label for="signin_date" class="form-label">Date</label>
                        <input type="text" class="form-control" value="{{ date('Y-m-d') }}" placeholder="Date"
                            disabled readonly />
                    </div>

                    <div class="col-12 mb-3">
                        <label for="signin_time" class="form-label">Time</label>
                        <input type="time" id="signin_time" name="signin_time" class="form-control"
                            value="{{ \Carbon\Carbon::now('Asia/Dubai')->format('H:i') }}" placeholder="Time" />
                        <input type="hidden" id="signin_date" name="signin_date" class="form-control"
                            value="{{ date('Y-m-d') }}" placeholder="Time" />
                    </div>

                    <div class="col-12 mb-3">
                        <label for="signin_late_note" class="form-label">Reason</label>
                        <textarea id="signin_late_note" name="signin_late_note" class="form-control" placeholder="Reason" rows="5"></textarea>
                    </div>
                </form>
                <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas"
                        aria-label="Close">
                        Close
                    </button>
                    <button type="submit" onclick="customMarking()" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>

            <!-- @endif -->
        </div>
        <div class="offcanvas-footer"></div>
    </div>

    <!-- Offcanvas for Emergency Marking -->
    <div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="emergencyMarkingOffcanvas"
        aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header bg-primary">
            <h5 class="offcanvas-title text-white" id="staticBackdropLabel">
                <i class="ti ti-device-watch float-start fs-3"></i> Emergency Marking
            </h5>
            <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <!-- @php
                $isOfficeNetwork = \App\Models\OfficeIp::pluck('ip_address')->contains(request()->ip());
            @endphp
            @if(!$isOfficeNetwork)
                <div class="alert alert-warning" role="alert">
                    Emergency marking is allow only office net work
                </div>
            @else -->
            <div class="row">
                <form id="emergencyMarkingForm" action="{{ route('attendance.emergency-mark') }}" method="post">
                    @csrf
                    <div class="col-12 mb-3">
                        <label for="emergency_signin_date" class="form-label">Date</label>
                        <input type="text" id="emergency_signin_date" name="signin_date" class="form-control"
                            value="{{ date('Y-m-d') }}" placeholder="Date" readonly />
                    </div>

                    <div class="col-12 mb-3">
                        <label for="emergency_signin_late_note" class="form-label">Reason</label>
                        <textarea id="emergency_signin_late_note" name="signin_late_note" class="form-control" placeholder="Reason"
                            rows="5"></textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="time_in_out" class="form-label">Time</label>
                        <input type="time" id="time_in_out" name="time_in_out" class="form-control"
                            value="{{ \Carbon\Carbon::now('Asia/Dubai')->format('H:i') }}" placeholder="Time" />
                    </div>
                </form>
            </div>
            <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close">
                    Close
                </button>
                <button type="button" onclick="emergencyMarkIn()" class="btn btn-success">
                    Mark In
                </button>
                <button type="button" onclick="emergencyMarkOut()" class="btn btn-danger">
                    Mark Out
                </button>
            </div>
            <!-- @endif -->
        </div>
        <div class="offcanvas-footer"></div>
    </div>

    <!-- work from home -->
    <div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="wfhOffcanvas"
        aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header bg-primary">
            <h5 class="offcanvas-title text-white" id="staticBackdropLabel">
                <i class="ti ti-building-home float-start fs-3"></i> Work From Home (WFH)
            </h5>
            <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="offcanvas-body" id="wfh-offcanvas-content">
            <!-- Marking/Request View -->
            <div class="row" id="wfh-active-view">
                <!-- Left Column: Marking Buttons -->
                <div class="col-md-7 border-end" id="wfh-marking-col">
                    <div class="card p-3 shadow-none border">
                        <div class="text-center mb-4 border-bottom pb-3">
                            <div class="avatar avatar-xl mb-2 mx-auto">
                                @if(Auth::user()->employee?->profile_image)
                                    <img src="{{ asset('storage/' . Auth::user()->employee->profile_image) }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-user ti-md"></i></span>
                                @endif
                            </div>
                            <h5 class="mb-0">{{ ucfirst(Auth::user()->employee?->full_name ?? 'N/A') }}</h5>
                            <span class="text-muted small">Employee ID: {{ Auth::user()->employee?->employeeID ?? 'N/A' }}</span>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="marking-card text-center">
                                    <div class="marking-icon-box">
                                        <i class="ti ti-calendar fs-4"></i>
                                    </div>
                                    <h6 class="mb-0 small fw-bold">{{ date('d M Y') }}</h6>
                                    <div class="text-muted" style="font-size: 11px;">{{ date('l') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="marking-card text-center">
                                    <div class="marking-icon-box">
                                        <i class="ti ti-clock fs-4"></i>
                                    </div>
                                    <h6 class="mb-0 small fw-bold" id="wfh_clock">00:00:00</h6>
                                    <div class="text-muted" style="font-size: 11px;">Current Time</div>
                                </div>
                            </div>
                        </div>

                        <div id="wfh-action-container">
                            @if(!$wfhAttendanceToday || $wfhAttendanceToday->signin_date != date('Y-m-d'))
                                <!-- Mark In Section -->
                                <div class="mb-4" id="wfh-markin-section">
                                    <div class="alert alert-cyan small p-3 mb-3 text-center d-flex align-items-center justify-content-center" role="alert">
                                        <i class="ti ti-clock me-2 fs-4"></i> Working Hours: 00:00:00
                                    </div>
                                    <button type="button" onclick="wfhMarkIn()" 
                                        class="btn p-3 wfh-btn-primary w-100 {{ !$approvedWFHRequestToday ? 'disabled' : '' }}"
                                        {{ !$approvedWFHRequestToday ? 'disabled' : '' }} id="wfh-markin-btn">
                                        <i class="ti ti-arrow-big-right-lines ti-sm me-1"></i> Mark-in
                                    </button>
                                </div>
                            @elseif($wfhAttendanceToday && !$wfhAttendanceToday->signout_time && $wfhAttendanceToday->signin_date == date('Y-m-d'))
                                <!-- Mark Out Section -->
                                <div class="mb-4" id="wfh-markout-section">
                                    <div class="alert alert-cyan small p-3 mb-3 text-center d-flex align-items-center justify-content-center" role="alert">
                                        <i class="ti ti-clock me-2 fs-4"></i> Working Hours: <span id="wfh-running-hours">00:00:00</span>
                                    </div>
                                    <button type="button" onclick="wfhMarkOut()" class="btn p-3 btn-success w-100" id="wfh-markout-btn">
                                        <i class="ti ti-report ti-sm me-1"></i> Mark-out
                                    </button>
                                </div>
                                @else
                                <!-- Completed Section -->
                                <div class="mb-4" id="wfh-completed-section">
                                    <div class="alert alert-cyan small p-3 mb-3 text-center d-flex align-items-center justify-content-center" role="alert">
                                        <i class="ti ti-clock me-2 fs-4"></i> Working Hours: {{ $wfhAttendanceToday?->working_hours ?? '00:00:00' }}
                                    </div>
                                    <div class="alert alert-soft-orange p-3 w-100 mb-3 text-center" role="alert">
                                        <strong>Next Markin Tomorrow:</strong> Please Co-operate.
                                    </div>
                                    <button type="button" class="btn p-3 wfh-btn-primary w-100 disabled" disabled>
                                        <i class="ti ti-arrow-big-right-lines ti-sm me-1"></i> Mark-in
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Request & Status -->
                <div class="col-md-5" id="wfh-request-col">
                    @if ($approvedWFHRequestToday)
                        <div id="wfh-status-container">
                            <div class="mb-4">
                                <div class="text-center">
                                    <!-- Title: Date Range -->
                                    <h2 class="status-date-range mb-1">
                                        {{ date('d-m-Y', strtotime($approvedWFHRequestToday->from_date)) }} -
                                        {{ date('d-m-Y', strtotime($approvedWFHRequestToday->to_date)) }}
                                    </h2>
                                    <!-- Subtitle: Applied Date -->
                                    <p class="text-muted mb-4">Applied Date:
                                        {{ date('d-m-Y', strtotime($approvedWFHRequestToday->created_at)) }}</p>

                                    <!-- Status Icon & Label -->
                                    <div class="status-circle-premium status-approved">
                                        <i class="fa fa-check"></i>
                                    </div>
                                    <h4 class="text-uppercase fw-bold mb-4" style="letter-spacing: 2px; color: #64748b;">
                                        Approved
                                    </h4>

                                    @if ($approvedWFHRequestToday->reason)
                                        <div class="reason-box">
                                            <strong class="d-block mb-1 small text-muted">Reason for Request:</strong>
                                            <p class="mb-0 small">{{ $approvedWFHRequestToday->reason }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Request Form -->
                        <div id="wfh-form-container">
                            <div class="card p-3 bg-light shadow-none border">
                                <h6 class="mb-3">Submit New WFH Request</h6>
                                <form id="wfhRequestForm" onsubmit="event.preventDefault(); submitWFHRequest();">
                                    @csrf
                                    <input type="hidden" name="request_type" value="wfh">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">From Date</label>
                                            <input type="date" name="from_date" id="wfh_from_date" class="form-control"
                                                value="{{ date('Y-m-d') }}" required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">To Date</label>
                                            <input type="date" name="to_date" id="wfh_to_date" class="form-control"
                                                value="{{ date('Y-m-d') }}" required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Attendance Option</label>
                                            <select name="attendance_option" class="form-control" required>
                                                <option value="personal" selected>Personal</option>
                                                <option value="company">Company</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Reason</label>
                                            <textarea name="reason" id="wfh_reason" class="form-control" placeholder="Reason for WFH" rows="3"
                                                required></textarea>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn wfh-btn-primary"
                                                id="wfh-submit-request-btn">Submit Request</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Report View (Full Width) -->
            {{-- <div id="wfh-report-view" class="mt-4" style="display:none;">
                 <!-- <x-work-from-home-attendance-report type="wfh" :hideDetails="true" /> -->
            </div> --}}
        </div>
    </div>

    <!-- work from site -->
    <div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="wosOffcanvas"
        aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header bg-primary">
            <h5 class="offcanvas-title text-white" id="staticBackdropLabel">
                <i class="ti ti-hourglass float-start fs-3"></i> Work On Site (WOS)
            </h5>
            <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas"
                aria-label="Close">
                <i class="fa fa-close"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <!-- Marking/Request View -->
            <div class="row" id="wos-marking-view">
                @php
                    $wosRequest = \App\Models\WorkFromHomeRequest::where('emp_id', Auth::id())
                        ->where('from_date', '<=', $today)
                        ->where('to_date', '>=', $today)
                        ->where('request_type', 'wos')
                        ->first();
                @endphp

                <!-- Left Column: Profile & Action -->
                <div class="col-md-7 border-end" id="wos-marking-col">
                    <div class="card p-3 shadow-none border">
                        <div class="text-center mb-4 border-bottom pb-3">
                            <div class="avatar avatar-xl mb-2 mx-auto">
                                @if(Auth::user()->employee?->profile_image)
                                    <img src="{{ asset('storage/' . Auth::user()->employee->profile_image) }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-user ti-md"></i></span>
                                @endif
                            </div>
                            <h5 class="mb-0">{{ ucfirst(Auth::user()->employee?->full_name ?? 'N/A') }}</h5>
                            <span class="text-muted small">Employee ID: {{ Auth::user()->employee?->employeeID ?? 'N/A' }}</span>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="marking-card text-center">
                                    <div class="marking-icon-box">
                                        <i class="ti ti-calendar fs-4"></i>
                                    </div>
                                    <h6 class="mb-0 small fw-bold">{{ date('d M Y') }}</h6>
                                    <div class="text-muted" style="font-size: 11px;">{{ date('l') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="marking-card text-center">
                                    <div class="marking-icon-box">
                                        <i class="ti ti-clock fs-4"></i>
                                    </div>
                                    <h6 class="mb-0 small fw-bold" id="wos_clock">00:00:00</h6>
                                    <div class="text-muted" style="font-size: 11px;">Current Time</div>
                                </div>
                            </div>
                        </div>

                        <div id="wos-action-container">
                            @if(!$wosAttendanceToday || $wosAttendanceToday->signin_date != date('Y-m-d'))
                                <!-- Mark In Section -->
                                <div class="mb-4" id="wos-markin-section">
                                    <div class="alert alert-cyan small p-3 mb-3 text-center d-flex align-items-center justify-content-center" role="alert">
                                        <i class="ti ti-clock me-2 fs-4"></i> Working Hours: 00:00:00
                                    </div>
                                    <button type="button" onclick="wosMarkIn()" 
                                        class="btn p-3 wfh-btn-primary w-100 {{ !$approvedWOSRequestToday ? 'disabled' : '' }}"
                                        {{ !$approvedWOSRequestToday ? 'disabled' : '' }} id="wos-markin-btn">
                                        <i class="ti ti-arrow-big-right-lines ti-sm me-1"></i> Mark-in
                                    </button>
                                </div>
                            @elseif($wosAttendanceToday && !$wosAttendanceToday->signout_time && $wosAttendanceToday->signin_date == date('Y-m-d'))
                                <!-- Mark Out Section -->
                                <div class="mb-4" id="wos-markout-section">
                                    <div class="alert alert-cyan small p-3 mb-3 text-center d-flex align-items-center justify-content-center" role="alert">
                                        <i class="ti ti-clock me-2 fs-4"></i> Working Hours: <span id="wos-running-hours">00:00:00</span>
                                    </div>
                                    <button type="button" onclick="wosMarkOut()" class="btn p-3 btn-success w-100" id="wos-markout-btn">
                                        <i class="ti ti-report ti-sm me-1"></i> Mark-out
                                    </button>
                                </div>
                            @else
                                <!-- Completed Section -->
                                <div class="mb-4" id="wos-completed-section">
                                    <div class="alert alert-cyan small p-3 mb-3 text-center d-flex align-items-center justify-content-center" role="alert">
                                        <i class="ti ti-clock me-2 fs-4"></i> Working Hours: {{ $wosAttendanceToday?->working_hours ?? '00:00:00' }}
                                    </div>
                                    <div class="alert alert-soft-orange p-3 w-100 mb-3 text-center" role="alert">
                                        <strong>Next Markin Tomorrow:</strong> Please Co-operate.
                                    </div>
                                    <button type="button" class="btn p-3 btn-primary w-100 disabled" disabled>
                                        <i class="ti ti-arrow-big-right-lines ti-sm me-1"></i> Mark-in
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Request & Details -->
                <div class="col-md-5" id="wos-details-col">
                    @if ($approvedWOSRequestToday)
                        <div id="wos-status-container">
                            <div class="mb-4">
                                <div class="card shadow-none border bg-light text-center">
                                    <div class="card-body p-4">
                                        <!-- Title: Date Range -->
                                        <h3 class="mb-1 fw-bold text-primary">
                                            {{ date('d-m-Y', strtotime($approvedWOSRequestToday->from_date)) }} -
                                            {{ date('d-m-Y', strtotime($approvedWOSRequestToday->to_date)) }}
                                        </h3>
                                        <!-- Subtitle: Applied Date -->
                                        <p class="text-muted mb-4">Applied Date:
                                            {{ date('d-m-Y', strtotime($approvedWOSRequestToday->created_at)) }}</p>

                                        <!-- Status Icon & Label -->
                                        <div class="status-circle-lg status-approved">
                                            <i class="fa fa-check fa-lg"></i>
                                        </div>
                                        <h5 class="text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">
                                            Approved
                                        </h5>

                                        @if ($approvedWOSRequestToday->reason)
                                            <div class="mt-2 p-3 bg-white rounded border small text-start">
                                                <strong class="d-block mb-1">Reason for Request:</strong>
                                                {{ $approvedWOSRequestToday->reason }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Request Form / Marking Forms -->
                        <div id="wos-form-container">
                            <!-- WOS Request Form -->
                            <div class="card p-3 bg-light shadow-none border">
                                <h6 class="mb-3">Submit New WOS Request</h6>
                                <form id="wosRequestForm" onsubmit="event.preventDefault(); submitWOSRequest();">
                                    @csrf
                                    <input type="hidden" name="request_type" value="wos">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">From Date</label>
                                            <input type="date" name="from_date" id="wos_from_date"
                                                class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">To Date</label>
                                            <input type="date" name="to_date" id="wos_to_date"
                                                class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required />
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label small">Attendance Option</label>
                                            <select name="attendance_option" class="form-control form-control-sm" required>
                                                <option value="personal" selected>Personal</option>
                                                <option value="company">Company</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label small">Reason</label>
                                            <textarea name="reason" id="wos_reason" class="form-control form-control-sm" placeholder="Reason for WOS"
                                                rows="3" required></textarea>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                id="wos-submit-request-btn">Submit Request</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Report View (Full Width) -->
            {{-- <div id="wos-report-view" class="mt-4" style="display:none;">
                 <!-- <x-work-from-home-attendance-report type="wos" :hideDetails="true" /> -->
            </div> --}}
        </div>
        <div class="offcanvas-footer"></div>
    </div>

    {{-- marks as read --}}
    <div class="modal fade" id="announcementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gray text-white">
                    <h5 class="modal-title" style="color: white">Important Announcement</h5>
                </div>
                <div class="modal-body">
                    <h6 id="announcement-title"></h6>
                    <p id="announcement-message"></p>
                    <div id="announcement-image" class="text-center mt-3"></div>
                    <input type="hidden" id="announcement-id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="mark-as-read-btn">
                        Mark as Read
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script>
        $(function() {
            /* Mark in function */
            $('.mark-in-btn').on('click', function(e) {
                e.preventDefault(); // 🔐 stop form from submitting
                var $btn = $(this);

                // Prevent double click
                if ($btn.prop('disabled')) return;

                // Disable the button and show loading text
                $btn.prop('disabled', true).text('Loading..');

                $.ajax({
                    url: "{{ route('attendance.mark-in') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({}),
                    success: function(data) {
                        console.log(data);
                        if (data.success) {
                            $('.network_error').hide(); // Hide error badge if exists
                            toastr["success"](data.message);
                            toastr.options = {
                                "closeButton": false,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                            // alert(data.message);
                            $('#last-punch-time').text(
                                `Last punch In Time: ${data.data.signin_time || data.message}`
                                );
                            window.location.reload();
                        } else {
                            $btn.prop('disabled', false).text('Mark In');
                            // Show error in badge instead of toastr
                            $('.network_error').text(data.message).show();
                            // Also support ID selector just in case
                            $('#network_error').text(data.message).show();
                            console.log('Error:', data.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // Re-enable the button if there's an error
                        $btn.prop('disabled', false).text('Mark In');
                    }
                });
            });

            /* Mark out function */
            $('#mark-out-btn').on('click', function() {
                var attendanceId = $('#attendance_id').val();
                var userId = $('#user_id').val();
                var $btn = $(this);
                // Prevent double click
                if ($btn.prop('disabled')) return;
                // Disable button and show loading text
                $btn.prop('disabled', true).text('Loading..');

                if (!confirm("Are you sure you want to mark out?")) {
                    $btn.prop('disabled', false).text('Mark Out');
                    return;
                }
                // var type = 'logout';
                // markOutHistory(userId,type);
                check_announcement(function(canProceed) {
                    if (canProceed) {
                        $.ajax({
                            url: "{{ route('attendance.mark-out') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            contentType: 'application/json',
                            data: JSON.stringify({
                                'attendanceId': attendanceId
                            }),
                            success: function(data) {
                                if (data.success) {
                                    toastr["success"](data.message);
                                    toastr.options = {
                                        "closeButton": false,
                                        "debug": false,
                                        "newestOnTop": false,
                                        "progressBar": false,
                                        "positionClass": "toast-top-right",
                                        "preventDuplicates": false,
                                        "onclick": null,
                                        "showDuration": "300",
                                        "hideDuration": "1000",
                                        "timeOut": "5000",
                                        "extendedTimeOut": "1000",
                                        "showEasing": "swing",
                                        "hideEasing": "linear",
                                        "showMethod": "fadeIn",
                                        "hideMethod": "fadeOut"
                                    }
                                    $('#last-punch-out-time').text(
                                        `Last punch Out Time: ${data.data.signout_time}`
                                        );
                                    $('#mark-out-btn').prop('disabled', true);
                                    window.location.reload();
                                } else {
                                    alert(data.message);
                                    // Optional: re-enable button and restore text if you don't reload
                                    $btn.prop('disabled', false).text('Mark Out');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error:', error);
                                // Re-enable on error
                                $btn.prop('disabled', false).text('Mark Out');
                            }
                        });
                    }
                });
            });

            $('.card-reload').on('click', function(e) {
                window.location.reload();
            });

            document.getElementById('signin_time').addEventListener('change', function() {
                let selected = this.value; // "HH:MM"

                if (!selected) return;

                // Build selected datetime object
                const today = new Date();
                const [hour, minute] = selected.split(":");
                const selectedTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), hour,
                    minute);

                // Current datetime object
                const now = new Date();

                if (selectedTime > now) {
                    alert("You cannot select a future time.");
                    // Reset to current time
                    this.value = now.toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });
                }
            });
        });

        // markOutHistory(userId,type){
        //     $.ajax({
        //             url: '/store-markout-history',
        //             type: 'POST',
        //             headers: {
        //                 'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //             },
        //             data: { userId: userId, type:type },
        //             success: function (res) {

        //             },
        //             error: function () {

        //             }
        //         });
        // }

        function check_announcement(callback) {
            $.ajax({
                url: '/check-announcement',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.found && res.announcements && res.announcements.length > 0) {
                        console.log("Unread announcements found.");
                        handleMultipleAnnouncements(res.announcements, 0, function() {
                            console.log("All announcements handled.");
                            callback(true);
                        });
                    } else {
                        console.log("No unread announcements.");
                        callback(true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error checking announcement:', error);
                    $btn.prop('disabled', false).text('Mark Out');
                }
            });
        }

        function handleMultipleAnnouncements(announcements, index, doneCallback) {
            if (index >= announcements.length) {
                console.log("No more announcements to show.");
                doneCallback(); // All done, proceed
                return;
            }

            const current = announcements[index];

            $('#announcement-title').text(current.name_announcement);
            $('#announcement-message').html(current.description);

            if (current.picture) {
                const imagePath = `/storage/${current.picture}`;
                $('#announcement-image').html(
                `<img src="${imagePath}" alt="Announcement Image" class="img-fluid rounded">`);
            } else {
                $('#announcement-image').empty(); // Clear if no image
            }

            $('#announcement-id').val(current.id);
            $('#announcementModal').modal('show');

            $('#mark-as-read-btn').off('click').on('click', function() {
                markAsRead(current.id).then(() => {
                    $('#announcementModal').modal('hide');

                    // Wait for modal to hide before next
                    $('#announcementModal').on('hidden.bs.modal', function() {
                        $('#announcementModal').off('hidden.bs.modal');
                        handleMultipleAnnouncements(announcements, index + 1, doneCallback);
                    });
                });
            });
        }

        function markAsRead(announcementId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/mark-announcement-read',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        announcement_id: announcementId
                    },
                    success: function(res) {
                        if (res.status === 'success') resolve();
                        else reject('Failed to mark as read');
                    },
                    error: function() {
                        reject('Error marking as read');
                    }
                });
            });
        }

        function customModal() {
            var offcanvasElement = $('#customMarkingOffcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            offcanvas.show();
            //$('#modelCustom').modal('show');
        }

        function emergencyModal() {
            var offcanvasElement = $('#emergencyMarkingOffcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            offcanvas.show();
            //$('#emergencyMarking').modal('show');
        }

        function customMarking() {
            const formData = {
                _token: $('input[name="_token"]').val(), // CSRF token
                signin_time: $('#signin_time').val(),
                signin_date: $('#signin_date').val(),
                signin_late_note: $('#signin_late_note').val()
            };

            $.ajax({
                type: "POST",
                url: $('#customMarkingForm').attr('action'),
                data: formData,
                dataType: "json",
                success: function(response) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    if (response.success) {
                        toastr.success(response.message);
                        $('#customMarkingForm')[0].reset(); // Clear form after success
                        const offcanvasElement = document.getElementById('customMarkingOffcanvas');
                        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                        if (offcanvas) offcanvas.hide();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors).flat().join('\n');
                        toastr.error('Error:\n' + errorMessages);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        }

        /* Emergency marking section js */
        function emergencyMarkIn() {
            emergencyMark('mark-in');
        }

        function emergencyMarkOut() {
            emergencyMark('mark-out');
        }

        function emergencyMark(type) {
            const formData = {
                _token: $('input[name="_token"]').val(),
                signin_date: $('#emergency_signin_date').val(),
                signin_late_note: $('#emergency_signin_late_note').val(),
                time_in_out: $('#time_in_out').val(),
                type: type // 'mark-in' or 'mark-out'
            };

            $.ajax({
                type: 'POST',
                url: $('#emergencyMarkingForm').attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '4000',
                        extendedTimeOut: '1000',
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut'
                    };

                    if (response.success) {
                        toastr.success(response.message);
                        $('#emergencyMarkingForm')[0].reset(); // Clear form after success
                        //$('#emergencyMarking').modal('hide'); // Close modal after success

                        var offcanvasElement = $('#emergencyMarkingOffcanvas');
                        var offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
                        offcanvas.hide();
                        if (type == 'mark-in') {
                            setTimeout(() => {
                                window.location.reload();
                            }, 300);
                        } else {
                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('work-report.emerbency-work-report') }}";
                            }, 300);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors).flat().join('\n');
                        toastr.error('Error:\n' + errorMessages);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        }

        function formatDate(dateStr) {
            if(!dateStr) return '';
            const date = new Date(dateStr);
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${d}-${m}-${y}`;
        }

        function wfh_attendance() {
            var offcanvasElement = document.getElementById('wfhOffcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            offcanvas.show();
            $('#wfh-active-view').show();
            //$('#wfh-report-view').hide();
        }

        function submitWFHRequest() {
            const $btn = $('#wfh-submit-request-btn');
            const $form = $('#wfhRequestForm');
            
            $btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: "{{ route('work-from-home-request.store') }}",
                type: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#wfh-form-container').html(`
                            <div class="alert alert-info small d-flex align-items-center" role="alert">
                                <i class="ti ti-info-circle me-2"></i>
                                You have an active request. You can submit a new one once this is completed or if it is rejected.
                            </div>
                        `);
                        
                        if(response.data) {
                            const data = response.data;
                            $('#wfh-status-container').html(`
                                <div class="mb-4">
                                    <div class="card shadow-none border bg-light text-center">
                                        <div class="card-body p-4">
                                            <h3 class="mb-1 fw-bold text-primary">
                                                ${formatDate(data.from_date)} - ${formatDate(data.to_date)}
                                            </h3>
                                            <p class="text-muted mb-4">Applied Date: ${formatDate(new Date())}</p>
                                            <div class="status-circle-lg status-pending">
                                                <i class="fa fa-hourglass fa-lg fa-spin"></i>
                                            </div>
                                            <h5 class="text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">Pending</h5>
                                            ${data.reason ? `
                                                <div class="mt-2 p-3 bg-white rounded border small text-start">
                                                    <strong class="d-block mb-1">Reason for Request:</strong>
                                                    ${data.reason}
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                    } else {
                        toastr.error(response.message || 'Error submitting request');
                        $btn.prop('disabled', false).text('Submit Request');
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors).flat().join('\n');
                        toastr.error(errorMessages);
                    } else {
                        toastr.error('An error occurred.');
                    }
                    $btn.prop('disabled', false).text('Submit Request');
                }
            });
        }

        function submitWOSRequest() {
            const $btn = $('#wos-submit-request-btn');
            const $form = $('#wosRequestForm');
            
            $btn.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: "{{ route('work-from-home-request.store') }}",
                type: 'POST',
                data: $form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#wos-form-container').html(`
                            <div class="alert alert-info small d-flex align-items-center" role="alert">
                                <i class="ti ti-info-circle me-2"></i>
                                You have an active request.
                            </div>
                        `);
                        
                        if(response.data) {
                            const data = response.data;
                            $('#wos-status-container').html(`
                                <div class="mb-4">
                                    <div class="card shadow-none border bg-light text-center">
                                        <div class="card-body p-4">
                                            <h3 class="mb-1 fw-bold text-primary">
                                                ${formatDate(data.from_date)} - ${formatDate(data.to_date)}
                                            </h3>
                                            <p class="text-muted mb-4">Applied Date: ${formatDate(new Date())}</p>
                                            <div class="status-circle-lg status-pending">
                                                <i class="fa fa-hourglass fa-lg fa-spin"></i>
                                            </div>
                                            <h5 class="text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">Pending</h5>
                                            ${data.reason ? `
                                                <div class="mt-2 p-3 bg-white rounded border small text-start">
                                                    <strong class="d-block mb-1">Reason for Request:</strong>
                                                    ${data.reason}
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                </div>
                            `);
                        }
                    } else {
                        toastr.error(response.message || 'Error submitting request');
                        $btn.prop('disabled', false).text('Submit Request');
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors).flat().join('\n');
                        toastr.error(errorMessages);
                    } else {
                        toastr.error('An error occurred.');
                    }
                    $btn.prop('disabled', false).text('Submit Request');
                }
            });
        }

        function wfhMarkIn() {
            const $btn = $('#wfh-markin-btn');
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true).text('Processing...');

            const formData = {
                signin_date: "{{ date('Y-m-d') }}",
                signin_time: new Date().toLocaleTimeString('en-GB', { timeZone: 'Asia/Dubai', hour12: false }),
                signin_note: 'WFH Mark In'
            };

            $.ajax({
                type: 'POST',
                url: '{{ route("attendance.wfh-mark-in") }}',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        const signinTimeFormatted = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        
                        // Update Offcanvas UI
                        $('#wfh-action-container').html(`
                            <div class="mb-4" id="wfh-markout-section">
                                <div class="badge bg-label-success p-3 w-100 mb-3 text-dark" id="wfh-last-punch-in" role="alert">
                                    Last Punch In: ${signinTimeFormatted}
                                </div>
                                <button type="button" onclick="wfhMarkOut()" class="btn p-3 btn-success w-100" id="wfh-markout-btn">
                                    <i class="ti ti-report ti-sm me-1"></i> Mark-out
                                </button>
                            </div>
                        `);

                        // Update Dashboard Button
                        $('#wfh-dash-btn').removeClass('btn-primary wfh-btn-primary').addClass('btn-success')
                            .html('WFH In <i class="ti ti-clock ti-xs"></i>');
                    } else {
                        toastr.error(response.message);
                        $btn.prop('disabled', false).text('Mark-in');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred.');
                    $btn.prop('disabled', false).text('Mark-in');
                }
            });
        }

        function wfhMarkOut() {
            const $btn = $('#wfh-markout-btn');
            if ($btn.prop('disabled')) return;
            $btn.prop('disabled', true).text('Processing...');

            const formData = {
                signout_date: "{{ date('Y-m-d') }}",
                signout_time: new Date().toLocaleTimeString('en-GB', { timeZone: 'Asia/Dubai', hour12: false }),
                signout_note: 'WFH Mark Out'
            };

            $.ajax({
                type: 'POST',
                url: '{{ route("attendance.wfh-mark-out") }}',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            bootstrap.Offcanvas.getInstance(document.getElementById('wfhOffcanvas')).hide();
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                        $btn.prop('disabled', false).text('Mark-out');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred.');
                    $btn.prop('disabled', false).text('Mark-out');
                }
            });
        }

        function wos_attendance() {
            var offcanvasElement = document.getElementById('wosOffcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            offcanvas.show();

            $('#wos-marking-view').show();
            $('#wos-report-view').hide();
        }

        function wosMarkIn() {
            const formData = {
                _token: $('input[name="_token"]').val(),
                signin_time: new Date().toLocaleTimeString('en-GB', { timeZone: 'Asia/Dubai', hour12: false }),
                signin_date: "{{ date('Y-m-d') }}",
                signin_note: 'WOS Mark In'
            };

            $.ajax({
                type: 'POST',
                url: '{{ route("attendance.wos-mark-in") }}',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '4000',
                        extendedTimeOut: '1000',
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut'
                    };

                    if (response.success) {
                        toastr.success(response.message);
                        
                        const signinTimeFormatted = new Date().toLocaleTimeString('en-US', { timeZone: 'Asia/Dubai', hour: '2-digit', minute: '2-digit', hour12: true });
                        
                        // Update Offcanvas UI status
                        $('#wos-action-container').html(`
                            <div class="mb-4" id="wos-markout-section">
                                <div class="badge bg-label-success p-3 w-100 mb-3 text-dark" id="wos-last-punch-in" role="alert">
                                    Last Punch In: ${signinTimeFormatted}
                                </div>
                                <button type="button" onclick="wosMarkOut()" class="btn p-3 btn-success w-100" id="wos-markout-btn">
                                    <i class="ti ti-report ti-sm me-1"></i> Mark-out
                                </button>
                            </div>
                        `);

                        // Update Dashboard Button
                        $('#wos-dash-btn').removeClass('btn-danger wfh-btn-primary').addClass('btn-success')
                            .html('WOS In <i class="ti ti-clock ti-xs"></i>');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors).flat().join('\n');
                        toastr.error('Error:\n' + errorMessages);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        }

        function wosMarkOut() {
            const formData = {
                _token: $('input[name="_token"]').val(),
                signout_time: new Date().toLocaleTimeString('en-GB', { timeZone: 'Asia/Dubai', hour12: false }),
                signout_date: "{{ date('Y-m-d') }}",
                signout_note: 'WOS Mark Out'
            };

            $.ajax({
                type: 'POST',
                url: '{{ route("attendance.wos-mark-out") }}',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: 'toast-top-right',
                        timeOut: '4000',
                        extendedTimeOut: '1000',
                        showMethod: 'fadeIn',
                        hideMethod: 'fadeOut'
                    };

                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            bootstrap.Offcanvas.getInstance(document.getElementById('wosOffcanvas')).hide();
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessages = Object.values(errors).flat().join('\n');
                        toastr.error('Error:\n' + errorMessages);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                }
            });
        }

        function updateClocks() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-GB', { timeZone: 'Asia/Dubai',hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
            if (document.getElementById('attendance_clock')) {
                document.getElementById('attendance_clock').innerText = timeStr;
            }
            if (document.getElementById('wfh_clock')) {
                document.getElementById('wfh_clock').innerText = timeStr;
            }
            if (document.getElementById('wos_clock')) {
                document.getElementById('wos_clock').innerText = timeStr;
            }
        }
        setInterval(updateClocks, 1000);
        updateClocks();
        function showWFHReport() {
            $('#wfh-active-view').hide();
            // $('#wfh-report-view').show();
            
            // Pre-fill fields
            $('#wfh_emp_id').val('{{ Auth::id() }}').trigger('change');
            $('#wfh_signin_date').val('{{ $wfhAttendanceToday ? $wfhAttendanceToday->signin_date : date("Y-m-d") }}');
            @if($wfhAttendanceToday)
                $('#wfh_signin_time').val('{{ date("H:i", strtotime($wfhAttendanceToday->signin_time)) }}');
                $('#target_id').val('{{ $wfhAttendanceToday->id }}');
            @else
                $('#wfh_signin_time').val('{{ date("H:i") }}');
            @endif
            $('#wfh_signout_date').val('{{ $wfhAttendanceToday && $wfhAttendanceToday->signout_date ? $wfhAttendanceToday->signout_date : date("Y-m-d") }}');
            $('#wfh_signout_time').val('{{ $wfhAttendanceToday && $wfhAttendanceToday->signout_time ? date("H:i", strtotime($wfhAttendanceToday->signout_time)) : date("H:i") }}');
        }

        function showWOSReport() {
            $('#wos-marking-view').hide();
            $('#wos-report-view').show();
            
            // Pre-fill fields
            $('#wos_emp_id').val('{{ Auth::id() }}').trigger('change');
            $('#wos_signin_date').val('{{ $wosAttendanceToday ? $wosAttendanceToday->signin_date : date("Y-m-d") }}');
            @if($wosAttendanceToday)
                $('#wos_signin_time').val('{{ date("H:i", strtotime($wosAttendanceToday->signin_time)) }}');
                $('#target_id').val('{{ $wosAttendanceToday->id }}');
            @else
                $('#wos_signin_time').val('{{ date("H:i") }}');
            @endif
            $('#wos_signout_date').val('{{ $wosAttendanceToday && $wosAttendanceToday->signout_date ? $wosAttendanceToday->signout_date : date("Y-m-d") }}');
            $('#wos_signout_time').val('{{ $wosAttendanceToday && $wosAttendanceToday->signout_time ? date("H:i", strtotime($wosAttendanceToday->signout_time)) : date("H:i") }}');
        }

        function updateRunningTimers() {
            @if($wfhAttendanceToday && !$wfhAttendanceToday->signout_time && $wfhAttendanceToday->signin_date == date('Y-m-d'))
                let signin = new Date('{{ $wfhAttendanceToday->signin_date }} {{ $wfhAttendanceToday->signin_time }}');
                let now = new Date();
                let diff = Math.abs(now - signin);
                let h = Math.floor(diff / 3600000);
                let m = Math.floor((diff % 3600000) / 60000);
                let s = Math.floor((diff % 60000) / 1000);
                $('#wfh-running-hours').text(`${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`);
            @endif

            @if($wosAttendanceToday && !$wosAttendanceToday->signout_time && $wosAttendanceToday->signin_date == date('Y-m-d'))
                let signinWos = new Date('{{ $wosAttendanceToday->signin_date }} {{ $wosAttendanceToday->signin_time }}');
                let nowWos = new Date();
                let diffWos = Math.abs(nowWos - signinWos);
                let hWos = Math.floor(diffWos / 3600000);
                let mWos = Math.floor((diffWos % 3600000) / 60000);
                let sWos = Math.floor((diffWos % 60000) / 1000);
                $('#wos-running-hours').text(`${String(hWos).padStart(2, '0')}:${String(mWos).padStart(2, '0')}:${String(sWos).padStart(2, '0')}`);
            @endif
        }
        setInterval(updateRunningTimers, 1000);
        updateRunningTimers();
    </script>
@stop
