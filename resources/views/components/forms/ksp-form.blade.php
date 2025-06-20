<form action="{{ route('tools.ksp.store') }}" method="post" id="ksp-form" enctype="multipart/form-data">
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="ksp_title">Title <span class="text-danger">*</span></label>
                <input type="text" name="ksp_title" id="ksp_title" class="form-control" placeholder="Enter KSP Title" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="ksp_category" class="d-flex justify-content-between">
                    <span>Category <span class="text-danger">*</span></span>
                    <a href="javascript:void(0)" class="text-primary text-xs" onclick="addCategoryModal()" ><i class="fa fa-plus"></i> Add</a>
                </label>
                <select name="ksp_category" id="ksp_category" class="form-control select2" required>
                    <option value="">Select Category</option>
                    @foreach($category as $result)
                        <option value="{{ $result->id }}">{{ $result->category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="created_by">Created By <span class="text-danger">*</span> </label>
                <select name="created_by" id="created_by" class="form-control select2" required>
                    <option value="">Select Employee</option>
                    @foreach($createdBy as $result)
                        <option value="{{ $result->user_id }}">{{ $result->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="ksp-details-editor">KSP Description</label>
                <div id="ksp-details-editor"></div>
                <input type="hidden" name="ksp_description" id="ksp_description">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="ksp_featured_image">Attachments</label>
                <input type="file" name="ksp_featured_image" id="ksp_featured_image" class="form-control" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save KSP
            </button>
        </div>   
    </div>
</form>
