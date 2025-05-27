@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('appreciation.store') }}" method="POST" id="appreciation-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="appreciant">Name of Appreciants <span class="text-danger">*</span></label>
                <select name="appreciant[]" id="appreciant" class="form-control select2" multiple required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ (old('appreciant') == $user->id ) ? 'selected' : '' }}>{{ $user->employee->full_name ?? 'N/A' }}</option>
                    @endforeach
                </select>
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
                <label for="display_date">Project</label>
                <select class="form-control" name="project" id="project" >
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ (old('project') == $project) ? 'selected' : '' }} >{{ $project->project_name ?? ''}}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="appreciation_details">Appreciation Details <span class="text-danger">*</span></label>
                <div id="appreciation-editor"></div>
                <input type="hidden" name="appreciation_details" value="{{ strip_tags(old('appreciation_details')) }}" id="appreciation_details">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">

                <div class="card-body d-flex flex-wrap gap-3">

                    @php
                        $flowers = ['flowerdefault-avatar.png', 'flower2.png', 'flower3.png', 'flower4.png'];
                    @endphp

                    @foreach ($flowers as $flower)
                        <label class="d-flex flex-column align-items-center" style="cursor: pointer;">
                            <img src="{{ asset('storage/appreciation_flowers/' . $flower) }}"
                                alt="{{ $flower }}"
                                style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #ccc; border-radius: 8px; margin-bottom: 8px;">

                            <input type="radio" name="picture" id="picture" value="{{ $flower }}">
                        </label>
                    @endforeach

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






    </script>

    @endpush
