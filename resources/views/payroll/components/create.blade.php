@extends('layouts.app')

@section('content')
     <div class="layout-wrapper layout-content-navbar">
          <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
               <x-menu />
               <div class="layout-page">
                    <x-header />
                    <div class="content-wrapper">
                         <div class="container-xxl flex-grow-1 container-p-y">
                              <div class="d-flex justify-content-between align-items-center mb-4">
                                   <h4 class="fw-bold py-3 mb-0">
                                        <a href="{{ route('payroll.components.index') }}" class="text-muted fw-light">Salary
                                             Components /</a> Add New
                                   </h4>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <form action="{{ route('payroll.components.store') }}" method="POST">
                                             @csrf
                                             <div class="row">
                                                  <div class="col-md-6 mb-3">
                                                       <label class="form-label">Component Name</label>
                                                       <input type="text" name="name" class="form-control"
                                                            placeholder="e.g. Basic Salary" required>
                                                  </div>
                                                  <div class="col-md-6 mb-3">
                                                       <label class="form-label">Type</label>
                                                       <select name="type" class="form-select" required>
                                                            <option value="earning">Earning</option>
                                                            <option value="deduction">Deduction</option>
                                                            <option value="employer_contribution">Employer Contribution
                                                            </option>
                                                       </select>
                                                  </div>
                                                  <div class="col-md-3 mb-3">
                                                       <div class="form-check mt-4">
                                                            <input type="hidden" name="is_statutory" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                 name="is_statutory" value="1" id="is_statutory">
                                                            <label class="form-check-label" for="is_statutory">Is
                                                                 Statutory?</label>
                                                       </div>
                                                  </div>
                                                  <div class="col-md-3 mb-3">
                                                       <div class="form-check mt-4">
                                                            <input type="hidden" name="is_variable" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                 name="is_variable" value="1" id="is_variable">
                                                            <label class="form-check-label" for="is_variable">Is
                                                                 Variable?</label>
                                                       </div>
                                                  </div>
                                                  <div class="col-md-3 mb-3">
                                                       <div class="form-check mt-4">
                                                            <input type="hidden" name="is_ctc_variable" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                 name="is_ctc_variable" value="1" id="is_ctc_variable">
                                                            <label class="form-check-label" for="is_ctc_variable">Is CTC
                                                                 Variable?</label>
                                                       </div>
                                                  </div>
                                                  <div class="col-md-3 mb-3">
                                                       <div class="form-check mt-4">
                                                            <input type="hidden" name="is_attendance_based" value="0">
                                                            <input class="form-check-input" type="checkbox"
                                                                 name="is_attendance_based" value="1"
                                                                 id="is_attendance_based" checked>
                                                            <label class="form-check-label" for="is_attendance_based">Is
                                                                 Attendance Based?</label>
                                                       </div>
                                                  </div>
                                                  <div class="col-md-3 mb-3">
                                                       <div class="form-check mt-4">
                                                            <input type="hidden" name="status" value="0">
                                                            <input class="form-check-input" type="checkbox" name="status"
                                                                 value="1" id="status" checked>
                                                            <label class="form-check-label" for="status">Active</label>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="mt-4">
                                                  <button type="submit" class="btn btn-primary me-2">Save Component</button>
                                                  <a href="{{ route('payroll.components.index') }}"
                                                       class="btn btn-label-secondary">Cancel</a>
                                             </div>
                                        </form>
                                   </div>
                              </div>
                         </div>
                         <x-footer />
                    </div>
               </div>
          </div>
     </div>
@endsection