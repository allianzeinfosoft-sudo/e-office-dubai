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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"> View /</span> {{ $meta_title }}</h4>

                    @if($moms->count() > 0)
                        @foreach($moms as $mom)

                        <div class="card">
                            <div class="card-header">
                                <h5>{{ $mom->mom_title }}</h5>
                                <hr />
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th> Title </th>
                                                    <th colspan="3"> {{ $mom->mom_title }} </th>
                                                </tr>
                                                <tr>
                                                    <th width="25%">Date</th>
                                                    <th width="25%"> {{ $mom->mom_date }} </th>
                                                    <th width="25%">Created By</th>
                                                    <th width="25%"> {{ $mom->employee->full_name }} </th>
                                                </tr>
                                                <tr>
                                                    <th>Assigned To</th>
                                                    <th colspan="3"> {{ implode(', ', $mom->AssignedToEmployee) }} </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"> {!! $mom->mom_details !!} </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4"> {!! $mom->attachments ? ' <a href="' . asset('public/moms/' . $mom->attachments) . '" target="_blank"><i class="ti ti-file"></i> </a>' : '' !!} </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    @endif

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
</script>
@endpush