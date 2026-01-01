<div>
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
</div>@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('training-tests.store') }}" method="POST" id="training-test-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">

    <div class="row">
         <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="title">Test Title <span class="text-danger">*</span></label>
                  <input type="text" name="title" id="title" class="form-control" placeholder="Test title" required>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="training_id">Training Title <span class="text-danger">*</span></label>
                <select name="training_id" id="training_id" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select Training">
                    <option value=""></option>
                    @foreach ($trainings as $training)
                        <option value="{{ $training->id }}"> {{ $training->training_title  ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="start_date_time">Start Date Time <span class="text-danger">*</span></label>
                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Start date" required>

            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="end_date_time">End Date Time <span class="text-danger">*</span></label>
                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="End date" required />
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="trainings_details">Training Test Details</label>
                <div id="training-test-editor"></div>
                <input type="hidden" name="training_test_details" value="{{ strip_tags(old('training_test_details')) }}" id="training_test_details">
            </div>
        </div>

        <hr>
        <h5 class="mb-3">Training Test Questions</h5>
        <div id="questions-wrapper"></div>

            <button type="button" class="btn btn-sm btn-success mb-3" id="add-question">
                <i class="fa fa-plus"></i> Add Question
            </button>


        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>

<template id="question-template">
    <div class="card mb-3 question-item">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">
                <h6>Question</h6>
                <button type="button" class="btn btn-sm btn-danger remove-question">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <!-- Question -->
            <div class="mb-3">
                <label>Question Title</label>
                <input type="text" name="questions[__INDEX__][title]" class="form-control" placeholder="Enter question">
            </div>

            <!-- Options -->
            <div class="row">
                @for ($i = 1; $i <= 4; $i++)
                <div class="col-md-6 mb-2">
                    <label>Option {{ $i }}</label>
                    <div class="input-group">
                        <div class="input-group-text">
                            <input type="radio" name="questions[__INDEX__][correct_answer]" value="{{ $i }}">
                        </div>
                        <input type="text"
                               name="questions[__INDEX__][options][{{ $i }}]"
                               class="form-control"
                               placeholder="Option {{ $i }}">
                    </div>
                </div>
                @endfor
            </div>

            <!-- Marks -->
            <div class="mt-3">
                <label>Maximum Marks</label>
                <input type="number"
                       name="questions[__INDEX__][marks]"
                       class="form-control"
                       min="1"
                       placeholder="Enter marks">
            </div>

        </div>
    </div>
</template>
@push('js')
<script>

    let questionIndex = 0;

    document.getElementById('add-question').addEventListener('click', function () {
        const template = document.getElementById('question-template').innerHTML;
        const html = template.replace(/__INDEX__/g, questionIndex);
        document.getElementById('questions-wrapper').insertAdjacentHTML('beforeend', html);
        questionIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-question')) {
            e.target.closest('.question-item').remove();
        }
    });


</script>
@endpush
