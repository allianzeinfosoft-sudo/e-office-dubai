                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
              <div class="card mb-4">
                <form id="leaveForm" class="card-body" method="post" action="{{ route('leaves.store') }}">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                  <div class="row g-3">

                    <div class="col-md-6">
                      <label class="form-label" for="leave_from">Leave From</label>
                      <div class="input-group input-group-merge">
                        <input type="text" name="leave_from" value="{{ old('leave_from') }}" class="form-control" placeholder="YYYY-MM-DD" id="leave-from" />
                      </div>
                    </div>


                    <div class="col-md-6">
                      <label class="form-label" for="leave_to">Leave To</label>
                      <div class="input-group input-group-merge">
                        <input type="text" name="leave_to" value="{{ old('leave_to') }}" class="form-control" placeholder="YYYY-MM-DD" id="leave-to" />
                      </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="multicol-username">Leave Reason</label>
                            <div id="leave-editor">{{ strip_tags(old('reason')) }}                            </div>
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
                                       id="defaultRadio2" {{ old('leave_type', 'half_day') == 'half_day' ? 'checked' : '' }} />
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



<script>
    (function () {
            document.getElementById("leaveForm").addEventListener("submit", function (event) {
                let quill = new Quill("#leave-editor", {
                    theme: "snow"
                });
                document.querySelector("#reason").value = quill.root.innerHTML;
                this.submit(); // Manually submit the form
            });

    })();
</script>
