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

                    <div class="row mb-3 pb-3 align-items-center">

                        <div class="col-md-6 pb-3">
                            <a class="btn btn-primary" href="{{ route('assets.dashboard') }}">
                            Assets Dashboard
                            </a>
                             <a class="btn btn-primary" href="{{ route('assets.register.index') }}">
                            Asset Batches
                            </a>
                            <a class="btn btn-primary" href="{{ route('assets.register.items') }}">
                            Asset Items
                            </a>
                        </div>
                        <div class="col-md-6 text-end pb-3">
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
                                    <table class="table table-bordered table-striped" id="asset-item-table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>Sl No.</th>
                                                <th>Asset ID</th>
                                                <th>Classificatin</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Item</th>
                                                <th>Brand Name</th>
                                                <th>Model</th>
                                                <th>Key/ID</th>
                                                <th>Serial Number</th>
                                                <th>Specifications</th>
                                                <th>Price</th>
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

<div class="offcanvas offcanvas-end w-90" data-bs-backdrop="static" tabindex="-1" id="vendor_offcanvas" aria-labelledby="staticBackdropLabel">
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

<div class="offcanvas offcanvas-end w-90" data-bs-backdrop="static" tabindex="-1" id="edit_vendor_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i>
            <span id="vendor-offcanvas-title">
                <h5 class="offcanvas-title text-white">Edit Asset Item</h5>
                <span class="text-white slogan">Edit Asset Item</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-asset-item-edit-form />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function () {
        const assetRegisterTable = $('#asset-item-table');

        if(assetRegisterTable.length) {
            assetRegisterTable.DataTable({
            processing: true,
            serverSide: false, // set to true if using server-side pagination
            ajax: '{{ route("assets.register.items") }}', // your route
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'asset_id', name: 'Asset ID'},
                { data: 'classification', name: 'Classificatin' },
                { data: 'category', name: 'Category' },
                { data: 'type', name: 'Type' },
                { data: 'item', name: 'Item' },
                { data: 'brand', name: 'Brand Name' },
                { data: 'model', name: 'Model'},
                { data: 'item_key_id', name: 'Key/Id'},
                { data: 'serial_number', name: 'Serial Number'},
                { data: 'specification', name: 'Specifications'},
                { data: 'price', name: 'Price'},
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
                            <a href="javascript:void(0)" onclick="deleteAssetRegister(${data})" class="btn btn-sm btn-icon btn-danger">
                                <i class="ti ti-trash"></i>
                            </a>`;
                    }
                }
            ]
            });
        }

        //   <a href="javascript:void(0)" onclick="openOffcanvas(${data})" class="btn btn-sm btn-icon btn-primary">
        //                         <i class="ti ti-edit"></i>
        //                     </a>
        /* Store Items */
        $('#register-edit-form').submit(function (e) {
            let url = $(this).attr('action');
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,   // MUST be false
                processData: false,   // MUST be false
                success: function (response) {
                    toastr["success"](response.message);
                    $('#asset-item-table').DataTable().ajax.reload();

                    const offcanvasElEdit = document.getElementById('edit_vendor_offcanvas');
                    const offcanvasedit = bootstrap.Offcanvas.getInstance(offcanvasElEdit);
                    if (offcanvasedit) {
                        offcanvasedit.hide();
                    }
                }
            });
        });

    });

    function deleteAssetRegister(id) {
        if (confirm('Are you sure you want to delete this Asset Entry?')) {
            $.ajax({
                url: 'items/delete/' + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE" // Important to spoof DELETE method
                },
                success: function (response) {
                    toastr["error"](response.message);
                    $('#asset-item-table').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    toastr["error"]("Failed to delete vendor.");
                }
            });
        }
    }

    function openOffcanvas(id = null) {
        const $form = $('#register-edit-form');
        $('#edit-item-line-container').empty();
        calculateGrandTotal();
        $('#company_name, #vendor_id').val('').trigger('change');
        $form[0].reset();
        $('#target_id').val('');
        $('#line_id').val('');
        $('#mapping_id').val('');

        $('#vendor-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit Asset Item</h5><span class="text-white slogan">Edit Asset Item</span>`);

        const offcanvasElement = $('#edit_vendor_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {

            $('#vendor-offcanvas-title').html(`
                <h5 class="offcanvas-title text-white">Edit Asset Register</h5>
                <span class="text-white slogan">Edit Asset Vendor</span>
            `);
            $('#current-attachment').remove();
            url = "{{ route('assets.item.edit', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response.register;
                    let item = response.item;
                    let map = response.mapping;
                    let index = 1;
                    // Main form values
                    $('#asset_number_edit').val(data.asset_number);
                    $('#company_name_edit').val(data.company_name).trigger('change');
                    $('#purchase_date_edit').flatpickr().setDate(data.purchase_date);
                    $('#invoice_number_edit').val(data.invoice_number);
                    $('#vendor_id_edit').val(data.vendor_id).trigger('change');
                    $('#remarks_edit').val(data.remarks);
                    $('#grand_total_edit').val(data.total_amount);
                    $('#total_amount_edit').html(parseFloat(data.total_amount).toFixed(2));

                    // Clear existing line items
                    $('#edit-item-line-container').empty();

                    $('#edit_target_id').val(data.id);
                    $('#line_id').val(item.id);
                    $('#mapping_id').val(map.id);
                    // Loop through line items and append rows

                        let row = `
                            <tr>
                                <td>
                                    <select name="asset_classification_id[${index}]" class="form-control select2" required>
                                        @foreach($assetClassifications as $key => $label)
                                            <option value="{{ $label['id'] }}" ${item.asset_classification_id == {{ $label['id'] }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_category_id[${index}]" class="form-control select2" required>
                                        @foreach($assetCategories as $key => $label)
                                            <option value="{{ $label['id'] }}" ${item.asset_category_id == {{ $label['id'] }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_type_id[${index}]" class="form-control select2" required>
                                        @foreach($assetTypes as $key => $label)
                                            <option value="{{ $label['id'] }}" ${item.asset_type_id == {{ $label['id'] }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_item_id[${index}]" class="form-control select2" required>
                                        @foreach($assetItems as $key => $label)
                                            <option value="{{ $label['id'] }}" ${item.asset_item_id == {{ $label['id'] }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td width="10%"><input class="form-control" type="text" name="asset_brand[${index}]" value="${item.asset_brand}" placeholder="Brand name" required></td>
                                <td><input type="text" name="asset_model[${index}]" class="form-control" value="${item.item_model ?? ''}" required></td>
                                <td><input type="text" name="item_key_id[${index}]" class="form-control" value="${item.item_key_id ?? ''}" ></td>

                                <td width="10%">
                                    <textarea class="form-control" name="asset_unit[${index}]" placeholder="Specification" row="5">${item.asset_description}</textarea>
                                </td>
                                <td><input type="number" name="asset_quantity[${index}]" class="form-control quantity" onchange="calculateAmount('${index}')" id="qty_${index}" value="${item.asset_quantity}" readonly></td>
                                <td><input type="number" name="asset_price[${index}]" class="form-control price" onchange="calculateAmount('${index}')" id="price_${index}" value="${item.asset_price}">
                                    <input type="hidden" name="asset_pre_price[${index}]" class="form-control price" id="pre_price_${index}" value="${item.asset_price}">
                                    <input type="hidden" name="asset_total[${index}]" class="form-control total" onchange="calculateAmount('${index}')" id="amount_${index}" readonly value="${item.asset_total}" >
                                    <input type="hidden" name="asset_pre_total[${index}]" class="form-control total" id="pre_amount_${index}" readonly value="${item.asset_total}" >
                                </td>

                                <td><input type="text" name="serial_number[${index}]" class="form-control" value="${item.serial_number ?? ''}"></td>
                                <td><input type="text" name="warranty[${index}]" class="form-control" value="${item.warranty ?? ''}"></td>


                            </tr>`;
                        $('#edit-item-line-container').append(row);

// <td><button type="button" class="btn btn-xs btn-icon btn-danger" onclick="$(this).closest('tr').remove(); calculateGrandTotal();"><i class="ti ti-minus"></i></button></td>
                    // Re-initialize select2 for dynamically added fields
                    $('#edit-item-line-container').find('select.select2').select2({
                        dropdownParent: $('#edit_vendor_offcanvas') // replace this ID with your actual offcanvas container ID
                    });
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
