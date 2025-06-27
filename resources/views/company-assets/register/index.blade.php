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
                                    <table class="table table-bordered table-striped" id="vendor-table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Reg_No</th>
                                                <th>Date</th>
                                                <th>Category</th>
                                                <th>Contact Person</th>
                                                <th>Contact Number</th>
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

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="vendor_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="vendor-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Vendor</h5>
                <span class="text-white slogan">Create New Asset Vendor</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-vendor-form />
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="category-modal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="exampleModalLabel1">Create Vendor Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('assets.store-vendor-category') }}" method="post" id="vendor-category-form">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Vendor Category <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="vendor Category" required />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="vendor-category-form" class="btn btn-primary waves-effect"> <i class="fa fa-save me-2"></i> Save</button>
                <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop


@push('js')
<script>
    $(function () {
        const vendorTable = $('#vendor-table');

        if(vendorTable.length) {            
            vendorTable.DataTable({
            processing: true,
            serverSide: false, // set to true if using server-side pagination
            ajax: '{{ route("assets.vendors.index") }}', // your route
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'vendor_code', name: 'vendor_code' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'vendor_category', name: 'vendor_category' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'contact_number', name: 'contact_number' },
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
        $('#vendor-form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('assets.vendors.store') }}",
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
        const $form = $('#vendor-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#vendor-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create Vendor</h5><span class="text-white slogan">Create New Asset Vendor</span>`);

        const offcanvasElement = $('#vendor_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            const url = "{{ route('assets.vendors.edit', ':assetVendors') }}".replace(':assetVendors', id);
            $('#target_id').val(id);
            $('#vendor-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit Vendor</h5><span class="text-white slogan">Edit Asset Vendor</span>`);
            $('#current-attachment').remove();
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'GET',
                success: function (response) {
                    vendor = response.data;
                    $('#target_id').val(vendor.id);
                    $('#vendor_code').val(vendor.vendor_code);
                    $('#vendor_category').val(vendor.category.id).trigger('change');
                    $('#vendor_name').val(vendor.vendor_name);
                    $('#contact_person').val(vendor.contact_person);
                    $('#contact_number').val(vendor.contact_number);
                    $('#vendor_address').val(vendor.vendor_address);
                    $('#email').val(vendor.email);
                    $('#website').val(vendor.website);
                    $('#mobile_number').val(vendor.mobile_number);  
                },
                error: function () {
                    alert('Failed to load MOM data.');
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
