<form action="{{ route('others.announcements.store') }} " method="post" id="announcement-form" >
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name_announcement">Announcement Title <span class="text-danger">*</span></label>
                <input type="text" name="name_announcement" id="name_announcement" class="form-control" placeholder="Announcement Title" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_start_date">Display Start Date <span class="text-danger">*</span></label>
                <input type="text" name="display_start_date" id="display_start_date" class="form-control flatpickr-input" placeholder="Display Start Date" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_end_date">Display End Date <span class="text-danger">*</span></label>
                <input type="text" name="display_end_date" id="display_end_date" class="form-control flatpickr-input" placeholder="Display End Date" required />
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
            <div class="form-group">
                <label for="announcement_details">Image</label>
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-center align-items-center" style="background-color: #625acc; height: 200px;">
                             <img id="PicturePreview" src="" accept="image/*" alt="" class="" style="width: 150px; height: 150px; object-fit: cover;  border: 2px solid #ddd;"/>
                        </div>
                        <div class="mb-3 mt-15">
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="file" id="picture" name="picture" onchange="previewImage(event)" />
                            </div>
                        </div>
                  </div>
            </div>
        </div>



        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>


@push('js')
    <script>
        function previewImage(event) {

        const input = event.target;
        const preview = document.getElementById("PicturePreview");

        if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
