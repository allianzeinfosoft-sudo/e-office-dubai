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
                                   <h4 class="fw-bold py-3 mb-0">Create Manual Payroll Batch</h4>
                                   <a href="{{ route('payroll.batches.index') }}" class="btn btn-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Back to List
                                   </a>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <form action="{{ route('payroll.batches.setup-manual') }}" method="POST">
                                             @csrf
                                             <div class="row">
                                                  <div class="col-md-4 mb-3">
                                                       <label class="form-label">Month</label>
                                                       <select name="month" class="form-select" required>
                                                            @for($i = 1; $i <= 12; $i++)
                                                                 <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                                                      {{ date("F", mktime(0, 0, 0, $i, 10)) }}
                                                                 </option>
                                                            @endfor
                                                       </select>
                                                  </div>
                                                  <div class="col-md-4 mb-3">
                                                       <label class="form-label">Year</label>
                                                       <select name="year" class="form-select" required>
                                                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                                                 <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                                                                      {{ $y }}</option>
                                                            @endfor
                                                       </select>
                                                  </div>
                                                   <div class="col-md-9 mb-3">
                                                        <label class="form-label">Salary Structure(s)</label>
                                                        <select name="salary_structure_ids[]" class="form-select select2" multiple data-placeholder="Select Salary Structures (Leave empty for all)">
                                                             @foreach($structures as $structure)
                                                                  <option value="{{ $structure->id }}">{{ $structure->name }}</option>
                                                             @endforeach
                                                        </select>
                                                        <small class="text-muted">Select multiple structures to process them in tabs. Leave empty to process all active structures.</small>
                                                   </div>
                                                   <input type="hidden" name="department_id" value="">
                                                   <div class="col-md-12 mb-3">
                                                       <div class="form-check form-switch mt-2">
                                                           <input class="form-check-input" type="checkbox" name="is_part_wise" id="isPartWise" value="1">
                                                           <label class="form-check-label fw-bold" for="isPartWise">Enable Part-wise Salary Processing (Statutory vs Non-Statutory Split)</label>
                                                       </div>
                                                       <small class="text-muted">If enabled, the payslip will be split into two parts: Part 1 for statutory components and Part 2 for others.</small>
                                                   </div>
                                             </div>
                                             <div class="mt-4">
                                                  <button type="submit" class="btn btn-primary">Next: Enter Payroll Data <i
                                                            class="ti ti-arrow-right ms-1"></i></button>
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

@push('js')
<script>
$(function() {
    if ($('.select2').length) {
        $('.select2').select2({
            allowClear: true
        });
    }
});
</script>
@endpush