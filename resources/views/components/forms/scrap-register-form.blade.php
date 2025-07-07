<div>
    @push('css')

@endpush
<div>
    <form action="{{ route('assets.scrap-register.store') }}" method="POST" id="scrap-register-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="target_id">

        <div class="row">

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="scrap_no">Scrap No. <span class="text-danger">*</span></label>
                    <input type="text" name="scrap_no" id="scrap_no" class="form-control" placeholder="Scrap Number" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="scrap_date">Scrap Date <span class="text-danger">*</span></label>
                    <input type="text" name="scrap_date" id="scrap_date" class="form-control" placeholder="Date" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="total_weight">Total weight <span class="text-danger">*</span></label>
                    <input type="text" name="total_weight" id="total_weight" class="form-control" placeholder="Total Weight" required />
                </div>
            </div>

            <div class="col-sm-7 mb-3">
                <div class="form-group">
                    <label for="scrap_vendor_id">Scrap Vendor <span class="text-danger">*</span></label>
                    <select name="scrap_vendor_id" id="scrap_vendor_id" class="form-control select2">
                        <option value="">Select Vendor</option>
                        @foreach ($vendors as $key => $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-5 mb-3">
                <div class="form-group">
                    <label for="total_amount">Total amount <span class="text-danger">*</span></label>
                    <input type="text" name="total_amount" id="total_amount" class="form-control" placeholder="Total Amount" required />
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <table class="table table-bordered table-striped table-xs fs-6" id="item-line-table" style="font-size: 11px !important;">
                    <thead>
                        <tr>
                            <th>Items</th>
                            <th>Model</th>
                            <th>Serial</th>
                            <th>Asset Id</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="item-line-container">   
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-right"><h5 class="mb-0">GrandTotal</h5></th>
                            <th class="text-right fw-semibold">
                                <h5 class="mb-0" id="total_amount_display">0.00</h5>
                                <input type="hidden" name="grand_total" id="grand_total">
                            </th>
                            <th></th>
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
                <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i>&nbsp;&nbsp; Save </button>
            </div>

        </div>
    </form>
</div>

@push('js')

<script>

    $('#scrap_date').flatpickr({
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'd-m-Y'
    });

    function addItemLine() {
        var itemLineLength = $('#item-line-container tr').length + 1;

        var assetItems = {!! json_encode($assetItems) !!};
        
        let html = `
            <tr>
                <td>
                    <select name="scrap_item_id[${itemLineLength}]" id="scrap_item_${itemLineLength}" onchange="getModelNo(this.value, '${itemLineLength}')" class="form-control select2">
                        <option value="">Select Item</option>
                        ${assetItems.map(item => `<option value="${item.id}">${item.name} [${item.item_code} - ${item.brand}]</option>`).join('')}
                    </select>
                </td>
                <td>
                    <select name="model[${itemLineLength}]" id="model_${itemLineLength}" onchange="getSerialNo(this.value, '${itemLineLength}')" class="form-control select2">
                        <option value="">Select Model</option>
                    </select>
                </td>
                <td>
                    <select name="serial_no[${itemLineLength}]" id="serial_no_${itemLineLength}" onchange="getAssetId(this.value, '${itemLineLength}')" class="form-control select2">
                        <option value="">Select Serial</option>
                    </select>
                </td>
                <td>
                    <select name="asset_id[${itemLineLength}]" id="asset_id_${itemLineLength}" class="form-control select2">
                        <option value="">Select Serial</option>
                    </select>
                </td>
                <td><input class="form-control" type="text" name="unit[${itemLineLength}]" id="unit_${itemLineLength}" value=""></td>
                <td><input class="form-control text-center" type="text" id="quantity_${itemLineLength}" name="quantity[${itemLineLength}]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                <td><input class="form-control text-right" type="text" id="rate_${itemLineLength}" name="rate[${itemLineLength}]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                <td><input class="form-control text-right" type="text" id="amount_${itemLineLength}" name="amount[${itemLineLength}]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                <td><input class="form-control" type="text" name="remarks[${itemLineLength}]" id="remarks_${itemLineLength}" value=""></td>
                <td>
                    <button type="button" class="btn btn-icon btn-xs btn-danger waves-effect" onclick="$(this).closest('tr').remove();">
                        <i class="ti ti-minus"></i>
                    </button>
                </td>
            </tr>
        `;

        // Convert to jQuery element
        const $newRow = $(html);

        // Append to table
        $('#item-line-container').append($newRow);
        // Initialize only newly added select2 elements
        $newRow.find('select.select2').select2({
            dropdownParent: $('#scrap_offcanvas') // replace this ID with your actual offcanvas container ID
        });
    }

    /* Get Serial No */
    function getSerialNo(model, itemLineLength) {
        var item_model = model;
        var itemId = $('#scrap_item_' + itemLineLength).val();
        $.ajax({
            url: "{{ route('assets.scrap-register.get-item-serials') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                item_id: itemId,
                item_model : item_model
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var html = '<option value="">Select Serial No</option>';
                    response.data.map(function(serial_no) {
                        html += '<option value="' + serial_no + '">' + serial_no + '</option>';
                    });    
                    $('#serial_no_' + itemLineLength).html(html);
                    $('#serial_no_' + itemLineLength).select2({
                        dropdownParent: $('#scrap_offcanvas') // replace this ID with your actual offcanvas container ID
                    });
                }
            }
        });
    }

    function getModelNo(itemId, itemLineLength) {
        var item_id = itemId;
        $.ajax({
            url: "{{ route('assets.scrap-register.get-model-no') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                item_id: itemId,
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var html = '<option value="">Select Model</option>';
                    response.data.map(function(model) {
                        html += '<option value="' + model + '">' + model + '</option>';
                    });    
                    $('#model_' + itemLineLength).html(html);
                    $('#model_' + itemLineLength).select2({
                        dropdownParent: $('#scrap_offcanvas') // replace this ID with your actual offcanvas container ID
                    });
                }
            }
        });
    }


    function calculateAmount(itemLineLength) {
        var qty = $('#quantity_' + itemLineLength).val();
        var price = $('#rate_' + itemLineLength).val();
        var amount = qty * price;
        $('#amount_' + itemLineLength).val(amount);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var total = 0;
        $('#item-line-container tr').each(function() {
            var amount = $(this).find('input[name^="amount["]').val();
            if (!isNaN(parseFloat(amount))) {
                total += parseFloat(amount);
            }
        });
        $('#total_amount_display').text(total.toFixed(2));
        $('#grand_total').val(total.toFixed(2));
        $('#total_amount').val(total.toFixed(2));
    }

    function getAssetId(serialNo, itemLineLength) {
        var item_id = $('#scrap_item_' + itemLineLength).val();
        var item_model = $('#model_' + itemLineLength).val();
        $.ajax({
            url: "{{ route('assets.scrap-register.get-asset-id') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                item_id: item_id,
                item_model: item_model,
                serial_no: serialNo
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var html = '<option value="">Select Asset Ids </option>';
                    response.data.map(function(assetId) {
                        html += '<option value="' + assetId.id + '">' + assetId.item_number + '</option>';
                    });    
                    $('#asset_id_' + itemLineLength).html(html);
                    $('#asset_id_' + itemLineLength).select2({
                        dropdownParent: $('#scrap_offcanvas') // replace this ID with your actual offcanvas container ID
                    });
                }
            }
        });
    }
    
</script>
    
@endpush