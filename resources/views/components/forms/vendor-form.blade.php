<form action="{{ route('assets.vendors.store') }}" method="POST" id="vendor-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="vendor_code">Vendor Code <span class="text-danger">*</span></label>
                <input type="text" name="vendor_code" id="vendor_code" class="form-control" placeholder="Vendor Code" value="{{ $vendorCode }}" readonly />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <span class="d-flex justify-content-between">
                    <label for="vendor_category">Vendor Category <span class="text-danger">*</span></label>
                    <span><a href="javascript:void(0)" class="text-primary text-xs" onclick="viewCategoryModal()" ><i class="fa fa-plus"></i> Add</a></span>
                </span>

                <select name="vendor_category" id="vendor_category" class="form-control select2" required>
                    <option value="">Select Category</option>
                        @foreach($vendorCategories as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="vendor_name">Vendor Name <span class="text-danger">*</span></label>
                <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Vendor Name" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control" placeholder="Contact Person Name" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Contact Number" />
            </div>
        </div>

         <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="vendor_address">Vendor Address</label>
                <textarea name="vendor_address" id="vendor_address" class="form-control" rows="3" placeholder="Address"></textarea>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="website">Website</label>
                <input type="text" name="website" id="website" class="form-control" placeholder="Website" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="mobile_number">Mobile</label>
                <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="Mobile" />
            </div>
        </div>


        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save Vendor
            </button>
        </div>

    </div>
</form>
