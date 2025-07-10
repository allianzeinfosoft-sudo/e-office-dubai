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

    #item-receive-modal {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 1055 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
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
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn btn-primary" href="javascript:void(0);" onclick="openRepairOffcanvas()">
                                <i class="ti ti-plus"></i> Send to Repair
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered table-striped" id="repair-register-table" style="font-size: 12px; width: 100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Date/No.</th>
                                            <th>Asset ID</th>
                                            <th>Item</th>
                                            <th>Model</th>
                                            <th>serial no</th>
                                            <th>Vendor</th>
                                            <th>Unit</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Received</th>
                                            <th>Remarks</th>
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

<!-- Modal -->
<div class="modal" tabindex="-1" id="item-receive-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


@stop

@push('js')
<script>
    $(function () {

        const repairTable = $('#repair-register-table').DataTable({
            processing: false,
            serverSide: false, // If you're not using Laravel server-side processing
            ajax: {
                url: '{{ route("assets.repair-register.index") }}',
                dataSrc: 'data' // ensures data is fetched from `data` key in response
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'repair_date', name: 'repair_date' },
                { data: 'asset_id_number', name: 'asset_id_number' },
                { data: 'item_name', name: 'item_name' },
                { data: 'item_model', name: 'item_model' },
                { data: 'serial_no', name: 'serial_no' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'unit', name: 'unit' },
                { data: 'quantity', name: 'quantity' },
                { data: 'rate', name: 'rate' },
                { data: 'amount', name: 'amount' },
                { data: 'status', name: 'status' },
                { 
                    data: null,
                    render: function (data, type, row, meta) {
                        return row.received_date != null
                            ? row.received_date
                            : `<button class="btn btn-sm btn-success" onclick="receiveRepairItem()">Receive</button>`;
                    }
                },
                { data: 'remarks', name: 'remarks' },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return `
                            <button class="btn btn-sm btn-danger" onclick="deleteRepair(${data})"><i class="ti ti-trash"></i></button>
                        `;
                    }
                }
            ]
        });

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
                    repairTable.ajax.reload();
                    $('#repair-register-form')[0].reset();
                    $('#repair-item-container').empty();
                }
            });
        });
    });

    function openRepairOffcanvas(id = null) {
        // Similar implementation as scrap
        // $('#repair-register-form')[0].reset();
        // $('#repair-register-form').find('select').val('').trigger('change');
        // $('#repair-item-container').empty();
        $('#repair_offcanvas-title').html(`<h5 class="offcanvas-title text-white">Send to Repair</h5>
            <span class="text-white slogan">Add Repair Entry</span>`);
        new bootstrap.Offcanvas('#repair_offcanvas').show();
    }

    function deleteRepair(id) {
        if (confirm('Delete this repair entry?')) {
            $.ajax({
                url: `{{ route('assets.repair-register.destroy', ':id') }}`.replace(':id', id),
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    toastr.error(res.message);
                    $('#repair-register-table').DataTable().ajax.reload();
                }
            });
        }
    }

    function receiveRepairItem(id) {
        const modal = new bootstrap.Modal(document.getElementById('item-receive-modal'));
        modal.show();
    }

    
</script>
@endpush
