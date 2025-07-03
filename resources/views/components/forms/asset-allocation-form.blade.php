@push('css')

@endpush
<div>
    <form action="{{ route('assets.allocation.store') }}" method="POST" id="allocation-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="target_id">

        <div class="row">

            {{-- <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="asset_number">Reg. No. <span class="text-danger">*</span></label>
                    <input type="text" name="asset_number" id="asset_number" class="form-control" placeholder="Register Number" required />
                </div>
            </div> --}}

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="asset_user">Asset User<span class="text-danger">*</span></label>
                    <select class="form-control select2" name="asset_user" id="asset_user">
                        <option></option>
                        @foreach(config('optionsData.asset_users') as $key => $label)
                            <option value="{{ $key }}"> {{ $label }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group" id="asset_user_details">

                </div>
            </div>

            {{-- <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="upload_invoice">Upload Invoice</label>
                    <input type="file" name="upload_invoice" id="upload_invoice" class="form-control">
                </div>
            </div> --}}

            <div class="col-sm-12 mb-3">
                <table class="table table-bordered table-striped table-xs fs-6" id="item-line-table" style="font-size: 11px !important;">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Stock</th>
                            <th>Project</th>
                            <th>Check Asset ID</th>
                            <th>Qty</th>
                            <th>Specification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="item-line-container">

                    </tbody>
                    <tfoot>
                        <tr>
                             <th colspan="6" class="text-right"></th>
                            <th><button type="button" class="btn btn-xs btn-icon btn-success waves-effect" onclick="addItemLine()"><i class="ti ti-plus"></i></button></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" cols="30" rows="5" class="form-control"></textarea>
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i>&nbsp;&nbsp; Save
                </button>
            </div>

        </div>
    </form>
</div>

@push('js')
<script>
    $('#purchase_date').flatpickr({
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'd-m-Y'
    });

    function addItemLine() {
        var itemLineLength = $('#item-line-container tr').length + 1;

        var assetItems = {!! json_encode($assetItems) !!};
        var assetClassifications = {!! json_encode($assetClassifications) !!};
        var assetCategories = {!! json_encode($assetCategories) !!};
        var assetTypes = {!! json_encode($assetTypes) !!};

        let html = `<tr>
                        <td>
                            <select name="asset_item_id[${itemLineLength}]" id="item_${itemLineLength}" class="form-control select2">
                                <option value="">Select Item</option>
                                ${assetItems.map(item => `<option value="${item.id}">${item.name} [${item.item_code} - ${item.brand}]</option>`).join('')}
                            </select>
                        </td>
                        <td><input class="form-control" type="text" name="warranty[${itemLineLength}]"></td>
                        <td>
                            <select name="asset_type_id[${itemLineLength}]" id="type_${itemLineLength}" class="form-control select2">
                                <option value="">Select Type</option>
                                ${assetTypes.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                            </select>
                        </td>
                        <td><input class="form-control text-center" type="text" id="qty_${itemLineLength}" name="asset_quantity[${itemLineLength}]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                        <td><input class="form-control text-center" type="text" id="qty_${itemLineLength}" name="asset_quantity[${itemLineLength}]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                        <td><input class="form-control text-center" type="text" id="qty_${itemLineLength}" name="asset_quantity[${itemLineLength}]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                        <td>
                            <button type="button" class="btn btn-icon btn-xs btn-danger waves-effect" onclick="$(this).closest('tr').remove();">
                                <i class="ti ti-minus"></i>
                            </button>
                        </td>
                    </tr>`;

        // Convert to jQuery element
        const $newRow = $(html);

        // Append to table
        $('#item-line-container').append($newRow);

        // Initialize only newly added select2 elements
        $newRow.find('select.select2').select2({
            dropdownParent: $('#vendor_offcanvas') // replace this ID with your actual offcanvas container ID
        });
    }




 $(document).ready(function () {


        $('#asset_user').on('change', function () {

            let selectedValue = $(this).val();

            if (selectedValue === 'employee') {
                $.ajax({
                    url: '/get-all-employees',
                    type: 'GET',
                    success: function (response) {
                        let selectHTML = `
                            <label for="asset_employees">Employee</label>
                            <select class="form-control select2" name="asset_employees" id="asset_employees">
                                <option></option>
                                ${Object.entries(response).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                            </select>`;

                        $('#asset_user_details').html(selectHTML);
                        $('#asset_employees').select2({
                            placeholder: 'Select Employee',

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
                            <label for="asset_location">Location</label>
                            <select class="form-control select2" name="asset_location" id="asset_location">
                                <option></option>
                                ${Object.entries(response).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                            </select>`;

                        $('#asset_user_details').html(selectHTML);
                        $('#asset_location').select2({
                            placeholder: 'Select Location',

                        });
                    },
                    error: function () {
                        alert('Failed to load location list.');
                    }
                });
            }
            else {
                $('#asset_user_details').html('');
            }
        });
    });
</script>

@endpush
