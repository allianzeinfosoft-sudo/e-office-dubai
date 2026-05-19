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
                                        <span class="text-muted fw-light">Payroll /</span> Statutory Settings
                                   </h4>
                              </div>

                              @if(session('success'))
                                   <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                             aria-label="Close"></button>
                                   </div>
                              @endif

                              <div class="row">
                                   <div class="col-md-12">
                                        <form action="{{ route('payroll.settings.update') }}" method="POST">
                                             @csrf
                                             <!-- PF Settings -->
                                             <div class="card mb-4">
                                                  <div class="card-header d-flex justify-content-between align-items-center">
                                                       <h5 class="mb-0"><i class="ti ti-pig me-2"></i> Provident Fund (PF)
                                                            Settings</h5>
                                                       <span class="badge bg-label-primary">EPFO Rules</span>
                                                  </div>
                                                  <div class="card-body">
                                                       <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                 <label class="form-label">Employee Contribution (%)</label>
                                                                 <div class="input-group">
                                                                      <input type="number" step="0.01"
                                                                           name="pf_employee_percent" class="form-control"
                                                                           value="{{ $settings['pf_employee_percent'] }}"
                                                                           required>
                                                                      <span class="input-group-text">%</span>
                                                                 </div>
                                                                 <small class="text-muted">Standard: 12% of Basic +
                                                                      DA</small>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                 <label class="form-label">Employer Contribution (%)</label>
                                                                 <div class="input-group">
                                                                      <input type="number" step="0.01"
                                                                           name="pf_employer_percent" class="form-control"
                                                                           value="{{ $settings['pf_employer_percent'] }}"
                                                                           required>
                                                                      <span class="input-group-text">%</span>
                                                                 </div>
                                                                 <small class="text-muted">Standard: 12% (includes
                                                                      Pension/EPS)</small>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                 <label class="form-label">Wage Limit (₹)</label>
                                                                 <div class="input-group">
                                                                      <span class="input-group-text">₹</span>
                                                                      <input type="number" name="pf_wage_limit"
                                                                           class="form-control"
                                                                           value="{{ $settings['pf_wage_limit'] }}" required>
                                                                 </div>
                                                                 <small class="text-muted">Mandatory if Basic Salary <=
                                                                           ₹15,000</small>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                 <label class="form-label">Employee Calculation Base</label>
                                                                 <select name="pf_employee_base[]" class="form-select select2" multiple required>
                                                                      @foreach($availableOptions as $option)
                                                                           <option value="{{ $option }}" {{ in_array($option, $settings['pf_employee_base']) ? 'selected' : '' }}>{{ $option }}</option>
                                                                      @endforeach
                                                                 </select>
                                                                 <small class="text-muted">Components used for
                                                                      <strong>Deduction</strong> (Calculated from Earned
                                                                      amount).</small>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                 <label class="form-label">Employer Calculation Base</label>
                                                                 <select name="pf_employer_base[]" class="form-select select2" multiple required>
                                                                      @foreach($availableOptions as $option)
                                                                           <option value="{{ $option }}" {{ in_array($option, $settings['pf_employer_base']) ? 'selected' : '' }}>{{ $option }}</option>
                                                                      @endforeach
                                                                 </select>
                                                                 <small class="text-muted">Components used for
                                                                      <strong>Contribution</strong>.</small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                 <div class="form-check form-switch pt-2">
                                                                      <input type="hidden" name="pf_employer_use_fixed"
                                                                           value="0">
                                                                      <input class="form-check-input" type="checkbox"
                                                                           name="pf_employer_use_fixed" value="1"
                                                                           id="pfEmployerFixed" {{ $settings['pf_employer_use_fixed'] ? 'checked' : '' }}>
                                                                      <label class="form-check-label"
                                                                           for="pfEmployerFixed">Calculate Employer PF from
                                                                           <strong>Standard (Fixed) Monthly Rate</strong>
                                                                           instead of Earned amount</label>
                                                                 </div>
                                                                 <small class="text-muted ps-4 d-block">If enabled, LOP will
                                                                      not reduce the employer's PF contribution.</small>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>

                                             <!-- ESI Settings -->
                                             <div class="card mb-4">
                                                  <div class="card-header d-flex justify-content-between align-items-center">
                                                       <h5 class="mb-0"><i class="ti ti-ambulance me-2"></i> Employee State
                                                            Insurance (ESI) Settings</h5>
                                                       <span class="badge bg-label-info">ESIC Rules</span>
                                                  </div>
                                                  <div class="card-body">
                                                       <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                 <label class="form-label">Employee Contribution (%)</label>
                                                                 <div class="input-group">
                                                                      <input type="number" step="0.01"
                                                                           name="esi_employee_percent" class="form-control"
                                                                           value="{{ $settings['esi_employee_percent'] }}"
                                                                           required>
                                                                      <span class="input-group-text">%</span>
                                                                 </div>
                                                                 <small class="text-muted">Current rate: 0.75% of
                                                                      wages</small>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                 <label class="form-label">Employer Contribution (%)</label>
                                                                 <div class="input-group">
                                                                      <input type="number" step="0.01"
                                                                           name="esi_employer_percent" class="form-control"
                                                                           value="{{ $settings['esi_employer_percent'] }}"
                                                                           required>
                                                                      <span class="input-group-text">%</span>
                                                                 </div>
                                                                 <small class="text-muted">Current rate: 3.25% of
                                                                      wages</small>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                 <label class="form-label">Wage Limit (₹)</label>
                                                                 <div class="input-group">
                                                                      <span class="input-group-text">₹</span>
                                                                      <input type="number" name="esi_wage_limit"
                                                                           class="form-control"
                                                                           value="{{ $settings['esi_wage_limit'] }}"
                                                                           required>
                                                                 </div>
                                                                 <small class="text-muted">Applicable if Gross Salary <=
                                                                           ₹21,000</small>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                 <label class="form-label">Employee Calculation Base</label>
                                                                 <select name="esi_employee_base[]" class="form-select select2" multiple required>
                                                                      @foreach($availableOptions as $option)
                                                                           <option value="{{ $option }}" {{ in_array($option, $settings['esi_employee_base']) ? 'selected' : '' }}>{{ $option }}</option>
                                                                      @endforeach
                                                                 </select>
                                                                 <small class="text-muted">Components used for
                                                                      <strong>Deduction</strong> (Earned amount).</small>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                 <label class="form-label">Employer Calculation Base</label>
                                                                 <select name="esi_employer_base[]" class="form-select select2" multiple required>
                                                                      @foreach($availableOptions as $option)
                                                                           <option value="{{ $option }}" {{ in_array($option, $settings['esi_employer_base']) ? 'selected' : '' }}>{{ $option }}</option>
                                                                      @endforeach
                                                                 </select>
                                                                 <small class="text-muted">Components used for
                                                                      <strong>Contribution</strong>.</small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                 <div class="form-check form-switch pt-2">
                                                                      <input type="hidden" name="esi_employer_use_fixed"
                                                                           value="0">
                                                                      <input class="form-check-input" type="checkbox"
                                                                           name="esi_employer_use_fixed" value="1"
                                                                           id="esiEmployerFixed" {{ $settings['esi_employer_use_fixed'] ? 'checked' : '' }}>
                                                                      <label class="form-check-label"
                                                                           for="esiEmployerFixed">Calculate Employer ESI from
                                                                           <strong>Standard (Fixed) Monthly Rate</strong>
                                                                           instead of Earned amount</label>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>

                                             <div class="text-end">
                                                  <button type="submit" class="btn btn-primary btn-lg">
                                                       <i class="ti ti-device-floppy me-1"></i> Save Payroll Settings
                                                  </button>
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
@section('js')
<script>
$(document).ready(function() {
    $('.select2').each(function() {
        $(this).select2({
            placeholder: "Select components",
            dropdownParent: $(this).parent()
        });
    });
});
</script>
@endsection
