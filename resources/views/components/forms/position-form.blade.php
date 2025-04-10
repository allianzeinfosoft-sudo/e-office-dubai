<form action="{{ route('recruitments.store-position') }}" method="post" id="formData">
    @csrf
    <div class="row">
        <div class="col mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select class="form-control" name="department_id" id="department_id" data-placeholder="Select Department" required>
                <option value=""></option>
                @if($departments->isNotEmpty())
                    @foreach($departments as $result)
                        <option value="{{ $result->id }}"> {{ $result->department }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="clearfix"></div>
        <div class="col mb-3">
            <label for="designation" class="form-label">Position</label>
            <input type="text" id="designation" name="designation" class="form-control" placeholder="Name">
        </div>
    </div>
</form>
                              