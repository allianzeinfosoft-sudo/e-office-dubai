@push('css')

@endpush
<div>
    <form action="{{ route('assets.register.store') }}" method="POST" id="register-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="target_id">

        <div class="row">

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="asset_number">Reg. No. <span class="text-danger">*</span></label>
                    <input type="text" name="asset_number" id="asset_number" class="form-control" placeholder="Register Number" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="company_name">Company <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="company_name" id="company_name">
                        <option value="">Select Company</option>
                        @foreach(config('optionsData.companies') as $key => $label)
                            <option value="{{ $key }}"> {{ $label }} </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="asset_date">Purchase Date <span class="text-danger">*</span></label>
                    <input type="text" name="asset_date" id="asset_date" class="form-control" placeholder="Date" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="asset_date">Invoice No <span class="text-danger">*</span></label>
                    <input type="text" name="asset_date" id="asset_date" class="form-control" placeholder="Date" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="vendor_id">Vendor <span class="text-danger">*</span></label>
                    <select name="vendor_id" id="vendor_id" class="form-control select2">
                        <option value="">Select Vendor</option>
                        @foreach ($vendors as $key => $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="attachments">Upload Invoice</label>
                    <input type="file" name="attachments" id="attachments" class="form-control">
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <table class="table table-bordered table-striped table-xs fs-6" id="item-line-table" style="font-size: 11px !important;">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Model</th>
                            <th>Serial No.</th>
                            <th>Warranty</th>
                            <th>Classification</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="item-line-container">
                       
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="10" class="text-right"><h5 class="mb-0">GrandTotal</h5></th>
                            <th class="text-right fw-semibold">
                                <h5 class="mb-0" id="total_amount">0.00</h5>
                                <input type="hidden" name="grand_total" id="grand_total">
                            </th>
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
    $('#asset_date').flatpickr({
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

        let html = `
            <tr>
                <td>
                    <select name="items[]" id="item_${itemLineLength}" class="form-control select2">
                        <option value="">Select Item</option>
                        ${assetItems.map(item => `<option value="${item.id}">${item.name} [${item.item_code} - ${item.brand}]</option>`).join('')}
                    </select>
                </td>
                <td><input class="form-control" type="text" name="model[]"></td>
                <td><input class="form-control" type="text" name="serial[]"></td>
                <td><input class="form-control" type="text" name="warenty[]"></td>
                <td>
                    <select name="classification[]" id="classification_${itemLineLength}" class="form-control select2">
                        <option value="">Select Classification</option>
                        ${assetClassifications.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <select name="category[]" id="category_${itemLineLength}" onchange="getAssetTypes(this.value, '${itemLineLength}')" class="form-control select2">
                        <option value="">Select Category</option>
                        ${assetCategories.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                    </select>
                </td>
                <td>
                    <select name="type[]" id="type_${itemLineLength}" class="form-control select2">
                        <option value="">Select Type</option>
                        ${assetTypes.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                    </select>
                </td>
                <td><input class="form-control" type="text" name="unit[]" value=""></td>
                <td><input class="form-control text-center" type="text" id="qty_${itemLineLength}" name="qty[]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                <td><input class="form-control text-right" type="text" id="price_${itemLineLength}" name="price[]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
                <td><input class="form-control text-right" type="text" id="amount_${itemLineLength}" name="Amount[]" onchange="calculateAmount('${itemLineLength}')" value="0.00"></td>
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
            dropdownParent: $('#vendor_offcanvas') // replace this ID with your actual offcanvas container ID
        });
    }

    function getAssetTypes(categoryId, itemLineLength) {
        var assetTypes = {!! json_encode($assetTypes) !!};
        var categoryTypes = assetTypes.filter(type => type.asset_category_id == categoryId);
        let html = `
            <option value="">Select Type</option>
            ${categoryTypes.map(type => `<option value="${type.id}">${type.name}</option>`).join('')}
        `;

        $('#type_' + itemLineLength).html(html).trigger('change');
    }

    function calculateAmount(itemLineLength) {
        var qty = $('#qty_' + itemLineLength).val();
        var price = $('#price_' + itemLineLength).val();
        var amount = qty * price;
        $('#amount_' + itemLineLength).val(amount);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var total = 0;
        $('#item-line-container tr').each(function() {
            var amount = $(this).find('input[name="Amount[]"]').val();
            total += parseFloat(amount);
        });
        $('#total_amount').text(total.toFixed(2));
        $('#grand_total').val(total.toFixed(2));
    }

    
</script>
    
@endpush