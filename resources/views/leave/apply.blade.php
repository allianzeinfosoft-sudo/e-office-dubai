@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
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
                            <input type="hidden" name="leave_id" id="leave_id" value="{{ old('leave_id',$leave->id ?? '') }}">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label" for="leave_from">Leave From<span class="mandatory">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="leave_from" value="{{ old('leave_from', $leave->leave_from ?? '') }}" class="form-control"
                                            placeholder="YYYY-MM-DD" id="leave-from" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="leave_to">Leave To<span class="mandatory">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="leave_to" value="{{ old('leave_to', $leave->leave_to ?? '') }}" class="form-control"
                                            placeholder="YYYY-MM-DD" id="leave-to" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="multicol-username">Leave Reason<span class="mandatory">*</span></label>
                                    <div id="leave-editor"></div>
                                    <input type="hidden" name="reason" value="{{ strip_tags(old('reason' , $leave->reason ?? '')) }}" id="reason">
                                </div>

                                <div class="row mt-3">
                                    <label class="form-label" for="multicol-username">Leave Type (Full/Half)<span class="mandatory">*</span></label>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input name="leave_type" class="form-check-input" type="radio" value="full_day"
                                                id="defaultRadio1" {{ old('leave_type', $leave->leave_type ?? '') == 'full_day' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="defaultRadio1"> Full Day </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input name="leave_type" class="form-check-input" type="radio" value="half_day"
                                                id="defaultRadio2" {{ old('leave_type',$leave->leave_type ?? '') == 'half_day' ? 'checked' : '' }} />
                                            <label class="form-check-label" for="defaultRadio2"> Half Day </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input name="leave_type" class="form-check-input" type="radio" value="off_day"
                                                id="defaultRadio3" {{ old('leave_type',$leave->leave_type ?? '') == 'off_day' ? 'checked' : '' }} />
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

    const hiddenReason = document.getElementById('reason');
    if (hiddenReason && hiddenReason.value) {
        quillLeaveEditor.root.innerHTML = hiddenReason.value;
    }

    document.addEventListener("DOMContentLoaded", function () {
    const Leaveform = document.getElementById('leaveForm');
    Leaveform.addEventListener('submit', function (e) {
        e.preventDefault();

        const userId = document.getElementById('user_id').value.trim();
        const leaveFrom = document.getElementById('leave-from').value.trim();
        const leaveTo = document.getElementById('leave-to').value.trim();
        const reason = quillLeaveEditor.root.innerText.trim();
        const hiddenReason = document.getElementById('reason');
        hiddenReason.value = quillLeaveEditor.root.innerHTML.trim();

        const leaveTypeSelected = document.querySelector('input[name="leave_type"]:checked');

        let errors = [];

        // === Basic Validations ===
        if (!userId) errors.push("User is required.");
        if (!leaveFrom || isNaN(Date.parse(leaveFrom))) errors.push("Valid Leave From date is required.");
        if (!leaveTo || isNaN(Date.parse(leaveTo))) errors.push("Valid Leave To date is required.");
        if (!reason) errors.push("Leave reason is required.");
        if (reason.length > 255) errors.push("Leave reason must not exceed 255 characters.");
        if (!leaveTypeSelected) errors.push("Please select a leave type.");

        if (!isNaN(Date.parse(leaveFrom)) && !isNaN(Date.parse(leaveTo))) {
            const fromDate = new Date(leaveFrom);
            const toDate = new Date(leaveTo);
            if (fromDate > toDate) {
                errors.push("Leave From must be before or equal to Leave To.");
            }
        }

        if (leaveTypeSelected && leaveTypeSelected.value === 'half_day' && leaveFrom !== leaveTo) {
            errors.push("For Half Day leave, 'Leave From' and 'Leave To' must be the same date.");
        }


        // === Display Errors (if any) ===
        let errorBox = document.getElementById('formErrors');
        if (!errorBox) {
            errorBox = document.createElement('div');
            errorBox.id = 'formErrors';
            errorBox.className = 'alert alert-danger mt-3';
            Leaveform.prepend(errorBox);
        }

        if (errors.length > 0) {
            errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            return;
        }

         // === Check if leave allocation exists ===

           const leaveYear = new Date(leaveFrom).getFullYear();
           $.ajax({
            url: `/check-leave-allocation/${userId}?year=${leaveYear}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                // Allocation exists, now check for overlapping dates
                fetch('/check-leave-overlap', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        leave_from: leaveFrom,
                        leave_to: leaveTo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.overlap) {
                        errorBox.innerHTML = '<ul class="mb-0"><li>Leave already applied for the selected date range.</li></ul>';
                    } else {
                        errorBox.innerHTML = '';
                        Leaveform.submit(); // ✅ Final submit
                    }
                })
                .catch(error => {
                    console.error("Overlap check error:", error);
                    errorBox.innerHTML = '<ul class="mb-0"><li>Server error while checking leave overlap.</li></ul>';
                });
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.error || "Leave is not allocated for this user.";
                errorBox.innerHTML = `<ul class="mb-0"><li>${message}</li></ul>`;
            }
        });






    });
});



</script>
@endpush

