<form action="{{ route('e-library.categories.store') }}" method="post" id="books-category-form" enctype="multipart/form-data">
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="name">Category <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Category" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="parent_id">Parent </label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">Select Parent</option>
                    @if($parent_categories->count() > 0)
                        @foreach($parent_categories as $result)
                            <option value="{{ $result->id }}">{{ $result->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>


        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i>&nbsp;&nbsp; Save
            </button>
        </div>   
    </div>
</form>
