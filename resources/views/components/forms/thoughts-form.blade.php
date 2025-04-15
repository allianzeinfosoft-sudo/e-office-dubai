@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('thoughts.store') }}" method="POST" id="thoughts-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="thoughts_title">Thought Title</label>
                <input type="text" name="thoughts_title" id="thoughts_title" class="form-control" placeholder="Thought Title" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_date">Display Date</label>
                <input type="date" name="display_date" id="display_date" class="form-control" placeholder="Display Date" />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="thoughts_details">Thoughts_details</label>
                <div id="thoughts-editor"></div>
                <input type="hidden" name="thoughts_details" value="{{ strip_tags(old('thoughts_details')) }}" id="thoughts_details">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="thoughts_details">Picture</label>
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-center align-items-center" style="background-color: #625acc; height: 200px;">
                             <img id="PicturePreview" src="" alt="" class="" style="width: 150px; height: 150px; object-fit: cover;  border: 2px solid #ddd;"/>
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



// form validation

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('thoughts-form');
        const quillEditor = new Quill('#thoughts-editor', { theme: 'snow' });

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values

            const thoughts_title = document.getElementById('thoughts_title').value.trim();
            const display_date = document.getElementById('display_date').value.trim();
            const picture = document.getElementById('picture').value.trim();
            const thoughts_details = quillEditor.root.innerText.trim();
            const hiddenThoughts_details = document.getElementById('thoughts_details');

            hiddenThoughts_details.value = quillEditor.root.innerHTML;

            let errors = [];

            // === Validation ===
            if (!thoughts_title) {
                errors.push("Thoughts Title is required.");
            }

            if (!display_date) {
                errors.push("Display date is required.");
            } else if (isNaN(Date.parse(display_date))) {
                errors.push("Display date must be a valid date.");
            }

            if (!picture) {
                errors.push("Picture is required.");
            }

            if (!hiddenThoughts_details) {
                errors.push("Thoughts details reason is required");
            }

            // === Show errors or submit ===
            let errorBox = document.getElementById('formErrors');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'formErrors';
                errorBox.className = 'alert alert-danger mt-3';
                form.prepend(errorBox);
            }

            if (errors.length > 0) {
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else {
                errorBox.innerHTML = ''; // Clear old errors
                form.submit(); // Submit manually only if no errors
            }
        });
    });




    </script>

    @endpush
