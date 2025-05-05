<form action="{{ route('recruitments.store-mini-qualification') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col mb-3">
            <label for="name" class="form-label">Qualification</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Name">
        </div>
    </div>
</form>
                              