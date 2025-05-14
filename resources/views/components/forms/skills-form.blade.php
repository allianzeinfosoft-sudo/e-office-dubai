<form action="{{ route('recruitments.store-skills') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col-12 mb-3">
            <label for="skill_name" class="form-label">Skill</label>
            <input type="text" id="skill_name" name="skill_name" class="form-control" placeholder="Skills">
        </div>
        <div class="col-12 mb-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        
    </div>
</form>
                              