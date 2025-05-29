@extends('layouts.app')

@section('css')
<style>
    .dt-buttons{
        float: left;
        margin-top: 5px;
        margin-left: 10px;
    }
    .dataTables_filter{
        float: right;
        margin-top: 5px;
        margin-right: 10px;
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
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div class=" float-end mt-15 mr-20"></div>
                            
                            <!-- Filter -->
                            <div class="row mb-2">
                                <div class="col-md-3 mt-15">
                                    <label for="formDate">From</label>
                                    <input type="text" name="from_date" class="form-control" id="from_date" value="{{ date('d-m-Y') }}" />
                                </div>

                                <div class="col-md-3 mt-15">
                                    <label for="to_date">To</label>
                                    <input type="text" name="to_date" class="form-control" id="to_date" value="{{ date('d-m-Y') }}" />
                                </div>

                                <div class="col-md-3 mt-15">
                                    <button type="button" onclick="lateComersList()" id="lateComersList" class="btn btn-primary mt-15">Filter</button>
                                </div>

                            </div>

                             <!-- Filter Result  -->   
                            <table class="datatables-basic datatables-late-comers table border-top table-stripedc table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th width="8%">Sl. No.</th>
                                        <th><i class="ti ti-users"></i></th>
                                        <th>Fullname</th>
                                        <th>Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
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

<!-- Modal -->
<div class="modal fade" id="viewMoreDetails" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">View Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function(){
        $('#from_date, #to_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        lateComersList();
        
    });

    function lateComersList() {
    const fromDate = $('#from_date').val();
    const toDate = $('#to_date').val();

    $('.datatables-late-comers').DataTable().destroy();

    $('.datatables-late-comers').DataTable({
        processing: true,
        serverSide: false,
        dom: 'Bfrtip',
        ajax: {
            url: "{{ route('list-of-latecomers-data') }}",
            type: "GET",
            data: {
                from_date: fromDate,
                to_date: toDate,
            }
        },
        buttons: [
            { extend: 'excelHtml5', title: 'All Attendance Report'},
            { extend: 'pdfHtml5', title: 'All Attendance Report', orientation: 'landscape', pageSize: 'A4'},
            { extend: 'print', title: 'All Attendance Report'}
        ],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'user', name: 'user' },
            { data: 'fullname', name: 'fullname' },
            { data: 'count', name: 'count' },
            { data: 'action', name: 'action' },
        ],
        columnDefs: [
            { orderable: false, targets: [1, 4] },
            { searchable: false, targets: [1, 4] }
        ]
    });
}

function viewMoreModal(id) {
    const fromDate = $('#from_date').val();
    const toDate = $('#to_date').val();

    const url = "{{ route('user-latecomers-list') }}";
    $.ajax({
        url: url,
        data: {
            id: id,
            from_date: fromDate,
            to_date: toDate
        },
        type: 'GET',
        success: function (data) {
            $('#viewMoreDetails .modal-body').html(data.html);
            $('.modal-title').text(data.meta_title);
            $('#viewMoreDetails').modal('show');
            $('#target_id').val(id);
        },
        error: function () {
            alert('Failed to load MOM data.');
        }
    });
}
</script>
@endpush
