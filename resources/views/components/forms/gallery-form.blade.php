@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('gallery.store') }}" method="POST" id="gallery-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="thoughts_title">Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Gallery Title" />
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
                <label for="gallery_details">Gallery Details</label>
                <div id="gallery-editor"></div>
                <input type="hidden" name="gallery_details" value="{{ strip_tags(old('gallery_details')) }}" id="gallery_details">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="gallery_details">Gallery Images</label>
                <input class="form-control" type="file" id="formFile" name="file[]"  multiple required/>
                <br>
                <div class="col-sm-12 mb-3">
                    <div id="image-preview" class="d-flex flex-wrap gap-2"></div>
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
    let selectedFiles = [];

    document.getElementById('formFile').addEventListener('change', function(event) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        selectedFiles = Array.from(event.target.files); // store files

        selectedFiles.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative me-2 mb-2';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.height = '100px';
                    img.style.width = 'auto';

                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                    removeBtn.style.zIndex = '1';
                    removeBtn.style.padding = '2px 6px';
                    removeBtn.onclick = () => {
                        selectedFiles.splice(index, 1);
                        updateInputFiles();
                        renderPreviews();
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    preview.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            }
        });

        updateInputFiles(); // refresh input with selectedFiles
    });

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        document.getElementById('formFile').files = dataTransfer.files;
    }

    function renderPreviews() {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative me-2 mb-2';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.height = '100px';
                img.style.width = 'auto';

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '&times;';
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
                removeBtn.style.zIndex = '1';
                removeBtn.style.padding = '2px 6px';
                removeBtn.onclick = () => {
                    selectedFiles.splice(index, 1);
                    updateInputFiles();
                    renderPreviews();
                };

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                preview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }
    </script>


@endpush
