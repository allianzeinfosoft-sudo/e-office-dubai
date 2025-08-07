@push('css')

@endpush
<div>
    <form action="{{ route('assets.register.update_item') }}" method="POST" id="register-edit-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="edit_target_id">
        <input type="hidden" name="line_id" id="line_id">
        <input type="hidden" name="mapping_id" id="mapping_id">


        <div class="row">

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="asset_number">Batch No. <span class="text-danger">*</span></label>
                    <input type="text" name="asset_number" id="asset_number_edit" class="form-control" placeholder="Register Number" value="{{ $batch_no }}" readonly />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="company_name">Company <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="company_name" id="company_name_edit">
                        <option value="">Select Company</option>
                        @foreach(config('optionsData.companies') as $key => $label)
                            <option value="{{ $key }}"> {{ $label }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="purchase_date">Purchase Date <span class="text-danger">*</span></label>
                    <input type="text" name="purchase_date" id="purchase_date_edit" class="form-control" placeholder="Date" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="invoice_number">Invoice No <span class="text-danger">*</span></label>
                    <input type="text" name="invoice_number" id="invoice_number_edit" class="form-control" placeholder="Invoice No" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="vendor_id">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id_edit" class="form-control select2">
                        <option value="">Select Vendor</option>
                        @foreach ($vendors as $key => $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="col-sm-12 mb-3">
                <table class="table table-bordered table-striped table-xs fs-6" id="item-line-table" style="font-size: 11px !important;">
                    <thead>
                        <tr style="text-align: center">
                            <th width="10%">Asset Classification</th>
                            <th width="10%">Category</th>
                            <th width="10%">Type</th>
                            <th width="10%">Item</th>
                            <th width="10%">Brand</th>
                            <th width="10%">Model</th>
                            <th width="10%">Key/ID</th>
                            <th width="10%">Specifications</th>
                            <th width="6%">Qty</th>
                            <th width="6%">Price</th>
                            {{-- <th width="6%">Amount</th> --}}
                            <th width="6%">Serial No</th>
                            <th width="5%">Warranty</th>

                        </tr>
                    </thead>
                    <tbody id="edit-item-line-container">

                    </tbody>
                    <tfoot>
                        {{-- <tr>
                            <th colspan="12" class="text-right"><h5 class="mb-0">GrandTotal</h5></th>
                            <th class="text-right fw-semibold">
                                <h5 class="mb-0" id="total_amount_edit">0.00</h5>
                                <input type="hidden" name="grand_total" id="grand_total_edit">
                            </th>

                        </tr> --}}
                    </tfoot>
                </table>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks_edit" cols="30" rows="5" class="form-control"></textarea>
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
        var itemLineLength = $('#edit-item-line-container tr').length + 1;

        var assetItems = {!! json_encode($assetItems) !!};
        var assetClassifications = {!! json_encode($assetClassifications) !!};
        var assetCategories = {!! json_encode($assetCategories) !!};
        var assetTypes = {!! json_encode($assetTypes) !!};

        let html = `<tr>
                    <td width="10%">
                        <select name="asset_classification_id[${itemLineLength}]" id="classification_${itemLineLength}" class="form-control select2" required>
                            <option value="">Select Classification</option>
                            ${assetClassifications.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                        </select>
                    </td>
                    <td width="10%">
                        <select name="asset_category_id[${itemLineLength}]" id="category_${itemLineLength}" onchange="getAssetTypesEdit(this.value, '${itemLineLength}')" class="form-control select2" required>
                            <option value="">Select Category</option>
                            ${assetCategories.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                        </select>
                    </td>
                    <td width="10%">
                        <select name="asset_type_id[${itemLineLength}]" id="type_${itemLineLength}" class="form-control select2" required>
                            <option value="">Select Type</option>
                            ${assetTypes.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                        </select>
                    </td>
                    <td width="10%">
                        <select name="asset_item_id[${itemLineLength}]" id="item_${itemLineLength}" class="form-control select2" required>
                            <option value="">Select Item</option>
                            ${assetItems.map(item => `<option value="${item.id}">${item.name} [${item.item_code}]</option>`).join('')}
                        </select>
                    </td>
                    <td width="10%"><input class="form-control" type="text" name="asset_brand[${itemLineLength}]" placeholder="Brand name" required></td>
                    <td width="10%"><input class="form-control" type="text" name="asset_model[${itemLineLength}]" placeholder="Model name" required></td>
                    <td width="10%"><input type="text" name="item_key_id[${itemLineLength}]" class="form-control" placeholder="Key or ID" ></td>
                    <td width="10%">
                        <textarea class="form-control" name="asset_unit[${itemLineLength}]" placeholder="Specifications" row="5"></textarea>
                    </td>

                    <td width="6%"><input class="form-control text-center" type="text" id="qty_${itemLineLength}" name="asset_quantity[${itemLineLength}]" onchange="calculateAmountEdit('${itemLineLength}')" placeholder="0.00" value="1" readonly></td>
                    <td width="6%"><input class="form-control text-right" type="text" id="price_${itemLineLength}" name="asset_price[${itemLineLength}]" onchange="calculateAmountEdit('${itemLineLength}')" placeholder="0.00">
                        <input class="form-control text-right" type="hidden" id="amount_${itemLineLength}" name="asset_total[${itemLineLength}]" onchange="calculateAmountEdit('${itemLineLength}')" placeholder="0.00" readonly>
                    </td>
                    <td width="6%"><input class="form-control" type="text" name="serial_number[${itemLineLength}]" placeholder = "Serial number"></td>
                    <td width="5%"><input class="form-control" type="text" name="warranty[${itemLineLength}]" placeholder="0"></td>
                </tr> `;

        // Convert to jQuery element
        const $newRow = $(html);

        // Append to table
        $('#edit-item-line-container').append($newRow);

        // Initialize only newly added select2 elements
        $newRow.find('select.select2').select2({
            dropdownParent: $('#edit_vendor_offcanvas') // replace this ID with your actual offcanvas container ID
        });
    }

    function getAssetTypesEdit(categoryId, itemLineLength) {
        var assetTypes = {!! json_encode($assetTypes) !!};
        var categoryTypes = assetTypes.filter(type => type.asset_category_id == categoryId);
        let html = `
            <option value="">Select Type</option>
            ${categoryTypes.map(type => `<option value="${type.id}">${type.name}</option>`).join('')}
        `;

        $('#type_' + itemLineLength).html(html).trigger('change');
    }

    function calculateAmountEdit(itemLineLength) {

        var qty = $('#qty_' + itemLineLength).val();
        var price = $('#price_' + itemLineLength).val();
        var amount = qty * price;
        $('#amount_' + itemLineLength).val(amount);
        calculateGrandTotalEdit();
    }

    function calculateGrandTotalEdit() {
        var total = 0;
        $('#edit-item-line-container tr').each(function() {


            var amount = $(this).find('input[name^="asset_total["]').val();
            if (!isNaN(parseFloat(amount))) {
                total += parseFloat(amount);
            }
        });

        $('#total_amount_edit').text(total.toFixed(2));
        $('#grand_total_edit').val(total.toFixed(2));
    }


</script>

@endpush
