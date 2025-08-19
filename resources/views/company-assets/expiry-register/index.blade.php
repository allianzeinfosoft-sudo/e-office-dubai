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


   /* Expired: strong pulse */
    .btn-expired {
    animation: pulseScaleRed 0.9s ease-in-out infinite alternate;
    will-change: transform;
    }
    @keyframes pulseScaleRed {
    0% { transform: scale(1); }
    100% { transform: scale(1.08); }
    }

    /* Expiring soon: softer pulse */
    .btn-expiring {
    animation: pulseScaleYellow 1.2s ease-in-out infinite alternate;
    will-change: transform;
    }
    @keyframes pulseScaleYellow {
    0% { transform: scale(1); }
    100% { transform: scale(1.05); }
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> Expiry Register</h4>

                    <div class="row mb-3 pb-3 align-items-center">

                        <div class="col-md-6 pb-3">
                             <a class="btn btn-danger" href="{{ route('assets.dashboard') }}">
                                <i class="ti ti-home me-0 me-sm-1 ti-xs"></i>
                            </a>
                        </div>
                        <div class="col-md-6 text-end pb-3">
                            <a class="btn btn-primary" href="javascript:void(0);" onclick="openExpiryOffcanvas()">
                                <i class="ti ti-plus"></i> New
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered table-striped" id="asset-expiry-table" style="font-size: 12px; width: 100%;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Service Name</th>
                                            {{-- <th>Category</th> --}}
                                            <th>Type</th>
                                            <th>Vendor</th>
                                            <th>Licence ID</th>
                                            <th>Licence Count</th>
                                            <th>Start Date</th>
                                            <th>Last Updated Date</th>
                                            <th>Expiry Date</th>
                                            <th>Cost</th>
                                            <th>Remarks</th>
                                            <th>Expiry Days</th>
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
<div class="offcanvas offcanvas-end w-40" data-bs-backdrop="static" tabindex="-1" id="expiry_offcanvas" aria-labelledby="expiryCanvasLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-tools fs-2 text-white"></i>
            <span id="expiry_offcanvas-title">
                <h5 class="offcanvas-title text-white">Asset Expiry</h5>
                <span class="text-white slogan">Add Asset Expiry</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-asset-expiry-form />
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    $(function () {

        const expiryTable = $('#asset-expiry-table').DataTable({
            processing: false,
            serverSide: false, // If you're not using Laravel server-side processing
            ajax: {
                url: '{{ route("assets.expiry-register.index") }}',
                dataSrc: 'data' // ensures data is fetched from `data` key in response
            },
            columns: [
                { data: 'DT_RowIndex', title: 'Sl No' },
                { data: 'service_name', title: 'Service Name' },
                // { data: 'asset_category', title: 'Category' },
                { data: 'asset_type', title: 'Type' },
                { data: 'asset_vendor', title: 'Vendor' },
                { data: 'licence_id', title: 'Licence ID' },
                { data: 'licence_count', title: 'Licence Count' },
                { data: 'start_date', title: 'Start Date' },
                { data: 'last_updated_date', title: 'Last Updated Date' },
                { data: 'expiry_date', title: 'Expiry Date'},
                { data: 'cost', title: 'Cost' },
                { data: 'remarks', name: 'Remarks' },
                {
                    data: 'expiry_date',
                    title: 'Expiry Days',
                    render: function(data) {
                        if (!data) {
                            return '<button class="btn btn-secondary btn-sm">N/A</button>';
                        }

                        // Split DD-MM-YYYY
                        let parts = data.split("-");
                        let day = parseInt(parts[0], 10);
                        let month = parseInt(parts[1], 10) - 1; // JS months are 0-based
                        let year = parseInt(parts[2], 10);

                        let expiryDate = new Date(year, month, day);
                        let today = new Date();

                        // Set to midnight for accurate diff
                        expiryDate.setHours(0, 0, 0, 0);
                        today.setHours(0, 0, 0, 0);

                        let diff = Math.ceil((expiryDate - today) / (1000 * 60 * 60 * 24));

                        let btnClass = "btn btn-sm ";
                        if (diff <= 0) {
                            btnClass += "btn-danger btn-expired"; // red
                        } else if (diff <= 7) {
                            btnClass += "btn-warning btn-expiring"; // yellow
                        } else {
                            btnClass += "btn-success"; // green
                        }

                        return '<button class="'+btnClass+'">'+diff+' Days</button>';
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return `
                             <a href="javascript:void(0)" onclick="openExpiryOffcanvas(${data})" class="btn btn-sm btn-icon btn-primary">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="deleteExpiry(${data})"><i class="ti ti-trash"></i></button>
                        `;
                    }
                }
            ]
        });

        $('#expiry-register-form').submit(function (e) {
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
                    $('#expiry_offcanvas').offcanvas('hide');
                    $('#asset-expiry-table').DataTable().ajax.reload();
                    $('#expiry-register-form')[0].reset();
                    $('#expiry-item-container').empty();
                }
            });
        });

        $('#repair_date').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        $('#update_receive_item').submit(function (e) {
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
                    $('#item_receive_offcanvas').offcanvas('hide');
                    repairTable.ajax.reload();
                    $('#update_receive_item')[0].reset();
                }
            });
        });

    });

    function openExpiryOffcanvas(id = null) {

        const $form = $('#expiry-register-form');
        $form[0].reset();
        $('#target_id').val('');

        $('#expiry_offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create Asset Expiry</h5>
            <span class="text-white slogan">Add Asset Expiry</span>`);

        const offcanvasElement = $('#expiry_offcanvas');
         if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            const url = "{{ route('assets.expiry-register.edit', ':assetExpiry') }}".replace(':assetExpiry', id);
            $('#target_id').val(id);
            $('#expiry_offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit Asset Expiry</h5><span class="text-white slogan">Edit Asset Expiry</span>`);

            $.ajax({
                url: url,
                dataType: 'json',
                type: 'GET',
                success: function (response) {
                    expiryAsset = response.data;
                    $('#target_id').val(expiryAsset.id);
                    $('#service_name').val(expiryAsset.service_name);
                    // $('#asset_category_id').val(String(expiryAsset.asset_categories_id)).trigger('change');
                    $('#asset_types_id').val(String(expiryAsset.asset_types_id)).trigger('change');
                    $('#asset_vendor_id').val(String(expiryAsset.asset_vendors_id)).trigger('change');
                    $('#licence_id').val(expiryAsset.licence_id);
                    $('#licence_count').val(expiryAsset.licence_count);
                    $('#cost').val(expiryAsset.cost);

                    let startPicker = flatpickr("#start_date", {
                            altInput: true,
                            altFormat: 'd-m-Y',
                            dateFormat: 'Y-m-d'
                        });

                    // Set value from DB
                    startPicker.setDate(expiryAsset.start_date);

                      let last_updatedPicker = flatpickr("#last_updated_date", {
                            altInput: true,
                            altFormat: 'd-m-Y',
                            dateFormat: 'Y-m-d'
                        });

                    // Set value from DB
                    last_updatedPicker.setDate(expiryAsset.last_updated_date);

                      let expiry_datePicker = flatpickr("#expiry_date", {
                            altInput: true,
                            altFormat: 'd-m-Y',
                            dateFormat: 'Y-m-d'
                        });

                    // Set value from DB
                    expiry_datePicker.setDate(expiryAsset.expiry_date);

                    $('#remarks').val(expiryAsset.remarks);

                },
                error: function () {
                    alert('Failed to load Asset Expirty data.');
                }
            });
        }

        // new bootstrap.Offcanvas('#expiry_offcanvas').show();
    }


    function deleteExpiry(id) {
        if (confirm('Delete this expiry entry?')) {
            $.ajax({
                url: `{{ route('assets.expiry-register.destroy', ':id') }}`.replace(':id', id),
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    toastr.error(res.message);
                    $('#asset-expiry-table').DataTable().ajax.reload();
                }
            });
        }
    }



</script>
@endpush
