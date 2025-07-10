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

            <div class="col-sm-4 mb-3">
                <div class="form-group" id="asset_department_details">

                </div>
            </div>


            {{-- <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="upload_invoice">Upload Invoice</label>
                    <input type="file" name="upload_invoice" id="upload_invoice" class="form-control">
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <table class="table table-bordered table-striped table-xs fs-6" id="item-line-table" style="font-size: 11px !important;">
                    <thead>
                        <tr style="text-align: center">
                            <th>Item</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Asset ID</th>
                            <th>Project</th>
                            {{-- <th>Available Qty</th>
                            <th>Qty</th> --}}
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
        var assetProjects = {!! json_encode($projects) !!}

        let html = `<tr data-row="${itemLineLength}">
                        <td>
                            <select name="asset_item_id[${itemLineLength}]" id="item_${itemLineLength}" class="form-control
                             select2 asset-item-select" data-row="${itemLineLength}">
                                <option value="">Select Item</option>
                                ${assetItems.map(item => `<option value="${item.id}">${item.name} [${item.item_code} - ${item.brand}]</option>`).join('')}
                            </select>
                        </td>
                        <td>
                             <select name="asset_model_id[${itemLineLength}]" id="asset_model_${itemLineLength}"
                             class="form-control select2 asset-model-select" data-row="${itemLineLength}"">
                                <option value="">Select Model</option>
                            </select>
                        </td>

                        <td>
                            <select name="asset_serialnumber[${itemLineLength}]" id="asset_serialnumber_${itemLineLength}" class="form-control
                            select2 asset-serial-select" data-row="${itemLineLength}"">
                                <option value="">Select Serial</option>

                            </select>
                        </td>

                         <td>
                            <select name="asset_id[${itemLineLength}]" id="asset_id_${itemLineLength}" class="form-control
                            select2 asset-id-select" data-row="${itemLineLength}"" data-placeholder="Select Asset ID">
                                <option value="">Select ID</option>
                            </select>
                        </td>

                        <td>
                             <select name="asset_project_id[${itemLineLength}]" id="asset_project_${itemLineLength}" class="form-control
                             select2" data-row="${itemLineLength}">
                                <option value="">Select Project</option>
                                ${assetProjects.map(item => `<option value="${item.id}">${item.project_name}</option>`).join('')}
                            </select>
                        </td>

                        {{--
                            <td>
                                <input class="form-control text-center" type="text" id="available_qty_${itemLineLength}" name="asset_available_quantity[${itemLineLength}]" value="0">
                            </td>

                            <td>
                                <input class="form-control text-center" type="text" id="qty_${itemLineLength}" name="asset_quantity[${itemLineLength}]" value="0">
                            </td>
                        --}}

                        <td>
                            <textarea class="form-control" name="specification[${itemLineLength}]" id="specification_${itemLineLength}" ></textarea>
                        </td>
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
            $('#asset_department_details').html('');

            if (selectedValue === 'employee') {
                $.ajax({
                    url: '/get-all-employees',
                    type: 'GET',
                    success: function (response) {
                        let selectHTML = `
                            <label for="asset_employee">Employee</label>
                            <select class="form-control select2" name="asset_employee" id="asset_employee">
                                <option></option>
                                ${Object.entries(response).map(([id, name]) => `<option value="${id}">${name}</option>`).join('')}
                            </select>`;

                        $('#asset_user_details').html(selectHTML);


                       setTimeout(() => {
                        $('#asset_employee').select2({
                                    placeholder: 'Select Employee',
                                    width: '100%',
                                    dropdownParent: $('#asset_user_details')
                                });
                            }, 100);
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
                        setTimeout(() => {
                            $('#asset_location').select2({
                                placeholder: 'Select Employee',
                                width: '100%',
                                dropdownParent: $('#asset_user_details')
                            });
                        }, 100);
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



    // fetch department

      $(document).on('change', '#asset_employee', function () {

            let selectedValue = $(this).val();
                $.ajax({
                    url: '/get-employee-department',
                    type: 'GET',
                      data: {
                            employee_id: selectedValue
                        },
                    success: function (response) {

                          let html = `
                                <label for="employee_department">Department</label>
                                <input type="text" class="form-control" id="employee_department"
                                    name="employee_department"
                                    value="${response.department ?? ''}"
                                    readonly />
                                <input type="hidden" name="department_id" value="${response.department_id ?? ''}" />
                            `;

                            $('#asset_department_details').html(html);

                    },
                    error: function () {
                        alert('Failed to load employee list.');
                    }
                });

        });




    // When an asset item is selected, fetch models
    $(document).on('change', '.asset-item-select', function () {
        const itemId = $(this).val();
        const row = $(this).data('row');

        const $modelSelect = $(`select.asset-model-select[data-row="${row}"]`);
        const $serialSelect = $(`select.asset-serial-select[data-row="${row}"]`);

        $modelSelect.html('<option value="">Loading...</option>');
        $serialSelect.html('<option value="">Select Serial</option>');

        if (itemId) {
            $.ajax({
                url: '/get-asset-models',
                type: 'GET',
                data: { item_id: itemId },
                success: function (models) {
                    let options = '<option value="">Select Model</option>';
                    Object.keys(models).forEach(function (key) {
                        options += `<option value="${key}">${key}</option>`;
                    });

                    $modelSelect.html(options).trigger('change');
                },
                error: function () {
                    $modelSelect.html('<option value="">Failed to load models</option>');
                }
            });
        }
    });

   // When a model is selected, fetch serial numbers
    $(document).on('change', '.asset-model-select', function () {
        const row = $(this).data('row');
        const model = $(this).val(); // This is likely item_model (name or id)
        const itemId = $(`select.asset-item-select[data-row="${row}"]`).val();

        const $serialSelect = $(`select.asset-serial-select[data-row="${row}"]`);
        $serialSelect.html('<option value="">Loading...</option>');


        if (model && itemId) {
            $.ajax({
                url: '/get-asset-serials',
                type: 'GET',
                data: {
                    item_id: itemId,
                    item_model: model
                },
                success: function (response) {
                    let options = '<option value="">Select Serial</option>';
                    response.serials.forEach(serial => {
                        options += `<option value="${serial.serial_number}">${serial.serial_number}</option>`;
                    });
                    $serialSelect.html(options);

                },
                error: function () {
                    $serialSelect.html('<option value="">Failed to load serials</option>');
                }
            });
        } else {
            $serialSelect.html('<option value="">Select Serial</option>');
        }
    });



      // When a model is selected, fetch qty
    // $(document).on('change', '.asset-serial-select', function () {
    //     const row = $(this).data('row');
    //     const serial_number = $(this).val(); // This is likely item_model (name or id)

    //     const $qtyInput = $(`#available_qty_${row}`);
    //     $qtyInput.val('0.00');

    //     if (serial_number) {
    //         $.ajax({
    //             url: '/get-asset-qty',
    //             type: 'GET',
    //             data: {
    //                 serial_number: serial_number
    //             },
    //             success: function (response) {

    //                  // Set available quantity
    //                 if (response.total_quantity !== undefined) {
    //                     $qtyInput.val(parseFloat(response.total_quantity));
    //                 }

    //             },
    //             error: function () {
    //                 $serialSelect.html('<option value="">Failed to load qty</option>');
    //             }
    //         });
    //     } else {
    //         $serialSelect.html('<option value="">Select Serial</option>');
    //     }
    // });


     // When a model is selected, fetch asset id's
    $(document).on('change', '.asset-serial-select', function () {
        const row = $(this).data('row');
        const serialId = $(this).val();
        const $idSelect = $(`select.asset-id-select[data-row="${row}"]`);
        $idSelect.html('<option value="">Loading...</option>');

        if (serialId) {
            $.ajax({
                url: '/get-asset-ids',
                type: 'GET',
                data: {
                    serial_id: serialId,
                },
                success: function (response) {
                    let options = '';
                    response.assetIds.forEach(assetId => {
                        options += `<option value="${assetId.asset_id_number}">${assetId.asset_id}</option>`;
                    });
                    $idSelect.html(options);

                },
                error: function () {
                    $idSelect.html('<option value="">Failed to load asset ids </option>');
                }
            });
        } else {
            $idSelect.html('<option value="">Select Asset ids</option>');
        }
    });


    // $(document).on('change', '.asset-id-select', function () {
    //     const row = $(this).data('row');
    //     const selectedCount = $(this).val() ? $(this).val().length : 0;

    //     $(`#qty_${row}`).val(selectedCount);
    // });
</script>

@endpush
