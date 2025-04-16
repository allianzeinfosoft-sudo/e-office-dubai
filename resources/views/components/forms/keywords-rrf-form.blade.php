<form action="{{route('recruitments.store-keywords') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col mb-3">
            <label for="name" class="form-label">Keywords</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Keywords">
        </div>
    </div>
</form>
                              