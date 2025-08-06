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
     #scrap-register-form th {
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> ScrapRegister</h4>

                    <div class="row">
                        <div class="col-md-6 pb-3">
                              <a class="btn btn-danger" href="{{ route('assets.dashboard') }}">
                                <i class="ti ti-home me-0 me-sm-1 ti-xs"></i>
                            </a>
                        </div>
                        <div class="col-md-6 text-end pb-3">
                            <a class="btn btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <i class="ti ti-plus"></i> Add Scrap
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="table table-bordered" id="scrap-register-table" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Batch No</th>
                                            <th>Scrap Date</th>
                                            <th>Vendor</th>
                                            <th>Total Weight</th>
                                            <th>Total Amount</th>
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

<!-- Scrap Offcanvas -->

<div class="offcanvas offcanvas-end w-75" data-bs-backdrop="static" tabindex="-1" id="scrap_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i>
            <span id="scrap_offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Scrap Register</h5>
                <span class="text-white slogan">Add new Scrap Register</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-scrap-register-form />
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    $(function () {
        const scrapTable = $('#scrap-register-table').DataTable({
            processing: false,
            serverSide: false,
            ajax: '{{ route("assets.scrap-register.index") }}',
            dataSrc: 'data',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'scrap_no' },
                { data: 'scrap_date' },
                { data: 'vendor_name' },
                { data: 'total_weight' },
                { data: 'total_amount' },
                { data: 'remarks' },
                {
                    data: 'id',
                    render: function (data) {
                        return `
                            <button class="btn btn-sm btn-primary" onclick="openOffcanvas(${data})"><i class="ti ti-edit"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteScrap(${data})"><i class="ti ti-trash"></i></button>`;
                    }
                }
            ]
        });

        $('#scrap-register-form').submit(function (e) {
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
                    $('#scrap_offcanvas').offcanvas('hide');
                    scrapTable.ajax.reload();
                    $('#scrap-register-form')[0].reset();
                    $('#item-line-container').empty();
                }
            });
        });
    });

    function openOffcanvas(id = null) {
            if (id) {
                const assetItems = @json($assetItems);
                $('#target_id').val(id);
                $('#scrap_offcanvas-title').html(`
                    <h5 class="offcanvas-title text-white">Edit Scrap Register</h5>
                    <span class="text-white slogan">Edit Scrap Register</span>
                `);
                const url = "{{ route('assets.scrap-register.edit', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        let data = response.data;
                        $('#scrap_no').val(data.scrap_no);
                        $('#scrap_date')[0]._flatpickr.setDate(data.scrap_date); // ensure flatpickr is already initialized
                        $('#scrap_vendor_id').val(data.scrap_vendor_id).trigger('change');
                        $('#total_weight').val(data.total_weight);
                        $('#total_amount').val(data.total_amount);
                        $('#remarks').val(data.remarks);
                        $('#total_amount_display').html(parseFloat(data.total_amount).toFixed(2));
                        $('#item-line-container').empty();

                        data.items.forEach(function (item, index) {
                            let row = `
                                <tr>
                                    <td>
                                        <select name="scrap_item_id[${index}]" id="scrap_item_${index}" onchange="getModelNo(this.value, '${index}')" class="form-control select2">
                                            <option value="">Select Item</option>
                                            @foreach($assetItems as $label)
                                                <option value="{{ $label['id'] }}">{{ $label['name'] }} [ {{ $label['item_code'] }} - {{ $label['brand'] }}]</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="model[${index}]" id="model_${index}" onchange="getSerialNo(this.value, '${index}')" class="form-control select2">
                                            <option value="">Select Model</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="serial_no[${index}]" id="serial_no_${index}" onchange="getAssetId(this.value, '${index}')" class="form-control select2">
                                            <option value="">Select Serial</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="asset_id[${index}]" id="asset_id_${index}" class="form-control select2">
                                            <option value="">Select Asset ID</option>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="text" name="unit[${index}]" value="${item.unit ?? ''}"></td>
                                    <td><input class="form-control text-center" type="text" name="quantity[${index}]" onchange="calculateAmount('${index}')" value="${item.quantity ?? 0.00}"></td>
                                    <td><input class="form-control text-right" type="text" name="rate[${index}]" onchange="calculateAmount('${index}')" value="${item.rate ?? 0.00}"></td>
                                    <td><input class="form-control text-right" type="text" name="amount[${index}]" onchange="calculateAmount('${index}')" value="${item.amount ?? 0.00}"></td>
                                    <td><input class="form-control" type="text" name="remarks[${index}]" value="${item.remarks ?? ''}"></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-xs btn-danger" onclick="$(this).closest('tr').remove();">
                                            <i class="ti ti-minus"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $('#item-line-container').append(row);

                            // Set values after insertion
                            $(`#scrap_item_${index}`).val(item.scrap_item_id).trigger('change');
                            getModelNo(item.scrap_item_id, index);
                            setTimeout(() => {
                                $(`#model_${index}`).val(item.model).trigger('change');
                                setTimeout(() => {
                                    $(`#serial_no_${index}`).val(item.serial_no).trigger('change');
                                    setTimeout(() => {
                                        $(`#asset_id_${index}`).val(item.asset_mapping_id).trigger('change');
                                    }, 100);
                                }, 300);
                            }, 500);
                        });

                        $('#item-line-container select.select2').select2({
                            dropdownParent: $('#scrap_offcanvas')
                        });
                    },
                    error: function () {
                        alert('Failed to load Scrap Register data.');
                    }
                });
            } else {
                // Create Mode
                $('#scrap-register-form')[0].reset();
                $('#scrap-register-form').find('select').val('').trigger('change');
                $('#item-line-container').empty();
                $('#scrap_offcanvas-title').html(`
                    <h5 class="offcanvas-title text-white">Create Scrap Register</h5>
                    <span class="text-white slogan">Create Scrap Register</span>
                `);
            }
            new bootstrap.Offcanvas('#scrap_offcanvas').show();
        }

    function deleteScrap(id) {
        if (confirm('Delete this scrap entry?')) {
            $.ajax({
                url: `{{ route('assets.scrap-register.destroy', ':id') }}`.replace(':id', id),
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    toastr.error(res.message);
                    $('#scrap-register-table').DataTable().ajax.reload();
                }
            });
        }
    }

    function appendItemLine(index, data = {}) {
        const row = `
            <tr>
                <td><select name="scrap_item_id[${index}]" class="form-control">${@json($items).map(item => `<option value="${item.id}" ${item.id == data.scrap_item_id ? 'selected' : ''}>${item.name}</option>`).join('')}</select></td>
                <td><input type="text" name="serial_no[${index}]" class="form-control" value="${data.serial_no ?? ''}"></td>
                <td><input type="text" name="unit[${index}]" class="form-control" value="${data.unit ?? ''}"></td>
                <td><input type="number" name="quantity[${index}]" class="form-control" value="${data.quantity ?? 0}" onchange="updateAmount(${index})"></td>
                <td><input type="number" name="rate[${index}]" class="form-control" value="${data.rate ?? 0}" onchange="updateAmount(${index})"></td>
                <td><input type="text" name="amount[${index}]" class="form-control" readonly value="${data.amount ?? 0}"></td>
                <td><textarea name="remarks[${index}]" class="form-control">${data.remarks ?? ''}</textarea></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove();">X</button></td>
            </tr>
        `;
        $('#item-line-container').append(row);
    }

    function updateAmount(index) {
        const qty = $(`[name="quantity[${index}]"]`).val();
        const rate = $(`[name="rate[${index}]"]`).val();
        const amount = parseFloat(qty * rate).toFixed(2);
        $(`[name="amount[${index}]"]`).val(amount);
    }

</script>
@endpush
