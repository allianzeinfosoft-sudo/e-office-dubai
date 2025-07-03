@extends('layouts.app')

@section('css')
<style>
    .w-90 { width: 90% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Scrap /</span> Register</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn btn-primary" href="javascript:void(0);" onclick="openScrapOffcanvas()">
                                <i class="ti ti-plus"></i> Add Scrap
                            </a>
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
<div class="offcanvas offcanvas-end w-90" data-bs-backdrop="static" tabindex="-1" id="scrap_offcanvas">
    <div class="offcanvas-header bg-primary text-white">
        <h5 class="offcanvas-title">Scrap Entry</h5>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas">
            <i class="fa fa-close"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        @include('scrap._form')
    </div>
</div>
@stop

@push('js')
<script>
    $(function () {
        const scrapTable = $('#scrap-register-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{{ route("scrap.index") }}',
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
                            <button onclick="editScrap(${data})" class="btn btn-sm btn-primary"><i class="ti ti-edit"></i></button>
                            <button onclick="deleteScrap(${data})" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></button>
                        `;
                    }
                }
            ]
        });

        $('#scrap-form').submit(function (e) {
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
                    $('#scrap-form')[0].reset();
                    $('#item-line-container').empty();
                }
            });
        });
    });

    function openScrapOffcanvas() {
        $('#scrap_id').val('');
        $('#scrap-form')[0].reset();
        $('#item-line-container').empty();
        new bootstrap.Offcanvas('#scrap_offcanvas').show();
    }

    function editScrap(id) {
        $.get("{{ url('scrap') }}/" + id + "/edit", function (res) {
            Object.keys(res.data).forEach(key => {
                $(`[name="${key}"]`).val(res.data[key]);
            });

            $('#item-line-container').empty();
            res.data.items.forEach((item, index) => {
                appendItemLine(index, item);
            });

            $('#scrap_id').val(id);
            new bootstrap.Offcanvas('#scrap_offcanvas').show();
        });
    }

    function deleteScrap(id) {
        if (confirm('Delete this scrap entry?')) {
            $.ajax({
                url: `{{ url('scrap') }}/${id}`,
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
