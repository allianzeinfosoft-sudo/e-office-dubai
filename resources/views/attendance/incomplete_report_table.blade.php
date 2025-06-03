<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> Incomplete Working Hours Report</h4>
    </div>
    
    <div class="card-datatable table-responsive">
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

                        @php
                            $employee = $attendance->employee;
                            $name = $employee->full_name ?? 'NA';
                            $initials = collect(explode(' ', $name))->map(fn($word) => strtoupper($word[0]))->join('');
                            $initials = substr($initials, 0, 2);
                        @endphp

                        @if ($employee && $employee->profile_image)
                            <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="Profile" width="40" height="40" class="rounded-circle">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ $initials }}
                            </div>
                        @endif

                        </td>
                        <td>{{ $attendance->employee->full_name ?? 'N/A' }}</td>
                        <td>{{ $attendance->username ?? 'N/A' }}</td>
                        <td><span class="alert bg-label-danger"> {{ $attendance->working_hours }} </span> </td>
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