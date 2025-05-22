@extends('layouts.app')

@section('css')

@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">    
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> Project /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-lg-7 mb-4">
                            <!-- Attendance Marking Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> {{ $meta_title }}</h4>
                                </div>
                                <div class="card-body">
                                    <form id="editAttendanceForm" action="{{ route('attendance.full-day-attendance-entry') }}" method="post">
                                        <div class="row">
                                            @csrf
                                            <div class="col-6 mb-3">
                                                <label for="emp_id">Employees</label>
                                                <select class="form-control select2" name="emp_id" id="emp_id">
                                                    <option value="">Select</option>
                                                    @if($employees->isnotempty())
                                                        @foreach($employees as $employee)
                                                            <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-6 mb-3">
                                                <label for="signin_date" class="form-label">Mark-In Date</label>
                                                <input type="text" id="signin_date" name="signin_date" class="form-control flatpickr-input" value="{{ date('d-m-Y') }}"  placeholder="Mark in Date" />
                                            </div>
                                            
                                            <div class="col-3 mb-3">
                                                <label for="signin_time" class="form-label">Mark In Time</label>
                                                <input type="time" id="signin_time" name="signin_time" class="form-control" step="1" value=""  placeholder="Time" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="break_time" class="form-label">Brake Time</label>
                                                <input type="time" id="break_time" name="break_time" class="form-control" step="1" value="{{ date('H:i:s', strtotime('1:00')) }}"  placeholder="Time" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="signout_time" class="form-label">Mark Out Time</label>
                                                <input type="time" id="signout_time" name="signout_time" onch class="form-control" step="1" value=""  placeholder="Time" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="working_hours" class="form-label">Working Hours</label>
                                                <input type="time" id="working_hours" name="working_hours" class="form-control" step="1" value=""  placeholder="Time" />
                                            </div>

                                            <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
                                                <input type="hidden" name="attendance_id" id="attendance_id" />
                                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close"> Close </button>
                                                <button type="button" onclick="updateAttendance()"class="btn btn-primary"> Update </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <x-footer /> 
                <!-- / Footer -->
                 
                <div class="content-backdrop fade"></div>

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>

                <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>
@stop


@push('js')
<script>

    $(function(){
        
    })

const CustomHelper = {
    calculateWorkingHours: function(startTime, endTime, breakTime) {
        if (!startTime || !endTime) return 0;

        let start = new Date('1970-01-01T' + startTime + ':00');
        let end = new Date('1970-01-01T' + endTime + ':00');

        // Handle overnight shifts (e.g., 10 PM to 6 AM)
        if (end < start) {
            end.setDate(end.getDate() + 1);
        }

        let diff = (end - start) / (1000 * 60 * 60); // Difference in hours
        diff -= parseFloat(breakTime) || 0; // Subtract break time

        return diff > 0 ? diff.toFixed(2) : 0;
    },
    convertHoursToTimeFormat: function(hours) {
        let h = Math.floor(hours);
        let m = Math.round((hours - h) * 60);
        return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
    }
};

    $(function(){

        $('#signin_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        $('.select2').select2();

        $('#signout_time').on('change', function() {
            var startTime = $('#signin_time').val();
            var endTime = $(this).val();
            var breakTime = $('#break_time').val();
            var workingHours = CustomHelper.calculateWorkingHours(startTime, endTime, breakTime);

            // If input is type="time", use convertHoursToTimeFormat
            $('#working_hours').val(CustomHelper.convertHoursToTimeFormat(workingHours));
        });

        $('#emp_id, #signin_date').on('change', function() {
            fetchAttendanceData();
        });
        
    });

    function updateAttendance() {
        var form = document.getElementById('editAttendanceForm');
        var formData = new FormData(form);
        var attendanceId = $('#attendance_id').val(); // Get the attendance ID from a hidden input field

        $.ajax({
            url: '/settings/update-attendance-data/' + attendanceId, // Dynamically setting the update URL
            type: 'POST', // Using POST, because Laravel accepts POST manually even for update if you setup it
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload(); // Reload or close the modal
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation error
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value + '\n';
                        // Highlight invalid fields
                        $('#' + key).addClass('is-invalid');
                        $('#' + key).next('.invalid-feedback').remove();
                        $('#' + key).after('<div class="invalid-feedback">' + value + '</div>');
                    });

                    Swal.fire({
                        title: 'Validation Error!',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Generic error
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }


    function fetchAttendanceData() {
        let empId = $('#emp_id').val();
        let signinDate = $('#signin_date').val(); // assume 'd-m-Y' format (like 28-04-2025)

        if (empId && signinDate) {
            $.ajax({
                url: '{{ route("settings.get-attendance-data") }}', // adjust your route name
                method: 'GET',
                data: {
                    emp_id: empId,
                    date: signinDate
                },
                success: function(response) {
                    if(response.success && response.data) {
                        $('#signin_time').val(response.data.signin_time);
                        $('#break_time').val(response.data.break_time);
                        $('#signout_time').val(response.data.signout_time);
                        $('#working_hours').val(response.data.working_hours);
                        $('#attendance_id').val(response.data.id);
                    } else {
                        // Clear form fields if no data
                        $('#signin_time, #break_time, #signout_time, #working_hours').val('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch attendance:', error);
                }
            });
        }
    }

</script>
@endpush