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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> StockReports</h4>
                    <div class="row">
                        <div class="md-4 mb-2">
                        <a class="btn btn-primary" href="{{route('assets.dashboard'); }}">Assets Dashboad</a>
                        </div>
                    </div>
                    <div class="row">

                        <div class="row">

                            <div class="col-xl-3 col-md-4 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="badge p-2 bg-label-danger mb-2 rounded">
                                           <span class="fs-3">{{ $stock_in_hand; }} </span>
                                        </div>
                                        <h5 class="card-title mb-1 pt-2">Stock</h5>
                                        <small class="text-muted">Assets in store</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-4 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="badge p-2 bg-label-success mb-2 rounded">
                                           <span class="fs-3">{{ $stock_allocated; }}</span>
                                        </div>
                                        <h5 class="card-title mb-1 pt-2">Allocated</h5>
                                        <small class="text-muted">Allocated to users</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-4 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="badge p-2 bg-label-warning mb-2 rounded">
                                           <span class="fs-3">{{ $stock_repaired; }}</span>
                                        </div>
                                        <h5 class="card-title mb-1 pt-2">Repair</h5>
                                        <small class="text-muted">Sent for repair </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-4 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="badge p-2 bg-label-info mb-2 rounded">
                                           <span class="fs-3">{{ $stock_scraped; }}</span>
                                        </div>
                                        <h5 class="card-title mb-1 pt-2">Scrap</h5>
                                        <small class="text-muted">Scrapped</small>
                                    </div>
                                </div>
                            </div>

                            {{-- @foreach ($assetsClassified as $item)
                                <div class="col-xl-2 col-md-4 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="badge p-2 bg-label-primary mb-2 rounded">
                                            <span class="fs-3">{{ $item->total_quantity }}</span>
                                            </div>
                                            <h5 class="card-title mb-1 pt-2">{{ $item->asset_classification->name }}</h5>
                                            <small class="text-muted">Assets</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach --}}

                        </div>

                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="location_status">Location Status</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="location_status" id="all_location" value="all" checked>
                                                <label class="form-check-label" for="all_location">All</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="location_status" id="location_allocated" value="allocated">
                                                <label class="form-check-label" for="location_allocated">Allocated</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="location_status" id="location_instore" value="in_store">
                                                <label class="form-check-label" for="location_instore">In Store</label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="classification">Classification</label>
                                            <select name="classification" id="classification" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select name="type" id="type" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="asset_item_id">Items</label>
                                            <select name="asset_item_id" id="asset_item_id" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} [{{$item->item_code }}] </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="model">Model</label>
                                            <select name="model" id="model" class="form-control select2">
                                                <option value="">All</option>
                                                @foreach ($items as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} [{{$item->item_code }} - {{ $item->brand }}] </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="vendor">Vendor</label>
                                            <select name="vendor" id="vendor" class="form-control select2">
                                                <option value=" ">All</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <button type="button" onclick="get_stock_reports()" class="btn btn-primary mt-4" id="search">Filter</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered" id="stock-report-table" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Asset ID</th>
                                            <th>Item Name</th>
                                            <th>Model</th>
                                            <th>Serial Number</th>
                                            <th>Classification</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Vendor</th>
                                            <th>Allocation Status</th>
                                            <th>User</th>
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
        get_stock_reports();
    });

    function get_stock_reports() {
        if ($.fn.DataTable.isDataTable('#stock-report-table')) {
            $('#stock-report-table').DataTable().clear().destroy();
        }
        const location_status = $('input[name="location_status"]:checked').val();
        const classification = $('#classification').val();
        const category = $('#category').val();
        const type = $('#type').val();
        const asset_item_id = $('#asset_item_id').val();
        const model = $('#model').val();
        const vendor = $('#vendor').val();

        const stockTable = $('#stock-report-table') ;

        stockTable.DataTable({
            dom: 'Bfrtip',
            buttons: [
                { extend: 'excelHtml5', title: 'Stock Item Report'},
                { extend: 'pdfHtml5', title: 'Stock Item Report'},
                { extend: 'print', title: 'Stock Item Report'}
            ],
            processing: false,
            serverSide: false,
            ajax: {
                url:'{{ route("assets.reports.stock-items-data") }}',
                data: {
                    location_status: location_status,
                    classification: classification,
                    category: category,
                    type: type,
                    model: model,
                    vendor: vendor,
                    asset_item_id:asset_item_id
                }
            },
            dataSrc: 'data',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'asset_id'},
                { data: 'item' },
                { data: 'model' },
                { data: 'serial_number'},
                { data: 'classification' },
                { data: 'category' },
                { data: 'type' },
                { data: 'vendor' },
                {
                    data: 'allocation_status',
                    render: function (data, type, row) {
                        if (data === 'Allocated') {
                            return '<p class="text-success">Allocated</p>';
                        } else {
                            return '<p class="text-danger">Not Allocated</p>';
                        }
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user',
                    render: function(data) {
                        return data ? data : '<span class="text-muted">In Store</span>';
                    }
                },
            ]
        });
    }




</script>
@endpush
