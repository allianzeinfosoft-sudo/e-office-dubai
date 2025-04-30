@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('appearences.store') }}" method="POST" id="appearence-background-form" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="background_type">Background Type</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="background_type[]" id="main_background" value="all">
                    <label class="form-check-label" for="main_background">All Background</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="background_type[]" id="feeds_background" value="feeds">
                    <label class="form-check-label" for="feeds_background">Feeds Background</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="background_type[]" id="login_background" value="login">
                    <label class="form-check-label" for="login_background">Login Background</label>
                </div>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="picture">Image</label>
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-center align-items-center" style="background-color: #625acc; height: 200px;">
                             <img id="PicturePreview" src="" accept="image/*" alt="" class="" style="width: 150px; height: 150px; object-fit: cover;  border: 2px solid #ddd;"/>
                        </div>
                        <div class="mb-3 mt-15">
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="file" id="image" name="image" onchange="previewImage(event)" />
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
