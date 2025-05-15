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
                                    <form id="fullDayAttendanceForm" action="{{ route('attendance.full-day-attendance-entry') }}" method="post">
                                        <div class="row">
                                            @csrf
                                            <div class="col-12 mb-3">
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
                                                <label for="signin_date" class="form-label">Start Date</label>
                                                <input type="text" id="signin_date" name="signin_date" class="form-control flatpickr-input" value="{{ date('d-m-Y') }}"  placeholder="Mark in Date" />
                                            </div>
                                    
                                            <div class="col-6 mb-3">
                                                <label for="signout_date" class="form-label">End Date</label>
                                                <input type="text" id="signout_date" name="signout_date" class="form-control flatpickr-input" value="{{ date('d-m-Y') }}"  placeholder="Date" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="signin_time" class="form-label">Mark In Time</label>
                                                <input type="time" id="signin_time" name="signin_time" step="1" class="form-control" value=""  placeholder="Time" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="break_time" class="form-label">Brake Time</label>
                                                <input type="time" id="break_time" name="break_time" step="1" class="form-control" value="{{ date('H:i:s', strtotime('1:00')) }}"  placeholder="Time" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="signout_time" class="form-label">Mark Out Time</label>
                                                <input type="time" id="signout_time" name="signout_time" step="1" class="form-control" value=""  placeholder="Time" />
                                            </div>

                                            <div class="col-3 mb-3">
                                                <label for="working_hours" class="form-label">Working Hours</label>
                                                <input type="time" id="working_hours" name="working_hours" step="1" class="form-control" value=""  placeholder="Time" />
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="signin_late_note" class="form-label">Reason</label>
                                                <textarea name="signin_late_note" class="form-control" id="signin_late_note"></textarea>
                                            </div>
                                            
                                            <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
                                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close"> Close </button>
                                                <button type="button" onclick="fullDayMarking()"class="btn btn-primary"> Submit </button>
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

const CustomHelper = {
    calculateWorkingHours: function(startTime, endTime, breakTime) {
        if (!startTime || !endTime) return 0;

        let start = new Date('1970-01-01T' + startTime + ':00');
        let end = new Date('1970-01-01T' + endTime + ':00');

        // Handle overnight shifts (e.g., 10 PM to 6 AM)
        if (end < start) {
            end.setDate(end.getDate() + 1);
        }

        let diff = (end - start) / (1000 * 60 * 60); // in hours
        diff -= parseFloat(breakTime) || 0;

        return diff > 0 ? parseFloat(diff.toFixed(2)) : 0;
    },
    convertHoursToTimeFormat: function(hours) {
        let h = Math.floor(hours);
        let m = Math.round((hours - h) * 60);
        return (h < 10 ? '0' : '') + h + ':' + (m < 10 ? '0' : '') + m;
    }
};

    $(function(){

        $('#signin_date, #signout_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        $('.select2').select2();

        $('#signout_time').on('change', function () {
            var startTime = $('#signin_time').val();
            var endTime = $(this).val();
            var breakTime = $('#break_time').val();
            
            if (!startTime || !endTime) return;

            var workingHours = CustomHelper.calculateWorkingHours(startTime, endTime, breakTime);
            $('#working_hours').val(CustomHelper.convertHoursToTimeFormat(workingHours));
        });
        
    });

    function fullDayMarking() {
        var formData = new FormData(document.getElementById('fullDayAttendanceForm'));

        $.ajax({
            url: "{{ route('attendance.full-day-attendance-entry') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status === 'success') {

                    // Success message or reload
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload(); // or you can reset the form or close the modal
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Handle form validation errors
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        // Append error message for each field
                        errorMsg += value + '\n';
                        // Highlight the invalid fields
                        $('#' + key).addClass('is-invalid'); // Add bootstrap 'is-invalid' class for visual feedback
                        // Optionally, display error message under the field
                        $('#' + key).next('.invalid-feedback').remove();
                        $('#' + key).after('<div class="invalid-feedback">' + value + '</div>');
                    });
                    // Show validation errors in a popup
                    Swal.fire({
                        title: 'Validation Error!',
                        text: errorMsg,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Handle generic errors
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

</script>
@endpush