@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('tools.quick-note.store') }}" method="POST" id="quick_note_form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="title">Quick Note Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Title" require />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="note_description">Discriptions <span class="text-danger">*</span></label>
                <div id="note_description_editor"></div>
                <input type="hidden" name="note_description" value="{{ strip_tags(old('note_description')) }}" id="note_description">
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="assigned_to">Assigned To</label>
                <select name="assigned_to" id="assigned_to" class="form-control select2">
                    <option value="">Select Employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->user_id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>

@push('js')
    <script>
       

    </script>
@endpush
