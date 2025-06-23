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
                            <table class="hover_effect datatables-basic datatables-feedback-report table border-top table-stripedc" id="datatables-feedback-assign">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Feedback Title</th>
                                        <th>Department</th>
                                        <th>Feedback Start Date</th>
                                        <th>Feedback End Date</th>
                                        <th>Created By</th>
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
        var feedbackTemplateTable = $('.datatables-feedback-report'),
        select2 = $('.select2');
        if (feedbackTemplateTable.length) {

            feedbackTemplateTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('feedback.report.list') }}", // Fixed syntax
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
                    { data: 'feedback_title', title: 'Feedback Title' },
                    { data: 'department', title: 'Department' },
                    { data: 'feedback_start_date', title: 'Feedback Start Date'},
                    { data: 'feedback_end_date', title: 'Feedback End Date'},
                    { data: 'created_by', title: 'Created By' },

                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const reportUrl = "{{ route('feedback.export', ':id') }}".replace(':id', row.feedback_id);
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
