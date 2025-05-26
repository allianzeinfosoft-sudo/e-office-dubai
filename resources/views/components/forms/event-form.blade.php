<form action="{{ route('others.events.store') }}" method="post" id="event-form">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="eventTitle">Event Title<span class="text-danger">*</span></label>
                <input type="text" name="eventTitle" id="eventTitle" class="form-control" placeholder="Event Title" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="eventDate">Event Date<span class="text-danger">*</span></label>
                <input type="text" name="eventDate" id="eventDate" class="form-control flatpickr-input" placeholder="Event Date" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="description-editor">Event Description</label>
                <div id="description-editor"></div>
                <input type="hidden" name="description" id="description">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save
            </button>
        </div>
    </div>
</form>
