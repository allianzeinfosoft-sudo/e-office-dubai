@if($lateAttendances->isEmpty())
    <p>No late entries found for this employee in the selected date range.</p>
@else
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Full Name</th>
                <th>Logged in Time</th>
                <th>Working Hours</th>
                <th>Signin Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lateAttendances as $index => $entry)
                <tr >
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($employee->profile_image)
                            <img src="{{ asset('storage/' . $employee->profile_image) }}" alt="Avatar" class="rounded-circle" width="40" height="40" />
                        @else
                            <img src="{{ asset('assets/img/avatars/default-avatar.png') }}" alt="Avatar" class="rounded-circle" width="40" height="40" />
                        @endif
                    </td>
                    <td>{{ $employee->full_name }}</td>
                    <td><button type="button" class="btn btn-primary">{{ $entry->signin_time }}</button></td>
                    <td><button type="button" class="btn btn-primary">{{ $entry->working_hours ?? 0  }}</button></td>
                    <td>{{ \Carbon\Carbon::parse($entry->signin_date)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif