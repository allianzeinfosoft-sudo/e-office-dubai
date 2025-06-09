<form action="{{ route('conferance-hall.store') }}" method="post" id="coferance_hall_form" enctype="multipart/form-data">
    @csrf 
    <input type="hidden" name="id" id="target_id">

    <div class="row">

        <div class="col-sm-4 mb-3">
            <div class="form-group">
                <label for="booking_date">Booking Date <span class="text-danger">*</span></label>
                <input type="date" name="booking_date" id="booking_date" class="form-control" placeholder="Booking Date" value="{{ date('d-m-Y') }}" required />
            </div>
        </div>

        <div class="col-sm-4 mb-3">
            <div class="form-group">
                <label for="start_time">Start Time <span class="text-danger">*</span></label>
                <input type="time" name="start_time" id="start_time" class="form-control" placeholder="Start Time" required />
            </div>
        </div>

        <div class="col-sm-4 mb-3">
            <div class="form-group">
                <label for="end_time">End Time <span class="text-danger">*</span></label>
                <input type="time" name="end_time" id="end_time" class="form-control" placeholder="End Time" required />
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="department_id">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="form-control select2" required>
                    <option value="">Select Employee</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->department }}</option>
                    @endforeach
                </select>
                
            </div>
        </div>

        <div class="col-sm-6 mb-3">
            <div class="form-group">
                <label for="booked_by">Booked by <span class="text-danger">*</span></label>
                <select name="booked_by" id="booked_by" class="form-control select2" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $result)
                        <option value="{{ $result->user_id }}">{{ $result->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="participants">Participence <span class="text-danger">*</span> </label>
                <select name="participants[]" id="participants" class="form-control select2" multiple required>
                    <option value="">Select Employee</option>
                        @foreach($employees as $result)
                        <option value="{{ $result->user_id }}">{{ $result->full_name }}</option>
                        @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="purpose">Purpuse <span class="text-danger">*</span></label>
                <textarea name="purpose" id="purpose" class="form-control"></textarea>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i>&nbsp;&nbsp; Save </button>
        </div>   
    </div>
</form>

@push('js')
    <script>
        $(function(){
            $("#booking_date").flatpickr({
                monthSelectorType: 'static',
                altInput: true,
                altFormat: 'd-m-Y',
                dateFormat: 'd-m-Y',
            });
        })
    </script>
@endpush
