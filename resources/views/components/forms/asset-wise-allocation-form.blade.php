@push('css')

@endpush
<div>
    <form action="{{ route('assets.allocation.asset_wise_store') }}" method="POST" id="asset-wise-allocation-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="asset_id" id="asset-wise-target_id">

        <div class="row">

        </div>
        <div class="row">
            <div class="col-sm-12 mb-3">
                <table class="table table-bordered table-striped table-xs fs-6" id="asset-wise-item-line-table" style="font-size: 11px !important;">
                    <thead>
                        <tr style="text-align: center">
                            <th>User Type</th>
                            <th>User</th>
                            <th>Department</th>
                            <th>Item</th>
                            <th>Asset ID</th>
                            <th>Model</th>
                            <th>Brand</th>
                            <th>Classification</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Serial Number</th>
                            <th>Specification</th>
                             <th>Project</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="asset-wise-item-line-container">

                    </tbody>
                    <tfoot>
                        <tr>
                             <th colspan="13" class="text-right"></th>
                            <th><button type="button" class="btn btn-xs btn-icon btn-success waves-effect" onclick="assetWiseaddItemLine()"><i class="ti ti-plus"></i></button></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="asset_wise_remarks" id="asset_wise_remarks" cols="30" rows="5" class="form-control"></textarea>
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

    function assetWiseaddItemLine() {

        var asset_wise_itemLineLength = $('#asset-wise-item-line-container tr').length + 1;
        var asset_wise_assetItems = {!! json_encode($asset_wise_assetItems) !!};
        var asset_wise_assetClassifications = {!! json_encode($asset_wise_assetClassifications) !!};
        var asset_wise_assetCategories = {!! json_encode($asset_wise_assetCategories) !!};
        var asset_wise_assetTypes = {!! json_encode($asset_wise_assetTypes) !!};
        var asset_wise_assetProjects = {!! json_encode($asset_wise_projects) !!}
        var asset_wise_userType = $("#asset_wise_asset_user").val();
        var rowIndex = $('#asset-wise-item-line-container tr').length + 1;
        var assetUsers = {!! json_encode(config('optionsData.asset_users')) !!};

            // User Type Select
            let userTypeSelect = `
                <select name="asset_wise_user_type[${rowIndex}]"
                        class="form-control select2 asset-wise-user-type"
                        data-row="${rowIndex}">
                    <option value="">Select User Type</option>
                    ${Object.entries(assetUsers).map(([key, val]) => `<option value="${key}">${val}</option>`).join('')}
                </select>`;

            // Empty User select (will be filled via AJAX based on user type)
            let userSelect = `
                <select name="asset_wise_user[${rowIndex}]"
                        class="form-control select2 asset-wise-user"
                        id="asset_wise_user_${rowIndex}"
                        data-row="${rowIndex}">
                </select>`;

            // Department text (auto-filled)
            let departmentInput = `
                <input type="text"
                    class="form-control"
                    name="asset_wise_department[${rowIndex}]"
                    id="asset_wise_department_${rowIndex}"
                    readonly />
                <input type="hidden" name="asset_wise_department_id[${rowIndex}]" id="asset_wise_department_id_${rowIndex}" />`;

            let assetWiseRow = `
                <tr data-row="${rowIndex}">
                    <td>${userTypeSelect}</td>
                    <td>${userSelect}</td>
                    <td>${departmentInput}</td>
                    <td>
                        <select name="asset_wise_asset_item_id[${rowIndex}]" class="form-control select2 asset-wise-asset-item-select" data-row="${rowIndex}">
                            <option value="">Select Item</option>
                            ${asset_wise_assetItems.map(item => `<option value="${item.id}">${item.name} [${item.item_code}]</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <select name="asset_wise_asset_code_id[${rowIndex}]" class="form-control select2 asset-wise-asset-code-select" data-row="${rowIndex}">
                            <option value="">Select Asset ID</option>
                        </select>
                    </td>
                    <td><input class="form-control" type="text" name="asset_wise_asset_model[${rowIndex}]" id="asset_wise_asset_model_${rowIndex}" readonly></td>
                    <td><input class="form-control" type="text" name="asset_wise_asset_brand_id[${rowIndex}]" id="asset_wise_asset_brand_${rowIndex}" readonly></td>
                    <td><input class="form-control" type="text" name="asset_wise_asset_classification_id[${rowIndex}]" id="asset_wise_asset_classification_${rowIndex}" readonly></td>
                    <td><input class="form-control" type="text" name="asset_wise_asset_category_id[${rowIndex}]" id="asset_wise_asset_category_${rowIndex}" readonly></td>
                    <td><input class="form-control" type="text" name="asset_wise_asset_type_id[${rowIndex}]" id="asset_wise_asset_type_${rowIndex}" readonly></td>
                    <td><input class="form-control" type="text" name="asset_wise_asset_serialnumber[${rowIndex}]" id="asset_wise_asset_serialnumber_${rowIndex}" readonly></td>
                    <td><textarea class="form-control" name="asset_wise_specification[${rowIndex}]" id="asset_wise_specification_${rowIndex}" readonly></textarea></td>
                    <td>
                        <select name="asset_wise_asset_project_id[${rowIndex}]" class="form-control select2">
                            <option value="">Select Project</option>
                            ${asset_wise_assetProjects.map(proj => `<option value="${proj.id}">${proj.project_name}</option>`).join('')}
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" onclick="$(this).closest('tr').remove();">
                            <i class="ti ti-minus"></i>
                        </button>
                    </td>
                </tr>`;

            const $newRow = $(assetWiseRow);
            $('#asset-wise-item-line-container').append($newRow);
             $newRow.find('select.select2').select2({
                width: '100%',
                dropdownParent: $('#asset-wise-allocation-form') // adjust if in modal/offcanvas
            });
        }



 $(document).ready(function () {

        $(document).on('change', '.asset-wise-user-type', function() {
            let row = $(this).data('row');
            let userType = $(this).val();
            let $userSelect = $(`#asset_wise_user_${row}`);

            $userSelect.html('<option value="">Loading...</option>');

            if(userType === 'employee'){
                $.get('/get-all-employees', function(response){
                    let options = '<option value="">Select Employee</option>';
                    Object.entries(response).forEach(([id,name]) => {
                        options += `<option value="${id}">${name}</option>`;
                    });
                    $userSelect.html(options).trigger('change');
                });
            } else if(userType === 'location'){
                $.get('/get-all-locations', function(response){
                    let options = '<option value="">Select Location</option>';
                    Object.entries(response).forEach(([id,name]) => {
                        options += `<option value="${id}">${name}</option>`;
                    });
                    $userSelect.html(options).trigger('change');
                });
            } else {
                $userSelect.html('');
            }
        });
    });



    // fetch department

     $(document).on('change', '.asset-wise-user', function() {
        let row = $(this).data('row');
        let userType = $(`.asset-wise-user-type[data-row="${row}"]`).val();
        let userId = $(this).val();

        if(userType === 'employee' && userId){
            $.get('/get-employee-department', { employee_id: userId }, function(response){
                $(`#asset_wise_department_${row}`).val(response.department || '');
                $(`#asset_wise_department_id_${row}`).val(response.department_id || '');
            });
        } else {
            $(`#asset_wise_department_${row}`).val('');
            $(`#asset_wise_department_id_${row}`).val('');
        }
    });



    // When an asset item is selected, fetch models
    $(document).on('change', '.asset-wise-asset-item-select', function () {
        const assetWiseItemId = $(this).val();
        const asset_wise_row = $(this).data('row');

        const $assetWiseAssetCodeSelect = $(`select.asset-wise-asset-code-select[data-row="${asset_wise_row}"]`);
        if ($assetWiseAssetCodeSelect.length === 0) {
                console.error("Target select not found for row:", asset_wise_row);
                return;
            }
        $assetWiseAssetCodeSelect.html('<option value="">Loading...</option>');

        if (assetWiseItemId) {
            $.ajax({
                url: '/get-asset-code',
                type: 'GET',
                data: { item_id: assetWiseItemId },
                success: function (response) {
                    let options = '<option value="">Select Asset Code</option>';

                    response.forEach(function (item) {
                        options += `<option value="${item.id}">${item.assetCode}</option>`;
                    });

                    $assetWiseAssetCodeSelect.html(options).trigger('change');
                },
                error: function () {
                    $assetWiseAssetCodeSelect.html('<option value="">Failed to load models</option>');
                }
            });
        } else {
            $assetWiseAssetCodeSelect.html('<option value="">Select Asset Code</option>');
        }
    });

 // get mapped asset item is item code selected
    $(document).on('change', '.asset-wise-asset-code-select', function () {
        const itemId = $(this).val();
        const row = $(this).data('row');

        const $asset_wise_modelInput = $(`#asset_wise_asset_model_${row}`);
        const $asset_wise_brandInput = $(`#asset_wise_asset_brand_${row}`);
        const $asset_wise_classificationInput =$(`#asset_wise_asset_classification_${row}`);
        const $asset_wise_categoryInput = $(`#asset_wise_asset_category_${row}`);
        const $asset_wise_typeInput = $(`#asset_wise_asset_type_${row}`);
        const $asset_wise_serialInput = $(`#asset_wise_asset_serialnumber_${row}`);
        const $asset_wise_specificationInput = $(`#asset_wise_specification_${row}`);


        if (itemId) {
            $.ajax({
                url: '/get-asset-mapped-item',
                type: 'GET',
                data: { item_id: itemId },
                success: function (data) {

                    $asset_wise_modelInput.val(data.model || '');
                    $asset_wise_brandInput.val(data.brand || '');
                    $asset_wise_classificationInput.val(data.classification || '');
                    $asset_wise_categoryInput.val(data.category || '');
                    $asset_wise_typeInput.val(data.type || '');
                    $asset_wise_serialInput.val(data.serial_number || '');
                    $asset_wise_specificationInput.val(data.specification || '');
                },
                error: function () {
                    $assetWiseAssetCodeSelect.html('<option value="">Failed to load models</option>');
                }
            });
        } else {
            $assetWiseAssetCodeSelect.html('<option value="">Select Asset Code</option>');
        }
    });
</script>

@endpush
