@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <x-menu /> <!-- Load the menu component here -->

      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <x-header />
        <!-- / Navbar -->

        <!-- Content wrapper -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Apply Leave</h4>

                    <div class="card mb-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="leaveForm" class="card-body" method="POST" action="{{ route('leaves.store') }}">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="leave_from">Leave From</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="leave_from" value="{{ old('leave_from') }}" class="form-control"
                                            placeholder="YYYY-MM-DD" id="leave-from" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="leave_to">Leave To</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="leave_to" value="{{ old('leave_to') }}" class="form-control"
                                            placeholder="YYYY-MM-DD" id="leave-to" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="multicol-username">Leave Reason</label>
                                    <div id="leave-editor"></div>
                                    <input type="hidden" name="reason" value="{{ strip_tags(old('reason')) }}" id="reason">
                                </div>

                                <div class="row mt-3">
                                    <label class="form-label" for="multicol-username">Leave Type (Full/Half)</label>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input name="leave_type" class="form-check-input" type="radio" value="full_day"
                                                id="defaultRadio1" {{ old('leave_type') == 'full_day' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="defaultRadio1"> Full Day </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input name="leave_type" class="form-check-input" type="radio" value="half_day"
                                                id="defaultRadio2" {{ old('leave_type') == 'half_day' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="defaultRadio2"> Half Day </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input name="leave_type" class="form-check-input" type="radio" value="off_day"
                                                id="defaultRadio3" {{ old('leave_type') == 'off_day' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="defaultRadio3"> Off Day </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>



          </div>

          <!-- / Content -->

          <!-- Footer -->
          <x-footer />
          <!-- / Footer -->
          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->
@endsection

@push('js')
<script>
    var quillLeaveEditor = new Quill('#leave-editor',
    { theme: 'snow',
        placeholder: 'Type your reason here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
    });

    document.addEventListener("DOMContentLoaded", function () {
    const Leaveform = document.getElementById('leaveForm');
    Leaveform.addEventListener('submit', function (e) {
            e.preventDefault();
            // Get values
            const userId = document.getElementById('user_id').value.trim();
            const leaveFrom = document.getElementById('leave-from').value.trim();
            const leaveTo = document.getElementById('leave-to').value.trim();
            const reason = quillLeaveEditor.root.innerText.trim(); // Plain text
            const hiddenReason = document.getElementById('reason');
            hiddenReason.value = quillLeaveEditor.root.innerHTML.trim(); // Store HTML in hidden field

            let errors = [];

            // === Validation ===
            if (!userId) {
                errors.push("User is required.");
            }

            if (!leaveFrom) {
                errors.push("Leave From date is required.");
            } else if (isNaN(Date.parse(leaveFrom))) {
                errors.push("Leave From must be a valid date.");
            }

            if (!leaveTo) {
                errors.push("Leave To date is required.");
            } else if (isNaN(Date.parse(leaveTo))) {
                errors.push("Leave To must be a valid date.");
            }

            if (!isNaN(Date.parse(leaveFrom)) && !isNaN(Date.parse(leaveTo))) {
                let fromDate = new Date(leaveFrom);
                let toDate = new Date(leaveTo);
                if (fromDate > toDate) {
                    errors.push("Leave From must be before or equal to Leave To.");
                }
            }

            if (reason.length > 255) {
                errors.push("Leave reason must not exceed 255 characters.");
            }

            if (!reason) {
                errors.push("Leave reason is required");
            }

            const leaveTypeSelected = document.querySelector('input[name="leave_type"]:checked');
            if (!leaveTypeSelected) {
                errors.push("Please select a leave type.");
            }



            // === Show errors or submit ===
            let errorBox = document.getElementById('formErrors');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'formErrors';
                errorBox.className = 'alert alert-danger mt-3';
                Leaveform.prepend(errorBox);
            }

            if (errors.length > 0) {
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else {
                errorBox.innerHTML = ''; // Clear old errors
                Leaveform.submit(); // Submit manually only if no errors
            }
        });
    });


</script>
@endpush

