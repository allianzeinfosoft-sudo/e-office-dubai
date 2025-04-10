<form action="{{ route('recruitments.store-graduation') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col mb-3">
            <label for="graduation" class="form-label">Graduation</label>
            <input type="text" id="graduation" name="graduation" class="form-control" placeholder="Graduation">
        </div>
    </div>
</form>
                              