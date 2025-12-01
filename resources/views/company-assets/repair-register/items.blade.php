@extends('layouts.app')

@section('css')
<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .w-90 { width: 90% !important; }

    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
        border-radius: 0px;
    }
    #repair-register-form th {
        text-transform: uppercase;
        font-size: 0.7125rem !important;
        letter-spacing: 1px;
        padding-top: 0.58rem;
        padding-bottom: 0.58rem;
    }

    #item-line-table > :not(caption) > * > * {
        padding: 0.5rem 0.5rem !important;
        background-color: var(--bs-table-bg);
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
    #item-line-table .form-control{
        border-radius: 0.2rem !important;
        padding: 0.40rem 0.40rem !important;
        font-size: 0.7125rem !important;
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> Repair Register</h4>

                    <div class="row">
                        <div class="col-md-6 pb-3">
                            <a class="btn btn-danger" href="{{ route('assets.dashboard') }}">
                                <i class="ti ti-home me-0 me-sm-1 ti-xs"></i>
                            </a>

                            <a class="btn btn-primary" href="{{ route('assets.repare-register.items') }}">
                                Repair Items
                            </a>
                            <a class="btn btn-secondary" href="{{ route('assets.repair-register.index') }}">
                                Sent / Received
                            </a>

                        </div>
                       <div class="col-md-6 text-end pb-3">
                            <a class="btn btn-primary" href="javascript:void(0);" onclick="openRepairOffcanvas()">
                                <i class="ti ti-plus"></i> Send to Repair
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered" id="repare-register-table" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Asset ID</th>
                                            <th>Classification</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Item</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Key/Id</th>
                                            <th>Serial Number</th>
                                            {{-- <th>Specification</th> --}}
                                            <th>Actions</th>
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

<!-- Repair Offcanvas -->
<div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="repair_offcanvas" aria-labelledby="repairCanvasLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-tools fs-2 text-white"></i>
            <span id="repair_offcanvas-title">
                <h5 class="offcanvas-title text-white">Send to Repair</h5>
                <span class="text-white slogan">Add Repair Entry</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-repair-register-form />
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    $(function () {
        const repareTable = $('#repare-register-table').DataTable({

            dom: 'Blfrtip',
            buttons: [
                { extend: 'excelHtml5', title: 'Allocated Items Report'},
                { extend: 'pdfHtml5', title: 'Allocated Items Report'},
                { extend: 'print', title: 'Allocated Items Report'}
            ],

            processing: false,
            serverSide: false,
            ajax: '{{ route("assets.repare-register.items") }}',
            dataSrc: 'data',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'asset_id' },
                { data: 'classificaiton' },
                { data: 'category' },
                { data: 'type' },
                { data: 'item' },
                { data: 'brand' },
                { data: 'model' },
                { data: 'key_id' },
                { data: 'serial_number' },
                // { data: 'specification' },
                {
                    data: 'id',
                    render: function (data) {
                        return `
                            <button class="btn btn-sm btn-danger" onclick="ItemRetrunStore(${data})" title="Back to Store"><i class="ti ti-arrow-left"></i></button>`;
                    }
                }
            ]
        });
        // <button class="btn btn-sm btn-primary" onclick="openOffcanvas(${data})"><i class="ti ti-edit"></i></button>

        $('#repair-register-form').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    toastr.success(res.message);
                    $('#repair_offcanvas').offcanvas('hide');
                    $('#repair-register-table').DataTable().ajax.reload();
                    $('#repair-register-form')[0].reset();
                    $('#repair-item-container').empty();
                }
            });
        });
    });





    function ItemRetrunStore(id){
         if (confirm('Are you sure to return to store?')) {
            $.ajax({
                url: `{{ route('assets.repair-register.return-store', ':id') }}`.replace(':id', id),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    toastr.error(res.message);
                    $('#repare-register-table').DataTable().ajax.reload();
                }
            });
        }
    }

    function openRepairOffcanvas(id = null) {
        // Similar implementation as scrap
        // $('#repair-register-form')[0].reset();
        // $('#repair-register-form').find('select').val('').trigger('change');
        // $('#repair-item-container').empty();
        $('#repair_offcanvas-title').html(`<h5 class="offcanvas-title text-white">Send to Repair</h5>
            <span class="text-white slogan">Add Repair Entry</span>`);
        new bootstrap.Offcanvas('#repair_offcanvas').show();
    }
</script>
@endpush
