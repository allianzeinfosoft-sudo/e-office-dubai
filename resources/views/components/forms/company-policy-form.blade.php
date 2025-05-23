<form action="{{ route('view.company-policies.store') }}" method="post" id="company-policy-form" enctype="multipart/form-data">
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="policyTitle">Policy Title <span class="text-danger">*</span></label>
                <input type="text" name="policyTitle" id="policyTitle" class="form-control" placeholder="Policy Title" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="policyStartDate">Policy Start Date <span class="text-danger">*</span> </label>
                <input type="text" name="policyStartDate" id="policyStartDate" class="form-control flatpickr-input" placeholder="Policy Start Date" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="policy-description-editor">Policy Description</label>
                <div id="policy-description-editor"></div>
                <input type="hidden" name="policyDescription" id="description">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="attachments">Attachments</label>
                <input type="file" name="attachments" id="attachments" class="form-control" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save
            </button>
        </div>   

    </div>
</form>