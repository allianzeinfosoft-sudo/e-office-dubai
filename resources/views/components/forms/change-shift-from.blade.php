@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('update.user.shift') }}" method="POST" id="chagne-shift-form" enctype="multipart/form-data" style="height:500px !important;">
    @csrf

    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="user">Select User</label>
                <select name="user" id="user" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select User">
                    <option value=""></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id  ?? '' }}">{{ $user->full_name ?? '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="shift">Shift Time</label>
                <select name="shift" id="shift" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select Shift">
                    <option value=""></option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id  ?? '' }}">{{ $shift->shift_id ?? '' }} [ {{ $shift->shift_start_time ?? '' }} - {{ $shift->shift_end_time ?? '' }} ( {{ $shift->shift_department ? $shift->shift_department->department : '' }}) ] </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-12 mb-3">
            <div class="form-group">
                <label for="login_limited_time">Login Limited Time</label>
                <button type="button" class="btn btn-icon btn-xs btn-outline-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addLoginLimitedTimeModal">
                    <span class="ti ti-plus text-xs" style="font-size: 12px;"></span>
                </button>

                <select name="login_limited_time" id="login_limited_time" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select login limited time">
                    @foreach($loginlimitedtimes as $login_limited_time)
                        <option value="{{ $login_limited_time->id  ?? '' }}"> {{ $login_limited_time->limited_time ?? '' }} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 mb-3">
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
        </div>
    </div>
</form>



<!-- Add New Department Modal -->
<div class="modal fade" id="addLoginLimitedTimeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Add New Login Limited Time</h3>
          </div>
          <form id="add-branch-form" action="{{ route('save.login_limited_time') }}" method="POST" class="row g-3" >
            @csrf
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCard">Login Limited Time</label>
                <div class="input-group input-group-merge">
                  <input id="login_limited_time" name="login_limited_time" class="form-control" type="time" placeholder="Choose Time" aria-describedby="login_limited_time" />
                </div>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

