              <div class="card mb-4">
                <form class="card-body" method="post" action="{{ route('leaves.store') }}">
                    @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-username">Leave From</label>
                      <div class="input-group input-group-merge">
                        <input type="date" name="leave_from" class="form-control " placeholder="YYYY-MM-DD" id="flatpickr-date" />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="multicol-email">Leave To</label>
                      <div class="input-group input-group-merge">
                        <input type="date" name="leave_to" class="form-control " placeholder="YYYY-MM-DD" id="flatpickr-date" />

                      </div>
                    </div>
                    <div class="col-md-12">
                         <!-- Full Editor -->
                         <label class="form-label" for="multicol-username">Leave Reason</label>
                                <div class="card">

                                <div class="card-body">
                                    <div id="full-editor">
                                    <p>
                                         content
                                    </p>
                                    </div>
                                </div>
                                </div>
                    </div>

                    <div class="row mt-3">
                        <label class="form-label" for="multicol-username">Leave type(full/half)</label>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input name="default-radio-1" class="form-check-input" type="radio" value="" id="defaultRadio1" />
                                <label class="form-check-label" for="defaultRadio1"> Unchecked </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input name="default-radio-1" class="form-check-input" type="radio" value="" id="defaultRadio2" checked />
                                <label class="form-check-label" for="defaultRadio2"> Checked </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check">
                                <input name="default-radio-1" class="form-check-input" type="radio" value="" id="defaultRadio3" />
                                <label class="form-check-label" for="defaultRadio3"> Checked </label>
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

