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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Views /</span> {{ $meta_title }}</h4>
                    <div class="row">
                        <div class="card">
                            <div class="card-datatable table-event">
                                <table class="datatables-basic datatables-holiday table border-top table-stripedc table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name of Holiday</th>
                                            <th>Date of Holiday</th>
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

@stop

@push('js')
<script>


    $(function(){

        var eventTable = $('.datatables-holiday');

        if(eventTable.length){
            eventTable.DataTable({
                processing: false,
                serverSide: false,
                ajax: {
                    type: "GET",
                    url: "{{ route('view.holiday') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: null,
                        title: 'S.No',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false

                    },
                    { data: 'holiday_name', name: 'Name of Holiday' },
                    { data: 'date', name: 'Date of Holiday' },

                ]
            });
        }


    });


</script>
@endpush
