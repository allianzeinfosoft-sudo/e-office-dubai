@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('banner.store') }}" method="POST" id="banner-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="banner_title">Banner Title <span class="text-danger">*</span></label>
                <input type="text" name="banner_title" id="banner_title" class="form-control" placeholder="Banner Title" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_date">Display Date <span class="text-danger">*</span></label>
                <input type="date" name="display_date" id="display_date" class="form-control" placeholder="Display Date" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="banner_details">Banner Details</label>
                <div id="banner-editor"></div>
                <input type="hidden" name="banner_details" value="{{ strip_tags(old('banner_details')) }}" id="banner_details">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="picture">Picture <span class="text-danger">*</span></label>
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-center align-items-center" style="background-color: #625acc; height: 200px;">
                             <img id="PicturePreview" src="" accept="image/*" alt="" class="" style="width: 150px; height: 150px; object-fit: cover;  border: 2px solid #ddd;"/>
                        </div>
                        <div class="mb-3 mt-15">
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="file" id="picture" name="picture" onchange="previewImage(event)" required />
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
