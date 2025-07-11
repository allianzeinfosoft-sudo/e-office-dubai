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

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> Allocate Item</span>
                                </span>
                            </a>
                        </div>



                        <div class="card mb-4">
                            <h5 class="card-header">Search Item</h5>
                            <div class="card-body">
                                <form id="allocated-item-search" action="{{ route('assets.alloted-item-search'); }}" method="POST" onsubmit="return false">
                                    @csrf
                                    <div class="row">

                                        <div class="mb-3 col-md-3 form-password-toggle">
                                            <label class="form-label" for="newPassword">Asset User</label>
                                            <div class="input-group input-group-merge">
                                              <div class="mb-3 col-12">
                                                    <select class="form-control select2" name="user" id="user">
                                                        <option></option>
                                                        @foreach (config('optionsData.asset_allocation_users') as $key => $value)
                                                            <option value="{{ $key }}"> {{ $value }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    <div class="mb-3 col-md-3 form-password-toggle" id="user_details"> </div>

                                    <div>
                                        <button type="submit" class="btn btn-primary me-2">Search</button>
                                        <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                    </div>
                                    </div>
                                </form>
                            </div>
                        </div>



                <!-- Accordion with Icon -->
                <div class="card mb-4 pb-4" id="asset-item-list-accodion">
                  <div class="accordion mt-3 " id="accordionWithIcon">
                    <!-- accordian -->
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
                <h5 class="offcanvas-title text-white">Create Asset Allocation</h5>
                <span class="text-white slogan">Add new Asset Allocation</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-asset-allocation-form />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function () {


        $('#allocated-item-search').submit(function (e) {
            let url = $(this).attr('action');
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

             $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        toastr.success(response.message);
                        $('#accordionWithIcon').html(response.html);
                    }
                });
        });

       // Handle pagination links via AJAX
        $(document).on('click', '#allocation-pagination .pagination a', function (e) {
            e.preventDefault();

            let pageUrl = $(this).attr('href');
            let page = new URL(pageUrl).searchParams.get('page');

            // Get the form
            let form = $('#allocated-item-search')[0];
            let formData = new FormData(form);
            formData.append('page', page);

            $.ajax({
                url: $(form).attr('action'), // POST to same route
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    toastr.success(response.message);
                    $('#accordionWithIcon').html(response.html); // Replace accordion content
                },
                error: function () {
                    toastr.error('Failed to load page');
                }
            });
        });




        $('#allocation-form').submit(function (e) {
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

                setTimeout(() => {
                    window.location.reload();
                }, 300);
                }
            });
        });




    });

    function deleteAssetAllocation(id) {
        if (confirm('Are you sure you want to delete this Asset Entry?')) {
            $.ajax({
                url: "{{ route('assets.allocation.destroy', ':id') }}".replace(':id', id),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE" // Important to spoof DELETE method
                },
                success: function (response) {
                    toastr["error"](response.message);
                    $('#asset-allocation-table').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    toastr["error"]("Failed to delete vendor.");
                }
            });
        }
    }



     function openOffcanvas(id = null) {
        const $form = $('#allocation-form');
        $('#item-line-container').empty();

        $('#company_name, #vendor_id').val('').trigger('change');
        $form[0].reset();
        $('#target_id').val('');
        $('#vendor-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create Asset Allocation</h5><span class="text-white slogan">Create New Asset Allocation</span>`);

        const offcanvasElement = $('#vendor_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            $('#target_id').val(id);
            $('#vendor-offcanvas-title').html(`
                <h5 class="offcanvas-title text-white">Edit Asset Allocation</h5>
                <span class="text-white slogan">Edit Asset Vendor</span>
            `);
            $('#current-attachment').remove();
            url = "{{ route('assets.allocation.edit', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    let data = response.data;

                    // Main form values
                    $('#asset_number').val(data.asset_number);
                    $('#company_name').val(data.company_name).trigger('change');
                    $('#purchase_date').flatpickr().setDate(data.purchase_date);
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
                                    <select name="asset_item_id[${index}]" class="form-control select2">
                                        @foreach($assetItems as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_item_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="asset_model[${index}]" class="form-control" value="${item.item_model ?? ''}"></td>
                                <td><input type="text" name="serial_number[${index}]" class="form-control" value="${item.serial_number ?? ''}"></td>
                                <td><input type="text" name="warranty[${index}]" class="form-control" value="${item.warranty ?? ''}"></td>
                                <td>
                                    <select name="asset_classification_id[${index}]" class="form-control select2">
                                        @foreach($assetClassifications as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_classification_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_category_id[${index}]" class="form-control select2">
                                        @foreach($assetCategories as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_category_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="asset_type_id[${index}]" class="form-control select2">
                                        @foreach($assetTypes as $key => $label)
                                            <option value="{{ $key }}" ${item.asset_type_id == {{ $key }} ? 'selected' : ''}>{{ $label['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="asset_unit[${index}]" class="form-control"  readonly value="${item.asset_description}"></td>
                                <td><input type="number" name="asset_quantity[${index}]" class="form-control quantity" onchange="calculateAmount('${index}')" id="qty_${index}" value="${item.asset_quantity}"></td>
                                <td><input type="number" name="asset_price[${index}]" class="form-control price" onchange="calculateAmount('${index}')" id="price_${index}" value="${item.asset_price}"></td>
                                <td><input type="text" name="asset_total[${index}]" class="form-control total" onchange="calculateAmount('${index}')" id="amount_${index}" readonly value="${item.asset_total}"></td>
                                <td><button type="button" class="btn btn-xs btn-icon btn-danger" onclick="$(this).closest('tr').remove(); calculateGrandTotal();"><i class="ti ti-minus"></i></button></td>
                            </tr>
                        `;
                        $('#item-line-container').append(row);
                    });

                    // Re-initialize select2 for dynamically added fields
                    $('.select2').select2();
                },
                error: function() {
                    alert('Failed to load Asset Allocation data.');
                }
            });
        }
    }



    // openOffcanvas
    function viewCategoryModal(id) {
        $('#category-modal').modal('show');
        $('#vendor-category-form')[0].reset();
    }


    $(document).ready(function () {

        $('#user').select2({
            placeholder: 'Select User',
            width: '100%'
        });
        $('#user').on('change', function () {

            let selectedValue = $(this).val();

            if (selectedValue === 'employee') {
                $.ajax({
                    url: '/get-all-employees',
                    type: 'GET',
                    success: function (response) {
                        let selectHTML = `
                            <label class="form-label" for="employee">Employee</label>
                            <div class="input-group input-group-merge">
                                <div class="mb-3 col-12">
                                    <select class="form-control select2" name="employee" id="employee" style="width: 100%;">
                                        <option></option>
                                        ${Object.entries(response).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                                    </select>
                                </div>
                            </div>`;

                        $('#user_details').html(selectHTML);
                        $('#employee').select2({
                            placeholder: 'Select Employee',
                            width: '100%'
                        });
                    },
                    error: function () {
                        alert('Failed to load employee list.');
                    }
                });
            }
            else if(selectedValue === 'location')
            {
                 $.ajax({
                    url: '/get-all-locations',
                    type: 'GET',
                    success: function (response) {
                        let selectHTML = `
                            <label class="form-label" for="location">Location</label>
                            <div class="input-group input-group-merge">
                                <div class="mb-3 col-12">
                                    <select class="form-control select2" name="location" id="location" style="width: 100%;">
                                        <option></option>
                                        ${Object.entries(response).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                                    </select>
                                </div>
                            </div>`;

                        $('#user_details').html(selectHTML);
                        $('#location').select2({
                            placeholder: 'Select Location',
                            width: '100%'
                        });
                    },
                    error: function () {
                        alert('Failed to load location list.');
                    }
                });
            }
            else {
                $('#user_details').html('');
            }
        });
    });

// return item to store

$(document).on('submit', '.return-form', function (e) {

    e.preventDefault();
    const form = $(this);
    const allocationId = form.data('id');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': form.find('input[name="_token"]').val()
        },
        success: function (res) {
            toastr.success('Assets returned successfully');
            const accordionCard = $('#accordion-card-' + allocationId);
            accordionCard.fadeOut(300, function () {
                $(this).remove();
            });
        },
        error: function () {
            toastr.error('Failed to return assets');
        }
    });
});

</script>
@endpush
