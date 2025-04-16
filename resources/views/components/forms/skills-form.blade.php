<form action="{{ route('recruitments.store-skills') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col mb-3">
            <label for="name" class="form-label">Skill</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Skills">
        </div>
    </div>
</form>
                              