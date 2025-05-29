
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form class="add-new-record pt-0 row g-2" method="post" action="{{ route('leave_approval_store') }}" id="form-add-leave-approval">
                @csrf

                <div class="col-sm-12">
                    <label class="form-label" for="department">Department</label>
                    <select name="department" id="department" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select Department">
                        <option value=""></option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-sm-12">
                    <label class="form-label" for="approval_level">Approval Level</label>
                    <select name="approval_level" id="approval-level" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select approve level">
                        <option value=""></option>
                       <option value="2">2nd Level</option>
                       <option value="3">3rd Level</option>
                    </select>


                  </div>
                <div class="col-sm-12">
                  <label class="form-label" for="approver">Approver</label>
                  <select name="approver" id="approver" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select approver">
                        <option value=""></option>
                        @foreach ($users as $user)
                            <option value="{{ $user->user_id }}">{{ $user->full_name ?? '' }}</option>
                        @endforeach
                    </select>

                </div>
                {{-- <div class="col-sm-12">
                  <label class="form-label" for="approval count">Approval Count</label>
                  <div class="input-group input-group-merge">
                    <input type="text" id="approve-count" class="form-control dt-approver" name="approve_count" />
                  </div>
                </div> --}}

                <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                  <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
              </form>
