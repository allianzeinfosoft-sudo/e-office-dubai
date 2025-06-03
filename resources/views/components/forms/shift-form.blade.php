                <form class="add-new-record pt-0 row g-2" method="post" action="{{ route('store.workshift') }}" id="form-add-new-shift" onsubmit="return false">
                    @csrf
                    <input type="hidden" name="target_id" id="target_id">
                  <div class="col-sm-12">
                    <label class="form-label" for="shift_id">Shitf ID</label>
                    <div class="input-group input-group-merge">
                      <span id="basicFullname2" class="input-group-text"><i class="ti ti-id"></i></span>
                      <input type="text" id="shift_id" class="form-control dt-shift_id" name="shift_id"/>
                    </div>
                  </div>

                   <div class="col-sm-12">
                    <label class="form-label" for="shift_id">Department</label>
                        <select name="department" id="department" class="select2 form-select form-select-lg" data-allow-clear="true" data-placeholder="Select department">
                            <option value=""></option>
                            <option value="0">General</option>
                            @foreach ($departments as $department)
                                 <option value="{{ $department->id }}">{{ $department->department ?? '' }}</option>
                            @endforeach
                         </select>
                  </div>

                  <div class="col-sm-12">
                    <label class="form-label" for="shift_start_time">Shitf Start Time</label>
                    <div class="input-group input-group-merge">
                      <span id="basicFullname2" class="input-group-text"><i class="ti ti-clock"></i></span>
                      <input type="text" id="shift_start_time" class="form-control dt-shift-start" name="shift_start_time"/>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <label class="form-label" for="shift_end_time">Shift End Time</label>
                    <div class="input-group input-group-merge">
                      <span id="basicPost2" class="input-group-text"><i class="ti ti-clock"></i></span>
                      <input type="text" id="shift_end_time" name="shift_end_time" class="form-control dt-shift-end"  />
                    </div>
                  </div>

                <div class="col-sm-12">
                  <label class="form-label" for="mini_break_time">Mini Break Time</label>
                  <div class="input-group input-group-merge">
                    <span  class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="text" id="mini_break_time" name="mini_break_time" class="form-control dt-min-break" />
                  </div>
                </div>
                <div class="col-sm-12">
                  <label class="form-label" for="max_break_time">Max Break Time</label>
                  <div class="input-group input-group-merge">
                    <span id="basicDate2" class="input-group-text"><i class="ti ti-clock"></i></span>
                    <input type="text" class="form-control dt-max-break" id="max_break_time" name="max_break_time" />
                  </div>
                </div>

                <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
                  <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
              </form>
