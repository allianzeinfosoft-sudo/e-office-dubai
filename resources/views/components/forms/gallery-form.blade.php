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
                <label for="gallery_details">Picture</label>
                    <div class="card-body">
                        <div class="mb-3 mt-15">
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="file" id="picture" name="picture"/>
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
