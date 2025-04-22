<style>
    .hidden{
        display: none;
    }

    .large-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }

    .large-switch .form-check-input:checked {
        background-color: #0d6efd;
    }

    .large-switch .form-check-label {
        font-size: 1.25rem;
        margin-left: 0.5rem;
    }


</style>
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('reminder.store') }}" method="POST" id="reminder-form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="target_id">
    <div class="row">
        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="user">User Name </label>
                <select name="user" id="user" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ (old('appreciant') == $user->id ) ? 'selected' : '' }}>{{ $user->employee->full_name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-check form-switch mb-2 large-switch mt-4">
                <input class="form-check-input" name="repeat_status" type="checkbox" id="flexSwitchCheckChecked">
                <label class="form-check-label" for="flexSwitchCheckChecked">Repeat</label>
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" name="event_name" value="{{ strip_tags(old('event_name')) }}" class="form-control" id="event_name">
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="display_time">Display Time</label>
                <input type="time" name="display_time" id="display_time" class="form-control" placeholder="Display Time" />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Start Date" />
            </div>
        </div>

        {{-- End Date --}}
        <div class="col-sm-6 mb-3 d-none" id="end-date-section">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
        </div>

        {{-- Repeat Mode Select --}}
        <div class="col-sm-6 mb-3 d-none" id="repeat-mode-section">
            <label for="repeat_mode">Repeat Mode</label>
            <select name="repeat_mode" id="repeat_mode" class="form-control">
                @foreach (config('optionsData.reminder_repeat_modes') as $key => $value)
                    <option value="{{ $key }}" {{ old('repeat_mode') == $key ? 'selected' : '' }}>
                        {{ $value ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- WEEKLY --}}
        <div class="col-sm-6 mb-3 d-none" id="weekly-div">
            <div class="form-group">
                <label class="form-label d-block mb-2">Select Weekday</label>
                @foreach (config('optionsData.weekdays', []) as $key => $value)
                    <div class="form-check form-check-inline ">
                        <input type="checkbox" class="form-check-input " id="weekday_{{ $key }}" name="weekdays[]" value="{{ $key }}">
                        <label class="form-check-label" for="weekday_{{ $key }}">{{ $value }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- MONTHLY --}}
        <div class="col-sm-6 mb-3 d-none" id="month-div">
            <div class="form-group">
                <label for="month" class="form-check-label ms-2">In</label>
                <select name="month" id="month" class="form-control">
                    @foreach (config('optionsData.months') as $key => $value)
                        <option value="{{ $key }}" {{ old('month') == $key ? 'selected' : '' }}>
                            {{ $value ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ON DAY 1 --}}
        <div class="col-sm-6 mb-3 d-none" id="onday-one-div">
            <div class="form-group">
                <div class="form-check form-check-inline align-items-center mb-2 ">
                    <input type="radio" name="monthly_type" id="monthly_type1" value="1" class="form-check-input" checked>
                    <label for="monthly_type1" class="form-check-label ms-2">On Day</label>
                </div>

                <select name="onday1" id="onday1" class="form-control ">
                    @for ($counter = 1 ; $counter <= 31 ; $counter++)
                        <option value="{{ $counter }}">{{ $counter }}</option>
                    @endfor
                </select>

            </div>
        </div>

         {{-- ON DAY YEAR --}}
         <div class="col-sm-6 mb-3 d-none" id="onday-year-div">
                    <label for="monthly_type1" class="form-check-label ms-2">On Day</label>
                <select name="on_year_day" id="on_year_day" class="form-control ">
                    @for ($counter = 1 ; $counter <= 31 ; $counter++)
                        <option value="{{ $counter }}">{{ $counter }}</option>
                    @endfor
                </select>

            </div>


        {{-- ON DAY 2 --}}
        <div class="col-sm-6 mb-3 d-none" id="onday-two-div">
            <div class="form-group">
                <div class="form-check form-check-inline align-items-center mb-2">
                    <input type="radio" name="monthly_type" id="monthly_type2" value="2" class="form-check-input">
                    <label for="monthly_type2" class="form-check-label ms-2">On The</label>
                </div>

                <div class="d-flex gap-2">
                    <select name="onday2" id="onday2" class="form-control w-50">
                        @foreach (config('optionsData.weekpostions') as $key => $value)
                            <option value="{{ $key }}">{{ $value ?? 'N/A' }}</option>
                        @endforeach
                    </select>

                    <select name="onday3" id="onday3" class="form-control w-50">
                        @foreach (config('optionsData.weekdays') as $key => $value)
                            <option value="{{ $key }}">{{ $value ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

        {{-- ON DAY 3 --}}
        {{-- <div class="col-sm-3 mb-3 d-none" id="onday-three-div">
            <div class="form-group">
                <div class="form-check form-check-inline align-items-center mb-2">

                </div>

            </div>
        </div> --}}

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="event_description">Event Description</label>
                <textarea name="event_description" id="event_description" class="form-control" rows="5">

                </textarea>
            </div>
        </div>




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


// select repeat modes

    function toggleRepeatModeFields() {
        const mode = $('#repeat_mode').val();

        // Hide all
        $('#weekly-div, #month-div, #onday-one-div, #onday-two-div, #onday-three-div, #onday-year-div').addClass('d-none');

        if (mode === 'weekly') {
            $('#weekly-div').removeClass('d-none');
        } else if (mode === 'monthly') {
            $('#onday-one-div, #onday-two-div, #onday-three-div').removeClass('d-none');
        } else if (mode === 'yearly') {
            $('#month-div, #onday-year-div').removeClass('d-none');
        }
    }

    $(document).ready(function () {
        toggleRepeatModeFields(); // On page load (for old() value)
        $('#repeat_mode').on('change', toggleRepeatModeFields);
    });




    $(document).ready(function () {
        // Initial state check
        toggleRepeatFields();

        // When the switch changes
        $('#flexSwitchCheckChecked').on('change', function () {
            toggleRepeatFields();
        });

        function toggleRepeatFields() {

            $('#repeat-mode-section, #end-date-section, #weekly-div, #month-div, #onday-one-div, #onday-two-div, #onday-three-div, #onday-year-div').addClass('d-none');

            if ($('#flexSwitchCheckChecked').is(':checked')) {
                $('#repeat-mode-section, #end-date-section').removeClass('d-none');
            } else {
                $('#repeat-mode-section, #end-date-section, #weekly-div, #month-div, #onday-one-div, #onday-two-div, #onday-three-div, #onday-year-div').addClass('d-none');
            }
        }
    });
    </script>

    @endpush
