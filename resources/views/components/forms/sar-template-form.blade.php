@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('sartemplate.store') }}" method="POST" id="sar-template-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">
    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="template_name">Template Name <span class="text-danger">*</span></label>
                <input type="text" name="template_name" id="template_name" class="form-control" placeholder="Template Name" require />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="department">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select department">
                    <option value=""></option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->id }}"> {{ $department->department  ?? '' }} </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="question-container"></div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addQuestion()">+ Add Question</button>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>
@push('js')
    <script>
        function previewImage(event) {

        const input = event.target;
        const preview = document.getElementById("PicturePreview");

        if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(input.files[0]);
        }
    }


    // add questions

    let questionIndex = 0;

        function addQuestion() {
            const html = `
            <div class="question-block border rounded p-3 mb-3 position-relative" id="question-block-${questionIndex}">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="removeQuestion(${questionIndex})">
                    Remove
                </button>

                <h5 class="question-title mb-2">Question</h5>

                <input type="text" name="questions[${questionIndex}][question]" class="form-control mb-2" required>

                <label>Answer Type</label>
                <select name="questions[${questionIndex}][answer_type]" class="form-control mb-2" onchange="toggleOptions(this, ${questionIndex})">
                    <option value="yes_no">Yes / No</option>
                    <option value="optional">Optional (4 options)</option>
                    <option value="description">Description</option>
                </select>

                <div id="options-${questionIndex}" class="options-container" style="display:none;">
                    <input type="text" name="questions[${questionIndex}][option1]" placeholder="Option 1" class="form-control mb-1">
                    <input type="text" name="questions[${questionIndex}][option2]" placeholder="Option 2" class="form-control mb-1">
                    <input type="text" name="questions[${questionIndex}][option3]" placeholder="Option 3" class="form-control mb-1">
                    <input type="text" name="questions[${questionIndex}][option4]" placeholder="Option 4" class="form-control mb-1">
                </div>
            </div>
            `;

            document.getElementById('question-container').insertAdjacentHTML('beforeend', html);
            questionIndex++;
            updateQuestionNumbers();
        }

        function removeQuestion(index) {
            const block = document.getElementById(`question-block-${index}`);
            if (block) {
                block.remove();
            }
            updateQuestionNumbers();
        }

        function updateQuestionNumbers() {
            const blocks = document.querySelectorAll('.question-block');
            blocks.forEach((block, i) => {
                const title = block.querySelector('.question-title');
                title.textContent = `Question ${i + 1}`;
            });
        }

        function toggleOptions(select, index) {
            const optionsDiv = document.getElementById(`options-${index}`);
            if (select.value === 'optional') {
                optionsDiv.style.display = 'block';
            } else {
                optionsDiv.style.display = 'none';
            }
        }


</script>
@endpush
