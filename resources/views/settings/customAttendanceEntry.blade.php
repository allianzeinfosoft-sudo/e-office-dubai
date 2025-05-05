@extends('layouts.app')

@section('css')

@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">    
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
                                    <form id="customAttendanceForm" action="{{ route('attendance.custom-attendance-entry') }}" method="post">
                                        <div class="row">
                                            @csrf
                                            <div class="col-12 mb-3">
                                                <label for="employee">Employees</label>
                                                <select class="form-control select2" name="employee" id="employee">
                                                    <option value="">Select</option>
                                                    @if($employees->isnotempty())
                                                        @foreach($employees as $employee)
                                                            <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <label for="signin_date" class="form-label">Date</label>
                                                <input type="text" id="signin_date" name="signin_date" class="form-control flatpickr-input" value=""  placeholder="Date" />
                                            </div>
                                    
                                            <div class="col-6 mb-3">
                                                <label for="signin_time" class="form-label">Time</label>
                                                <input type="time" id="signin_time" name="signin_time" step="1" class="form-control" value=""  placeholder="Time" />
                                            </div>
                                            
                                            <div class="col-sm-12 d-flex justify-content-end align-items-center gap-2">
                                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close"> Close </button>
                                                <button type="button" onclick="customAttendanceEntry()"class="btn btn-primary"> Submit </button>
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

        $('#signin_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });
        
    });

    function customAttendanceEntry() {
        var url = "{{ route('attendance.custom-attendance-entry') }}";
        var formData = $('#customAttendanceForm').serialize();

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.success) {
                    $('#customAttendanceForm')[0].reset();
                    $('.select2').val(null).trigger('change');
                     // Success message or reload
                     Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload(); // or you can reset the form or close the modal
                    });
                    
                } else {
                    alert(response.message || 'Entry failed.');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                window.location.reload();
            }
        });
    }
</script>
@endpush
