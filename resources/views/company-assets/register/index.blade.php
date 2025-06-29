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
    
    #item-line-table th {
        text-transform: uppercase;
        font-size: 0.6125rem !important;
        letter-spacing: 1px;
        padding-top: 0.68rem;
        padding-bottom: 0.68rem;
    }

    #item-line-table > :not(caption) > * > * {
    padding: 0.20rem 0.20rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
    #item-line-table .form-control{
        border-radius: 0.2rem !important;
        font-size: 0.6125rem !important;
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> Add New</span>
                                </span>
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-mom">
                                <div class="card-datatable table-responsive">
                                    <table class="table table-bordered table-striped" id="asset-register-table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Reg. No.</th>
                                                <th>Date</th>
                                                <th>Vendor</th>
                                                <th>Invoice</th>
                                                <th>Amount</th>
                                                <th>Doc</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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

<div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="vendor_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="vendor-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Asset Register</h5>
                <span class="text-white slogan">Add new Asset Register</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-asset-register-from />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function () {
        const assetRegisterTable = $('#asset-register-table');

        if(assetRegisterTable.length) {            
            assetRegisterTable.DataTable({
            processing: true,
            serverSide: false, // set to true if using server-side pagination
            ajax: '{{ route("assets.register.index") }}', // your route
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'asset_number', name: 'Reg. No.' },
                { data: 'purchase_date', name: 'Date' },
                { data: 'vendor_name', name: 'Vendor' },
                { data: 'invoice_number', name: 'Invoice No.' },
                { data: 'total_amount', name: 'Amount' },
                { data: 'upload_invoice', name: 'Doc' },
                {
                    data: 'id',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <a href="javascript:void(0)" onclick="openOffcanvas(${data})" class="btn btn-sm btn-icon btn-primary">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteVendor(${data})" class="btn btn-sm btn-icon btn-danger">
                                <i class="ti ti-trash"></i>
                            </a>
                        `;
                    }
                }
            ]
            });
        }

        /* Create vendor category */
        $('#vendor-category-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('assets.store-vendor-category') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    toastr["success"](response.message);
                    $('#category-modal').modal('hide');
                    $('#vendor_category').append(`<option value="${response.data.id}" selected>${response.data.name}</option>`).trigger('change');
                }
            });
        });

        /* Store vendot */
        $('#register-form').submit(function (e) {
            let url = $(this).attr('action');
            e.preventDefault();
            $.ajax({
                url: url,
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    toastr["success"](response.message);
                    $('#vendor-table').DataTable().ajax.reload();

                    const offcanvasEl = document.getElementById('vendor_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (offcanvas) {
                        offcanvas.hide();
                    }
                }
            });
        });

    });

    function deleteVendor(id) {
        if (confirm('Are you sure you want to delete this vendor?')) {
            $.ajax({
                url: "{{ route('assets.vendors.destroy', ':id') }}".replace(':id', id),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE" // Important to spoof DELETE method
                },
                success: function (response) {
                    toastr["error"](response.message);
                    $('#vendor-table').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    toastr["error"]("Failed to delete vendor.");
                }
            });
        }
    }

    function openOffcanvas(id = null) {
        const $form = $('#register-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#vendor-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create Asset Register</h5><span class="text-white slogan">Create New Asset Register</span>`);

        const offcanvasElement = $('#vendor_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            $('#target_id').val(id);
            $('#vendor-offcanvas-title').html(`
                <h5 class="offcanvas-title text-white">Edit Asset Register</h5>
                <span class="text-white slogan">Edit Asset Vendor</span>
            `);
            $('#current-attachment').remove();
            url = "{{ route('assets.register.edit', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response.data;

                    // Main form values
                    $('#asset_number').val(data.asset_number);
                    $('#company_name').val(data.company_name).trigger('change');
                    $('#purchase_date').val(data.purchase_date).trigger('change');
                    $('#invoice_number').val(data.invoice_number);
                    $('#vendor_id').val(data.vendor_id).trigger('change');
                    $('#remarks').val(data.remarks);
                    $('#grand_total').val(data.total_amount);
                    $('#total_amount').html(parseFloat(data.total_amount).toFixed(2));

                    // Clear existing line items
                    $('#item-line-container').empty();  

                    // Loop through line items and append rows
                    data.items.forEach(function(item, index) {
                        let row = `
                            <tr>
                                <td>
                                    <select name="asset_item_id[${index}][asset_item_id]" class="form-control select2">
                                        @foreach($assetItems as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_item_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="asset_items[${index}][item_model]" class="form-control" value="${item.item_model ?? ''}"></td>
                                <td><input type="text" name="asset_items[${index}][serial_number]" class="form-control" value="${item.serial_number ?? ''}"></td>
                                <td><input type="text" name="asset_items[${index}][warranty]" class="form-control" value="${item.warranty ?? ''}"></td>
                                <td>
                                    <select name="asset_items[${index}][asset_classification_id]" class="form-control select2">
                                        @foreach($assetClassifications as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_classification_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_items[${index}][asset_category_id]" class="form-control select2">
                                        @foreach($assetCategories as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_category_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_items[${index}][asset_type_id]" class="form-control select2">
                                        @foreach($assetTypes as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_type_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" readonly value="Unit"></td>
                                <td><input type="number" name="asset_items[${index}][asset_quantity]" class="form-control quantity" value="${item.asset_quantity}"></td>
                                <td><input type="number" name="asset_items[${index}][asset_price]" class="form-control price" value="${item.asset_price}"></td>
                                <td><input type="text" name="asset_items[${index}][asset_total]" class="form-control total" readonly value="${item.asset_total}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItemRow(this)"><i class="ti ti-trash"></i></button></td>
                            </tr>
                        `;
                        $('#item-line-container').append(row);
                    });

                    // Re-initialize select2 for dynamically added fields
                    $('.select2').select2();
                },
                error: function() {
                    alert('Failed to load Asset Register data.');
                }
            });
        }
    }

    function viewCategoryModal(id) {
        $('#category-modal').modal('show');
        $('#vendor-category-form')[0].reset();
    }
    

    
    
    
</script>
@endpush
