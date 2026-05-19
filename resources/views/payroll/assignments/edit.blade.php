@extends('layouts.app')
@section('css')
<style>
     input[readonly] {
          background-color: #f8f9fa !important;
          cursor: not-allowed;
     }
     .drag-handle {
          cursor: move;
          color: #d1d1d1;
     }
     .drag-handle:hover {
          color: #6c757d;
     }
     .sortable-ghost {
          opacity: 0.4;
          background-color: #f8f9fa !important;
     }
</style>
@endsection

@section('content')
<div class="layout-wrapper layout-content-navbar">
     <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
          <x-menu />
          <div class="layout-page">
               <x-header />
               <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                         <h4 class="fw-bold py-3 mb-4">
                              <a href="{{ route('payroll.assignments.index') }}" class="text-muted fw-light">Salary
                                   Assignments /</a> Edit Assignment
                         </h4>

                         <div class="row">
                              <div class="col-md-10 mx-auto">
                                   <div class="card">
                                        <div class="card-body">
                                             @if($isLocked)
                                                 <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                                     <span class="alert-icon text-danger me-2">
                                                         <i class="ti ti-ban ti-xs"></i>
                                                     </span>
                                                     <div>
                                                         <strong>This assignment is locked because payroll has already been approved for this period.</strong> Please create a new assignment with a later effective date instead.
                                                     </div>
                                                 </div>
                                             @endif
                                             <form id="assignment-form" action="{{ route('payroll.assignments.update', $assignment->id) }}"
                                                  method="POST">
                                                  @csrf
                                                  @method('PUT')
                                                  <div class="row g-3">
                                                       <div class="col-md-6">
                                                            <label class="form-label" for="employee_id">Employee</label>
                                                            <select id="employee_id" name="employee_id"
                                                                 class="select2 form-select @error('employee_id') is-invalid @enderror"
                                                                 data-allow-clear="true" required disabled>
                                                                 <option value="{{ $assignment->employee_id }}"
                                                                      selected>
                                                                      {{ $assignment->employee->full_name }}
                                                                      ({{ $assignment->employee->employeeID }})
                                                                 </option>
                                                            </select>
                                                            <!-- Hidden fields -->
                                                            <input type="hidden" id="assignment_employee_id" name="employee_id"
                                                                 value="{{ $assignment->employee_id }}">
                                                            <input type="hidden" name="base_amount" id="base_amount"
                                                                 value="{{ $assignment->base_amount }}">
                                                       </div>
                                                       <div class="col-md-6">
                                                            <label class="form-label" for="salary_structure_id">Salary
                                                                 Structure</label>
                                                            <select id="salary_structure_id" name="salary_structure_id"
                                                                 class="select2 form-select @error('salary_structure_id') is-invalid @enderror"
                                                                 data-allow-clear="true" required>
                                                                 <option value="">Select Structure</option>
                                                                 @foreach($structures as $structure)
                                                                 <option value="{{ $structure->id }}" {{
                                                                      old('salary_structure_id', $assignment->
                                                                      salary_structure_id) == $structure->id ?
                                                                      'selected' : '' }}>
                                                                      {{ $structure->name }}
                                                                 </option>
                                                                 @endforeach
                                                            </select>
                                                       </div>
                                                       <div class="col-md-6">
                                                            <label class="form-label" for="effective_date">Effective
                                                                 Date</label>
                                                            <input type="date"
                                                                 class="form-control @error('effective_date') is-invalid @enderror"
                                                                 id="effective_date" name="effective_date"
                                                                 value="{{ old('effective_date', $assignment->effective_date?->format('Y-m-d')) }}"
                                                                 required />
                                                       </div>

                                                       <div class="col-md-6">
                                                            <label class="form-label" for="monthly_ctc">Monthly
                                                                 CTC</label>
                                                            <div class="input-group">
                                                                 <span class="input-group-text">₹</span>
                                                                 <input type="number" step="0.01"
                                                                      class="form-control @error('monthly_ctc') is-invalid @enderror"
                                                                      id="monthly_ctc" name="monthly_ctc"
                                                                      value="{{ old('monthly_ctc', $assignment->monthly_ctc) }}"
                                                                      required />
                                                            </div>
                                                       </div>
                                                       <div class="col-md-6">
                                                            <label class="form-label" for="annual_ctc">Annual
                                                                 CTC</label>
                                                            <div class="input-group">
                                                                 <span class="input-group-text">₹</span>
                                                                 <input type="number" step="0.01"
                                                                      class="form-control @error('annual_ctc') is-invalid @enderror"
                                                                      id="annual_ctc" name="annual_ctc"
                                                                      value="{{ old('annual_ctc', $assignment->annual_ctc) }}"
                                                                      readonly />
                                                            </div>
                                                       </div>
                                                       <div class="col-md-6">
                                                            <label class="form-label" for="status">Status</label>
                                                            <select name="status" id="status" class="form-select">
                                                                 <option value="1" {{ $assignment->status ? 'selected' :
                                                                      '' }}>Active</option>
                                                                 <option value="0" {{ !$assignment->status ? 'selected'
                                                                      : '' }}>Inactive</option>
                                                            </select>
                                                       </div>

                                                       <div class="col-md-3">
                                                            <label class="form-label" for="pf_eligible">PF
                                                                 Eligible</label>
                                                            <div class="d-flex align-items-center mt-2">
                                                                 <span class="me-2">No</span>
                                                                 <div class="form-check form-switch mb-0">
                                                                      <input class="form-check-input" type="checkbox"
                                                                           id="pf_eligible" name="pf_eligible" value="1"
                                                                           {{ old('pf_eligible',
                                                                           $assignment->pf_eligible) ? 'checked' : ''
                                                                      }}>
                                                                 </div>
                                                                 <span class="ms-2">Yes</span>
                                                            </div>
                                                       </div>
                                                       <div class="col-md-3">
                                                            <label class="form-label" for="esi_eligible">ESI
                                                                 Eligible</label>
                                                            <div class="d-flex align-items-center mt-2">
                                                                 <span class="me-2">No</span>
                                                                 <div class="form-check form-switch mb-0">
                                                                      <input class="form-check-input" type="checkbox"
                                                                           id="esi_eligible" name="esi_eligible"
                                                                           value="1" {{ old('esi_eligible',
                                                                           $assignment->esi_eligible) ? 'checked' : ''
                                                                      }}>
                                                                 </div>
                                                                 <span class="ms-2">Yes</span>
                                                            </div>
                                                       </div>
                                                  </div>

                                                  <div id="components-container" class="mt-4">
                                                       <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h5 class="mb-0">Salary Components</h5>
                                                            <small class="text-muted"><i class="ti ti-info-circle me-1"></i> Drag and drop components to reorder</small>
                                                       </div>
                                                       <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                 <thead>
                                                                      <tr>
                                                                           <th width="40"></th>
                                                                           <th>Component</th>
                                                                           <th>Type</th>
                                                                           <th>Value</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody id="components-body">
                                                                      @foreach($allComponents as $idx => $comp)
                                                                      @php
                                                                      $lowercaseName = strtolower($comp->name);
                                                                      $isPF = str_contains($lowercaseName, 'pf');
                                                                      $isESI = str_contains($lowercaseName, 'esi');
                                                                      $isBasic = str_contains($lowercaseName, 'basic')
                                                                      || $lowercaseName === 'da' ||
                                                                      str_contains($lowercaseName, 'basic+da');
                                                                      $isStatutory = $isPF || $isESI ||
                                                                      $comp->is_statutory;
                                                                      $isReadOnly = $isPF || $isESI;
                                                                      @endphp
                                                                      <tr
                                                                           class="{{ $isBasic ? 'table-primary font-weight-bold' : '' }}" data-id="{{ $comp->id }}">
                                                                           <td class="text-center align-middle">
                                                                                <i class="ti ti-drag-drop drag-handle fs-4"></i>
                                                                                <input type="hidden" name="components[{{ $comp->id }}][sort_order]" class="sort-order-input" value="{{ $idx }}">
                                                                           </td>
                                                                           <td>
                                                                                {{ $comp->name }} {{ $isBasic ?
                                                                                '(Basis)' : '' }}
                                                                                <input type="hidden"
                                                                                     name="components[{{ $comp->id }}][id]"
                                                                                     value="{{ $comp->id }}">
                                                                           </td>
                                                                           <td><span class="badge bg-label-info">{{
                                                                                     str_replace('_', ' ', $comp->type)
                                                                                     }}</span></td>
                                                                           <td>
                                                                                <input type="text"
                                                                                     class="form-control component-amount {{ $isBasic ? 'base-basis-input' : '' }}"
                                                                                     name="components[{{ $comp->id }}][amount]"
                                                                                     value="{{ $savedAmounts[$comp->id] ?? $comp->pivot->amount }}"
                                                                                     data-id="{{ $comp->id }}"
                                                                                     data-statutory="{{ $isStatutory }}"
                                                                                     data-pf="{{ $isPF }}"
                                                                                     data-esi="{{ $isESI }}"
                                                                                     data-basic="{{ $isBasic }}" {{
                                                                                     $isReadOnly ? 'readonly' : '' }}
                                                                                     required>
                                                                           </td>
                                                                      </tr>
                                                                      @endforeach
                                                                 </tbody>
                                                            </table>
                                                       </div>
                                                  </div>

                                                  <div class="mt-4 d-flex justify-content-between align-items-center">
                                                       <div>
                                                           <button type="submit" class="btn btn-primary me-2" {{ $isLocked ? 'disabled' : '' }}>Update Assignment</button>
                                                           <a href="{{ route('payroll.assignments.index') }}" class="btn btn-label-secondary">Cancel</a>
                                                       </div>
                                                       <button type="button" class="btn btn-icon btn-label-primary" id="btn-update-statutory" data-bs-toggle="tooltip" title="Update Statutory & Bank Details">
                                                           <i class="ti ti-settings fs-4"></i>
                                                       </button>
                                                  </div>
                                             </form>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
                    <x-footer />
               </div>
          </div>
     </div>
