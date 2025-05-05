<form action="{{ route('others.announcements.store') }} " method="post" id="announcement-form" >
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name_announcement">Announcement Title</label>
                <input type="text" name="name_announcement" id="name_announcement" class="form-control" placeholder="Announcement Title" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_start_date">Display Start Date</label>
                <input type="text" name="display_start_date" id="display_start_date" class="form-control flatpickr-input" placeholder="Display Start Date" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_end_date">Display End Date</label>
                <input type="text" name="display_end_date" id="display_end_date" class="form-control flatpickr-input" placeholder="Display End Date" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="description-editor">Job Description</label>
                <div id="description-editor"></div>
                <input type="hidden" name="description" id="description">
            </div>
        </div>
        
        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>   
    </div>
</form>