@extends('layouts.app')

@section('css')
<style>
    .dt-buttons{
        float: left;
        margin-top: 10px;
        margin-left: 10px;
    }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container bg-eoffice">
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> ScrapReports</h4>

                    <div class="row">

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="from_date">From Date</label>
                                            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="to_date">To Date</label>
                                            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="scrap_date">Vendors</label>
                                            <select name="vendor_id" id="vendor_id" class="form-control select2">
                                                <option value="">All</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="button" onclick="get_reports()" class="btn btn-primary mt-4" id="search">Filter</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered" id="scrap-register-table" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Scrap No</th>
                                            <th>Scrap Date</th>
                                            <th>Vendor</th>
                                            <th>Item</th>
                                            <th>Asset No</th>
                                            <th>Model</th>
                                            <th>Serial No</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <x-footer />
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $(function () {
        $('#from_date , #to_date').flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });
        get_reports();
    });
    
    function get_reports() {
        if ($.fn.DataTable.isDataTable('#scrap-register-table')) {
            $('#scrap-register-table').DataTable().clear().destroy();
        }
        const from_date = $('#from_date').val();
        const to_date = $('#to_date').val();
        const vendor_id = $('#vendor_id').val();
        
        const scrapTable = $('#scrap-register-table') ;

        scrapTable.DataTable({
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', title: 'Scrap Item Report from ' + from_date + ' to ' + to_date},
                { extend: 'pdfHtml5', title: 'Scrap Item Report from ' + from_date + ' to ' + to_date , orientation: 'portrait', pageSize: 'A4'},
                { extend: 'print', title: 'Scrap Item Report from ' + from_date + ' to ' + to_date}
            ],
            processing: false,
            serverSide: false,
            ajax: {
                url:'{{ route("assets.reports.scrap-items-data") }}',
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    vendor_id: vendor_id
                }
            },
            dataSrc: 'data',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'scrap_no' },
                { data: 'scrap_date' },
                { data: 'vendor_name' },
                { data: 'item_name' },
                { data: 'asset_code' },
                { data: 'item_model' },
                { data: 'serial_number' },
                { data: 'unit' },
                { data: 'quantity' },
                { data: 'rate' },
                { data: 'amount' },
                { data: 'remarks' },
            ]
        });
    }

</script>
@endpush
