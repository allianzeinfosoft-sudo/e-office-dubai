<div>
    <div>
    @push('css')

    @endpush
<div>
    <form action="{{ route('assets.expiry-register.store') }}" method="POST" id="expiry-register-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" id="target_id">

        <div class="row">

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="service_name">Service Name<span class="text-danger">*</span></label>
                    <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service Name" required/>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="asset_category_id">Category<span class="text-danger">*</span></label>
                    <select name="asset_category_id" id="asset_category_id" class="form-control select2" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $key => $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="asset_vendor_id">Vendor</label>
                    <select name="asset_vendor_id" id="asset_vendor_id" class="form-control select2" required>
                        <option value="">Select Vendor</option>
                        @foreach ($vendors as $key => $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="licence_id">Licence ID<span class="text-danger">*</span></label>
                    <input type="text" name="licence_id" id="licence_id" class="form-control" placeholder="Licence ID" required />
                </div>
            </div>

            <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="licence_count">Licence Count<span class="text-danger">*</span></label>
                    <input type="text" name="licence_count" id="licence_count" class="form-control" placeholder="Licence Count" required />
                </div>
            </div>

             <div class="col-sm-6 mb-3">
                <div class="form-group">
                    <label for="cost">Cost<span class="text-danger">*</span></label>
                    <input type="text" name="cost" id="cost" class="form-control" placeholder="Cost" required />
                </div>
            </div>

             <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="start_date">Start Date<span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Start Date" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="last_updated_date">Last Updated Date<span class="text-danger">*</span></label>
                    <input type="date" name="last_updated_date" id="last_updated_date" class="form-control" placeholder="Last Updated Date" required />
                </div>
            </div>

            <div class="col-sm-4 mb-3">
                <div class="form-group">
                    <label for="expiry_date">Expiry Date<span class="text-danger">*</span></label>
                    <input type="date" name="expiry_date" id="expiry_date" class="form-control" placeholder="Expiry Date" required />
                </div>
            </div>

            <div class="col-sm-12 mb-3">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" cols="30" rows="5" class="form-control" ></textarea>
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

    $('#start_date, #last_updated_date, #expiry_date').flatpickr({
        altInput: true,
        altFormat: 'd-m-Y',
        dateFormat: 'd-m-Y'
    });


</script>

@endpush
</div>
