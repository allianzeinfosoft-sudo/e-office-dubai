<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> Incomplete Working Hours Report</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 mb-1">
                <table class="table table-sm table-striped table-bordered" id="incompleteWorkingHoursTable">
                    <thead>
                        <tr>
                            <th>Sl. No.</th>
                            <th><i class="ti ti-user ti-sm"></i></th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Working Hours</th>
                            <th>Sign-in Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $attendance->employee->profile_image) }}" alt="Profile" width="40" height="40" class="rounded-circle">
                                </td>
                                <td>{{ $attendance->employee->full_name ?? 'N/A' }}</td>
                                <td>{{ $attendance->username ?? 'N/A' }}</td>
                                <td>
                                    @if($attendance->signin_time && $attendance->signout_time)
                                        @php
                                            $signIn = \Carbon\Carbon::parse($attendance->signin_time);
                                            $signOut = \Carbon\Carbon::parse($attendance->signout_time);
                                            $totalSeconds = $signOut->diffInSeconds($signIn);
        
                                            $breakSeconds = 3600; // Subtract 1 hour break
                                            $workedSeconds = max(0, $totalSeconds - $breakSeconds);
        
                                            $hours = floor($workedSeconds / 3600);
                                            $minutes = floor(($workedSeconds % 3600) / 60);
                                            $seconds = $workedSeconds % 60;
                                        @endphp
                                         <span class="alert bg-label-danger"> {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }} </span> 
                                    @else
                                        Incomplete
                                    @endif
                                </td>
                                <td><span class="alert bg-label-primary"> {{ \Carbon\Carbon::parse($attendance->signin_date)->format('d-m-Y') }} </span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>