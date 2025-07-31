<div class="row d-flex align-items-center">

    <div class="col-sm-6 mb-3">
        <div class="card bg-white">
            <div class="card-body">
                <!-- user avatar -->
                <div class="user-avatar-section">
                    <div class="d-flex align-items-center jestify-content-center gap-3">
                    <img class="img-fluid rounded mb-3 pt-1" src="{{ $current_user->profile_image ? asset('storage/' . $current_user->profile_image ) : '../../assets/img/avatars/15.png' }}" height="100" width="100" alt="User avatar">
                    <div class="user-info">
                        <h4 class="mb-2">{{ $current_user->full_name ?? '' }}</h4>
                        <span class="badge bg-label-danger mt-1">{{ $current_user->employeeID ?? '' }}</span>
                        <span class="badge bg-label-secondary mt-1">{{ $current_user->designation ? $current_user->designation->designation : '' }}</span>
                        <h5 class="mt-2">Attendance Report for {{ $day ? $day .' -' : '' }} {{ $month ? $month .' -' : '' }} {{ $year ?? '' }}. </h5>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-bg">
            <div class="card-datatable">
                <div class="table-responsive">
                    <table class="datatables-basic datatables-all-attendance-report table border-top table-stripedc table-hover table-striped" style="font-size: 11px">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Signin Date</th>
                                <th>Signin Time</th>
                                <th>Signout Time</th>
                                <th>Break Time</th>
                                <th>Working Hours</th>
                                <th>Signin Note</th>
                                <th>Signout Note</th>
                                <th>Punch-in Type</th> <!-- ✅ New -->
                                <th>Leave Type</th>    <!-- ✅ New -->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($attendances->count() > 0)
                                @foreach($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attendance->signin_date ?? 'N/A' }}</td>
                                        <td>{{ $attendance->signin_time ?? 'N/A' }}</td>
                                        <td>{{ $attendance->signout_time ?? 'N/A' }}</td>
                                        <td>{{ $attendance->break_time ?? 'N/A' }}</td>
                                        <td>{{ $attendance->working_hours ?? 'N/A' }}</td>
                                        <td>{{ $attendance->signin_note ?? 'N/A' }}</td>
                                        <td>{{ $attendance->signout_note ?? 'N/A' }}</td>
                                        <td>{{ $attendance->punchin_type }}</td> <!-- ✅ -->
                                        <td>{{ $attendance->leave_type }}</td>   <!-- ✅ -->
                                        <td>{!! $attendance->statusText ?? 'N/A' !!}</td>
                                        <td>
                                            @if($attendance->signin_time != "N/A" && $attendance->signout_time != "N/A")
                                                <form action="{{ route('attendance.destroy', $attendance->atte_id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


