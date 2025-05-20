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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div class=" float-end mt-15 mr-20"></div>

                            <table class="datatables-basic datatables-custom-attendance table border-top table-stripedc table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th><i class="ti ti-users"></i></th>
                                        <th>Fullname</th>
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($employees) > 0)
                                        @foreach ($employees as $key => $employee)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if ($employee->profile_image)
                                                        <img src="{{ asset('/storage/' . $employee->profile_image) }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                                    @else
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                                                    @endif
                                                </td>
                                                <td>{{ $employee->full_name }}</td>
                                                <td>{{ $employee->username }}</td>
                                                <td>
                                                    @if ($employee->status == 1)
                                                        <span class="badge bg-label-danger">Blocked</span>
                                                    @else
                                                        <span class="badge bg-label-danger">Active</span>
                                                    @endif
                                                </td>
                                                <td>{{ $employee->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-primary" href="#"><i class="bx bx-show me-1"></i> Weight List</a>
                                                </td>
                                            </tr>
                                        @endforeach 
                                    @endif
                                </tbody>
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
        $('.datatables-basic').DataTable({});
    });
</script>
@endpush