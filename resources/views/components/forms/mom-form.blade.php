<form action="{{ route('others.moms.store') }}" method="post" id="mom-form" enctype="multipart/form-data">
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="mom_title">MOM Title</label>
                <input type="text" name="mom_title" id="mom_title" class="form-control" placeholder="Enter MOM Title" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="mom_date">MOM Date</label>
                <input type="text" name="mom_date" id="mom_date" class="form-control flatpickr-input" placeholder="Select Date" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="created_by">Created By</label>
                <select name="created_by" id="created_by" class="form-control select2">
                    <option value="">Select Employee</option>
                    @foreach($createdBy as $result)
                        <option value="{{ $result->user_id }}">{{ $result->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="assigned_to">Assign To</label>
                <select name="assigned_to[]" id="assigned_to" class="form-control select2" multiple>
                    @foreach($assignedTo as $user)
                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="mom-details-editor">MOM Description</label>
                <div id="mom-details-editor"></div>
                <input type="hidden" name="mom_details" id="mom_details">
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
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save MOM
            </button>
        </div>   
    </div>
</form>
