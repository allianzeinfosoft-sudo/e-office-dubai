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
                <label for="event_document">Document</label>
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-center align-items-center" style="background-color: #625acc; height: 200px;">
                             <img id="PicturePreview" src="" alt="" class="" style="width: 150px; height: 150px; object-fit: cover;  border: 2px solid #ddd;"/>
                        </div>
                        <div class="mb-3 mt-15">
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="file" id="picture"  accept="image/*,application/pdf"  name="picture" onchange="previewImage(event)" />
                            </div>
                        </div>
                  </div>
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
@push('js')
  <script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById("PicturePreview");
   const defaultImage = "https://cdn-icons-png.flaticon.com/512/337/337946.png";

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileType = file.type;

        // ✅ If file is an image
        if (fileType && fileType.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
        // ❌ If file is NOT an image
        else {
            preview.src = defaultImage;
            preview.style.display = "block";
        }
    } else {
        // If no file selected, reset preview
        preview.src = defaultImage;
        preview.style.display = "block";
    }
}
</script>


@endpush
