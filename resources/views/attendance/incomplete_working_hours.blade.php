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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Attendance /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-lg-7">
                            <!-- Attendance Marking Card -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> {{ $meta_title }}</h4>
                                </div>
                                <div class="card-body">
                                    <form id="fullDayAttendanceForm" action="{{ route('attendance.full-day-attendance-entry') }}" method="post">
                                        <div class="row">
                                            @csrf
                                            <div class="col-5 mb-3">
                                                <label for="years" class="form-label">Years</label>
                                                <select class="form-control select2" name="years" id="years">
                                                    <option value=""></option>
                                                    @if($years->isNotEmpty())
                                                        @foreach($years as $year)
                                                            <option value="{{ $year }}">{{ $year }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                    
                                            <div class="col-5 mb-3">
                                                <label for="months" class="form-label">Month</label>
                                                <select class="form-control select2" name="months" id="months">
                                                    <option value=""></option>
                                                    @if($months->isNotEmpty())
                                                        @foreach($months as $month)
                                                            <option value="{{ $month->month }}">{{ $month->month_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-2 mt-4 mb-3 gap-2">
                                                <button type="button" onclick="getIncompleteWorkingHoursReport()"class="btn btn-primary"> Search </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="reportView"></div>
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

    });
    
    /* Get Incomplete Working Hours */
    function getIncompleteWorkingHoursReport() {
        var year = $('#years').val();
        var month = $('#months').val();
        var url = "{{ route('attendance.get-incomplete-working-hours-report') }}";
        $.ajax({
            type: "get",
            url: url,
            data: { year: year, month: month },
            dataType: "json",
            success: function (response) {
                $('#reportView').html(response.html);
                $('#incompleteWorkingHoursTable').DataTable();
            }
        });    
    }
</script>
@endpush