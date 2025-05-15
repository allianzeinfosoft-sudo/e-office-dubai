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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> Recuritments /</span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-basic datatables-rrf-approvals table border-top table-stripedc table-hover table-striped">
                               <thead>
                                   <tr>
                                       <th>Sl. No.</th>
                                       <th>Date</th>
                                       <th>Job Title</th>
                                       <th>Project Name</th>
                                       <th>Designation</th>
                                       <th>Priority</th>
                                       <th>Actions</th>
                                   </tr>
                               </thead> 
                            </table>
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
        var rrfTable = $('.datatables-rrf-approvals');

        if (rrfTable.length) {
            rrfTable = rrfTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: "GET",
                    url: "{{ route('recruitments.rrf-approvals') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'row', name: 'Sl. No.', orderable: false, searchable: false },
                    { data: 'rrfDate', name: 'Date' },
                    { data: 'jobTitle', name: 'Job Title' },
                    { data: 'projectName', name: 'Project Name' },
                    { data: 'designation', name: 'Designation' },
                    { data: 'priority', name: 'Priority' },
                    { data: 'interviewer', name: 'Interviewer' },
                    { data: null, name: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
            });
        }

    });
</script>
@endpush