</div>

<!-- Offcanvas for Statutory Details -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasStatutory" aria-labelledby="offcanvasStatutoryLabel">
    <div class="offcanvas-header bg-primary py-3">
         <h5 id="offcanvasStatutoryLabel" class="offcanvas-title text-white">Update Employee Details</h5>
         <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
         <form id="statutory-details-form">
              <div class="row g-3">
                  <div class="col-12"><h6 class="mb-0 border-bottom pb-2">Statutory Numbers</h6></div>
                  <div class="col-md-6">
                      <label class="form-label">ESI No</label>
                      <input type="text" name="esi_no" class="form-control" placeholder="ESI Number">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">PF No</label>
                      <input type="text" name="pf_no" class="form-control" placeholder="PF Number">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">UAN No</label>
                      <input type="text" name="uan_no" class="form-control" placeholder="UAN Number">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">WWF No</label>
                      <input type="text" name="wwf_no" class="form-control" placeholder="WWF Number">
                  </div>

                  <div class="col-12 mt-4"><h6 class="mb-0 border-bottom pb-2">Bank Information</h6></div>
                  <div class="col-md-12">
                      <label class="form-label">Bank Name</label>
                      <input type="text" name="bank_name" class="form-control" placeholder="Bank Name">
                  </div>
                  <div class="col-md-12">
                      <label class="form-label">Bank Branch</label>
                      <input type="text" name="bank_branch" class="form-control" placeholder="Branch">
                  </div>
                  <div class="col-md-12">
                      <label class="form-label">Beneficiary Name</label>
                      <input type="text" name="beneficiary_name" class="form-control" placeholder="Beneficiary Name">
                  </div>
                  <div class="col-md-12">
                      <label class="form-label">Account Number</label>
                      <input type="text" name="account_number" class="form-control" placeholder="Account Number">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">IFSC</label>
                      <input type="text" name="ifsc" class="form-control" placeholder="IFSC Code">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Transaction Type</label>
                      <input type="text" name="transaction_type" class="form-control" placeholder="NEFT, RTGS, etc.">
                  </div>
              </div>
              <div class="mt-4 pt-2">
                  <button type="submit" class="btn btn-primary w-100" id="btn-save-statutory">
                      <span class="spinner-border spinner-border-sm me-1 d-none" role="status"></span>
                      Save Employee Details
                  </button>
              </div>
         </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
     $(document).ready(function () {
          if ($('.select2').length) {
               $('.select2').each(function () {
                    $(this).select2({
                         placeholder: 'Select an option',
                         allowClear: true,
                         dropdownParent: $(this).parent()
                    });
               });
          }

          // Initialize Sortable
          const sortable = new Sortable(document.getElementById('components-body'), {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    updateSortOrders();
                }
          });

          function updateSortOrders() {
                $('#components-body tr').each(function(index) {
                    $(this).find('.sort-order-input').val(index);
                });
          }

          let isInitialLoad = true;
          let currentEmployee = null;
          const employeeId = $('#assignment_employee_id').val();

          if (employeeId) {
                               function checkLastProcessed(showToast = false) {
                    const employeeId = $('#employee_id').val() || window.employeeId; // ensure employeeId is available
                    $.get(`/payroll/assignments/get-last-processed-date/${employeeId}`, function (data) {
                         if (data.last_processed_date) {
                              const nextDay = new Date(data.last_processed_date);
                              nextDay.setDate(nextDay.getDate() + 1);
                              const minDate = nextDay.toISOString().split('T')[0];
                              
                              $('#effective_date').attr('min', minDate);
                              const nextMonthStr = nextDay.toLocaleString('default', { month: 'long', year: 'numeric' });
                              validateEffectiveDate(showToast);
                              if (showToast && $('#effective_date').val() < minDate) {
                                   toastr.warning('Payrole alredy procced on ' + data.formatted_date + ' for employee. you need to change effective date ' + nextMonthStr + '.');
                              }
                         } else {
                              $('#effective_date').removeAttr('min');
                              $('button[type="submit"]').prop('disabled', false);
                         }
                    });
                }
                
                checkLastProcessed(false); // Quiet on load

                function validateEffectiveDate(showToast = false) {
                    const effectiveDate = $('#effective_date').val();
                    const minDate = $('#effective_date').attr('min');
                    const btn = $('button[type="submit"]');
                    
                    if (minDate && effectiveDate < minDate) {
                        btn.prop('disabled', true);
                        if (showToast) {
                            const lastDate = new Date(minDate);
                            lastDate.setMonth(lastDate.getMonth() - 1);
                            const lastProcessedStr = lastDate.toLocaleString('default', { month: 'long', year: 'numeric' });
                            const nextMonthStr = new Date(minDate).toLocaleString('default', { month: 'long', year: 'numeric' });
                            toastr.error('Payrole alredy procced on ' + lastProcessedStr + ' for employee. you need to change effective date ' + nextMonthStr + '.');
                        }
                    } else {
                        btn.prop('disabled', false);
                    }
                }

                $('#effective_date').on('change', function() {
                    validateEffectiveDate(true); // Show toast if manually changed to invalid date
                });

                $.get(`/payroll/assignments/get-employee-assignment/${employeeId}`, function (data) {
                    currentEmployee = data.employee;
               });
          }

          function updateAnnualCTC() {
               const monthly = parseFloat($('#monthly_ctc').val()) || 0;
               const annual = (monthly * 12).toFixed(2);
               $('#annual_ctc').val(annual);
          }

          $('#monthly_ctc').on('input change keyup blur', function () {
               updateAnnualCTC();
          });

          // Initial calculation
          updateAnnualCTC();

          $('#pf_eligible').on('change', function () {
               const baseAmount = parseFloat($('#base_amount').val()) || 0;
               if ($(this).is(':checked') && currentEmployee && !currentEmployee.pf_no) {
                    toastr.error('Selected employee PF details need to update');
                    $(this).prop('checked', false);
                    return;
               }
               if (currentComponents.length > 0) {
                    recalculateComponents(baseAmount);
               }
          });

          $('#esi_eligible').on('change', function () {
               const baseAmount = parseFloat($('#base_amount').val()) || 0;
               if ($(this).is(':checked') && currentEmployee && !currentEmployee.esi_no) {
                    toastr.error('Selected employee ESI details need to update');
                    $(this).prop('checked', false);
                    return;
               }
               if (currentComponents.length > 0) {
                    recalculateComponents(baseAmount);
               }
          });

          // Offcanvas opening logic
          $('#btn-update-statutory').on('click', function() {
               if (!employeeId) {
                   toastr.warning('Please select an employee first');
                   return;
               }
               if (!currentEmployee) {
                   toastr.error('Employee data not loaded yet. Please wait a moment.');
                   return;
               }

               // Populate fields
               const form = $('#statutory-details-form');
               form.find('[name="esi_no"]').val(currentEmployee.esi_no);
               form.find('[name="pf_no"]').val(currentEmployee.pf_no);
               form.find('[name="uan_no"]').val(currentEmployee.uan_no);
               form.find('[name="wwf_no"]').val(currentEmployee.wwf_no);
               form.find('[name="bank_name"]').val(currentEmployee.bank_name);
               form.find('[name="bank_branch"]').val(currentEmployee.bank_branch);
               form.find('[name="beneficiary_name"]').val(currentEmployee.beneficiary_name);
               form.find('[name="account_number"]').val(currentEmployee.account_number);
               form.find('[name="ifsc"]').val(currentEmployee.ifsc);
               form.find('[name="transaction_type"]').val(currentEmployee.transaction_type || 'NEFT');

               const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasStatutory'));
               offcanvas.show();
          });

          // Statutory Save logic
          $('#statutory-details-form').on('submit', function(e) {
               e.preventDefault();
               const formData = $(this).serialize();
               const btn = $('#btn-save-statutory');
               const spinner = btn.find('.spinner-border');

               btn.prop('disabled', true);
               spinner.removeClass('d-none');

               $.ajax({
                   url: `/payroll/assignments/update-statutory/${employeeId}`,
                   method: 'POST',
                   data: formData,
                   headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                   success: function(response) {
                       if (response.success) {
                           toastr.success(response.message);
                           currentEmployee = response.employee;
                           bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasStatutory')).hide();
                       }
                   },
                   error: function(xhr) {
                       toastr.error('Failed to update employee details');
                       console.error(xhr);
                   },
                   complete: function() {
                       btn.prop('disabled', false);
                       spinner.addClass('d-none');
                   }
               });
          });

          $('#salary_structure_id').on('change', function () {
               const structureId = $(this).val();
               const baseAmount = parseFloat($('#base_amount').val()) || 0;
               if (structureId) { 
                    fetchComponents(structureId, baseAmount);
                    if (employeeId) {
                         // Reuse the logic from page load
                         checkLastProcessed(true); // Show toast on structure change
                    }
               } else { $('#components-container').hide(); }
          });

          $('#base_amount').on('input', function () {
               const structureId = $('#salary_structure_id').val();
               const baseAmount = parseFloat($(this).val()) || 0;
               if (structureId) { recalculateComponents(baseAmount); }
          });

          let currentSettings = @json($settings);
          let currentComponents = @json($allComponents);

          function fetchComponents(structureId, baseAmount) {
               $.get(`/payroll/assignments/get-structure-components/${structureId}`, function (data) {
                    currentSettings = data.settings;
                    currentComponents = data.components;
                    renderComponents(baseAmount);
               });
          }

          function renderComponents(baseAmount) {
               const body = $('#components-body');
               body.empty();
               const pfEligible = $('#pf_eligible').is(':checked');
               const esiEligible = $('#esi_eligible').is(':checked');

               currentComponents.forEach((comp, index) => {
                    const lowercaseName = comp.name.toLowerCase();
                    const isPF = lowercaseName.includes('pf');
                    const isESI = lowercaseName.includes('esi');
                    const isBasic = lowercaseName.includes('basic') || lowercaseName === 'da' || lowercaseName.includes('basic+da');
                    const isStatutory = isPF || isESI || comp.is_statutory == 1;

                    let amount = 0;
                    let isReadOnly = false;

                    if (isPF) {
                         if (pfEligible) {
                              const limit = currentSettings.pf_wage_limit || 15000;
                              const percent = (lowercaseName.includes('employer') || lowercaseName.includes('emp.contri')) ? currentSettings.pf_employer_percent : currentSettings.pf_employee_percent;
                              amount = (Math.min(baseAmount, limit) * (percent / 100)).toFixed(2);
                         }
                         isReadOnly = true; isEditableChecked = false;
                    } else if (isESI) {
                         isReadOnly = true; isEditableChecked = false;
                    } else if (isBasic) {
                         amount = baseAmount;
                    } else {
                         if (comp.pivot && (comp.pivot.calculation_type === 'percentage' || comp.pivot.calculation_type === 'earned_percentage')) {
                              amount = (baseAmount * (comp.pivot.value / 100)).toFixed(2);
                         } else if (comp.pivot) { amount = comp.pivot.value; }
                    }

                    const row = `
                              <tr class="${isBasic ? 'table-primary font-weight-bold' : ''}" data-id="${comp.id}">
                                   <td class="text-center align-middle">
                                        <i class="ti ti-drag-drop drag-handle fs-4"></i>
                                        <input type="hidden" name="components[${comp.id}][sort_order]" class="sort-order-input" value="${index}">
                                   </td>
                                   <td>${comp.name} ${isBasic ? '<strong>(Basis)</strong>' : ''}<input type="hidden" name="components[${comp.id}][id]" value="${comp.id}"></td>
                                   <td><span class="badge bg-label-info">${comp.type.replace('_', ' ')}</span></td>
                                   <td><input type="text" class="form-control component-amount ${isBasic ? 'base-basis-input' : ''}" name="components[${comp.id}][amount]" value="${amount}" data-id="${comp.id}" data-statutory="${isStatutory}" data-pf="${isPF}" data-esi="${isESI}" data-basic="${isBasic}" ${isReadOnly ? 'readonly' : ''} required></td>
                              </tr> `;
                    body.append(row);
               });
               $('#components-container').show();
               recalculateComponents(baseAmount);
               updateSortOrders();
          }

          function calculateConsolidatedCTC() {
               let total = 0;
               const baseAmount = parseFloat($('#base_amount').val()) || 0;
               total += baseAmount;
               $('.component-amount').each(function () {
                    const input = $(this);
                    const amount = parseFloat(input.val()) || 0;
                    if (input.data('basic')) return;

                    const compId = input.data('id');
                    const comp = currentComponents.find(c => c.id == compId);

                    if (comp && (comp.type === 'earning' || comp.type === 'employer_contribution')) {
                         total += amount;
                    }
               });
               $('#monthly_ctc').val(total.toFixed(2));
               updateAnnualCTC();
               $('#monthly_ctc').trigger('change').trigger('input');
          }

          function recalculateComponents(baseAmount) {
               const pfEligible = $('#pf_eligible').is(':checked');
               const esiEligible = $('#esi_eligible').is(':checked');

               let grossForESI = baseAmount;
               $('.component-amount').each(function () {
                    const input = $(this);
                    if (input.data('basic')) {
                        if (!input.is(':focus') && !isInitialLoad) input.val(baseAmount);
                        return;
                    }
                    const compId = input.data('id');
                    const comp = currentComponents.find(c => c.id == compId);
                    if (!comp || comp.type !== 'earning' || input.data('esi') || input.data('pf')) return;

                    if (!isInitialLoad) {
                         if (comp.pivot && (comp.pivot.calculation_type === 'percentage' || comp.pivot.calculation_type === 'earned_percentage')) {
                              let calcAmount = (baseAmount * (comp.pivot.value / 100)).toFixed(2);
                              input.val(calcAmount);
                         }
                    }
                    grossForESI += parseFloat(input.val()) || 0;
               });

               $('.component-amount').each(function () {
                    const input = $(this);
                    const compId = input.data('id');
                    const isPF = input.data('pf');
                    const isESI = input.data('esi');
                    const comp = currentComponents.find(c => c.id == compId);
                    if (!comp || (input.is(':focus') && input.data('basic'))) return;

                    if (!isInitialLoad) {
                         let amount = 0;
                         if (isPF) {
                              if (pfEligible) {
                                   const limit = currentSettings.pf_wage_limit || 15000;
                                   const lowercaseName = comp.name.toLowerCase();
                                   const percent = (lowercaseName.includes('employer') || lowercaseName.includes('emp.contri')) ? currentSettings.pf_employer_percent : currentSettings.pf_employee_percent;
                                   amount = (Math.min(baseAmount, limit) * (percent / 100)).toFixed(2);
                              }
                              input.val(amount);
                         } else if (isESI) {
                              if (esiEligible) {
                                   const limit = currentSettings.esi_wage_limit || 21000;
                                   const lowercaseName = comp.name.toLowerCase();
                                   const percent = (lowercaseName.includes('employer') || lowercaseName.includes('emp.contri')) ? currentSettings.esi_employer_percent : currentSettings.esi_employee_percent;
                                   amount = (grossForESI > limit) ? 0 : (grossForESI * (percent / 100)).toFixed(2);
                              }
                              input.val(amount);
                         }
                    }
               });

               if (!isInitialLoad) {
                    calculateConsolidatedCTC();
               }
          }

          $(document).on('input', '.component-amount', function () {
               const input = $(this);
               if (input.data('basic')) { $('#base_amount').val(input.val()).trigger('input'); }
               else { calculateConsolidatedCTC(); }
          });

          // Form submission validation
          $('#assignment-form').on('submit', function(e) {
               const effectiveDate = $('#effective_date').val();
               const minDate = $('#effective_date').attr('min');
               if (minDate && effectiveDate < minDate) {
                    e.preventDefault();
                    const nextDate = new Date(minDate);
                    const lastDate = new Date(minDate);
                    lastDate.setMonth(lastDate.getMonth() - 1);
                    const lastProcessedDateStr = lastDate.toLocaleString('default', { month: 'long', year: 'numeric' });
                    const nextMonthStr = nextDate.toLocaleString('default', { month: 'long', year: 'numeric' });
                    
                    toastr.error('Payrole alredy procced on ' + lastProcessedDateStr + ' for employee. you need to change effective date ' + nextMonthStr + '.');
                    return false;
               }
          });

          // Prevent form submission on Enter key
          $('#assignment-form').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    if (e.target.tagName !== 'BUTTON') {
                        e.preventDefault();
                        return false;
                    }
                }
          });

          // End of initialization
          setTimeout(() => { isInitialLoad = false; }, 500);
     });
</script>
@endsection