@extends('layouts.app')

@section('css')
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- Menu section -->
        <x-menu />

        <!-- Page content -->
        <div class="layout-page">

            <!-- Header Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Attendance /</span>{{ $meta_title }}</h4>

                    <div class="row">

                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <small class="d-block mb-1 text-danger"> You have missed to markout on {{ date('d-m-Y', strtotime( $missingMarkOut->signin_date )) }} </small>
                                    </div>
                                    <h4 class="card-title mb-1"> <i class="ti ti-clock ti-sm"></i> {{ $meta_title }}</h4>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <form id="customMarkOutForm" action="{{ route('attendance.custom-mark-out', $missingMarkOut->id) }}" method="post">
                                            @csrf
                                            <div class="col-12 mb-3">
                                                <label for="signin_date" class="form-label">Date</label>
                                                <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($missingMarkOut->signin_date))  }}"  placeholder="Date" disabled readonly />
                                            </div>
                                
                                            <div class="col-12 mb-3">
                                                <label for="signout_time" class="form-label">Time</label>
                                                <input type="time" id="signout_time" name="signout_time" class="form-control" value="{{ date('H:i:s', strtotime('now')) }}"  placeholder="Time" />
                                                <input type="hidden" id="signout_date" name="signout_date" class="form-control" value="{{ $missingMarkOut->signin_date }}"  placeholder="Time" />
                                            </div>
                                
                                            <div class="col-12 mb-3">
                                                <label for="signout_late_note" class="form-label">Reason</label>
                                                <textarea id="signout_late_note" name="signout_late_note" class="form-control"  placeholder="Reason" rows="5" required> </textarea>
                                            </div>
                                        </form>
        
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" onclick="customMarkOut()" class="btn btn-primary">Mark Out</button>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>

                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>

    $(function(){

    });

    function customMarkOut(){
        let form = $('#customMarkOutForm');
        let formData = form.serialize();
    
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Mark out updated successfully!');
                    location.reload(); // Reload the page (optional)
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = "Something went wrong.";
                if (errors) {
                    errorMessage = Object.values(errors).map(e => e.join('\n')).join('\n');
                }
                alert(errorMessage);
            }
        });
    }
</script>
@stop