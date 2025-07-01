@extends('layouts.app')

@section('css')
<style>
.w-35 {
    width: 35% !important;
}
.w-45 {
    width: 45% !important;
}
.offcanvas-close{
    position: absolute;
    top: 0px;
    left: -32px;  /* Moves the button outside the offcanvas */
    z-index: 1055; /* Ensures it stays on top */
    padding: 28px 10px;
    border-radius: 0px;
}

    .modal-content {
        border-radius: 15px;
        border: 2px solid #ff5f10;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background-color: #fdfdfd;
    }



</style>
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
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-sar-report table border-top table-stripedc" id="datatables-sar-assign">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>SAR Title</th>
                                        <th>SAR Name</th>
                                        <th>Department</th>
                                        <th>SAR Start Date</th>
                                        <th>SAR End Date</th>
                                        <th>Created By</th>
                                        <th>Response</th>
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
            </div>
        </div>
    </div>
</div>


@stop


@push('js')
<script>

    $(function() {
        var sarTemplateTable = $('.datatables-sar-report'),
        select2 = $('.select2');
        if (sarTemplateTable.length) {

            sarTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('sar.report.list') }}", // Fixed syntax
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
                    { data: 'sar_title', title: 'SAR Title' },
                    { data: 'sar_name', title: 'SAR Name'},
                    { data: 'department', title: 'Department' },
                    { data: 'sar_start_date', title: 'SAR Start Date'},
                    { data: 'sar_end_date', title: 'SAR End Date'},
                    { data: 'created_by', title: 'Created By' },
                    {
                        data: 'null',
                        title: 'Response',
                        render: function(data, type, row, full){
                                return `<div>
                                            <strong>${row.attended_users ?? 0}</strong> /
                                            <strong>${row.total_users ?? 0}</strong>
                                        </div>`;
                        },
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const reportUrl = "{{ route('sar.export', ':id') }}".replace(':id', row.sar_id);
                                return `<a href="${reportUrl}" class="btn btn-sm btn-icon btn-success" title="Download Report">
                                            <i class="ti ti-download"></i></a>`;


                        }
                    }
                ]
            });
        }
    });


</script>
@endpush
