@extends('layouts.app')

@section('css')
<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
        border-radius: 0px;
    }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Others /</span> {{ $meta_title }}</h4>

                    <div class="row">


                        <div class="card">
                            <div class="card-datatable table-policy">
                                <table class="datatables-basic datatables-policy table border-top table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No.</th>
                                            <th style="width: 45%;">Policy</th>
                                            <th style="width: 20%;">Department</th>
                                            <th style="width: 30%;">Project</th>
                                            {{-- <th>Actions</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <x-footer />
                <div class="content-backdrop fade"></div>
                <div class="layout-overlay layout-menu-toggle"></div>
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="policy_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i>
            <span id="policy-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Policy</h5>
                <span class="text-white slogan">Create New Policy</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-policy-form />
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    const quillPolicy = new Quill('#policy-description-editor', {
        theme: 'snow',
        placeholder: 'Type policy description here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    $(function(){


        const endPicker = $("#pollicyEndDate").flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        })

        $("#policyStartDate").flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y',
            onChange: function(selectedDates, dateStr, instance) {
            // Set minDate of end date based on start date
                $("#pollicyEndDate")[0]._flatpickr.set('minDate', dateStr);
            }
        });

        var policyTable = $('.datatables-policy');

        if(policyTable.length){
            const assetBaseUrl = "{{ asset('storage') }}";

            policyTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: "GET",
                    url: "{{ route('others.policies.index') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'row', name: 'No' },
                    {
                        data: null,
                        title: 'Policy',
                        render: function (data, type, row) {
                            const title = row.policyTitle ?? 'Untitled';

                            if (row.attachments) {
                                return `
                                    <a href="${assetBaseUrl}/policies/${row.attachments}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-success"
                                    title="Download Policy">
                                        ${title} <i class="ti ti-download"></i>
                                    </a>`;
                            } else {
                                return `<span class="text-muted">${title}</span>`;
                            }
                        }
                    },
                    { data: 'department', name: 'Department'},
                    { data: 'project', name: 'Project'},
                    // {
                    //     data: null,
                    //     title: 'Actions',
                    //     render: function (data, type, row) {
                    //         return ` <a href="${assetBaseUrl}/policies/${row.attachments}" target="_blank" class="btn btn-sm btn-icon btn-outline-success"><i class="ti ti-download"></i></a>`;
                    //     }
                    // }
                ]
            });
        }


    });




</script>
@endpush
