<form action="{{route('recruitments.store-keywords') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col mb-3">
            <label for="name" class="form-label">Keywords</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Keywords">
        </div>
        <div class="clearfix"></div>
        <div class="col-12 mb-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
    
</form>
                              