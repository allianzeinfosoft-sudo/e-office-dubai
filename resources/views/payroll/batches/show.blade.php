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
                                        <a href="{{ route('payroll.batches.index') }}" class="text-muted fw-light">Payroll
                                             Batches /</a>
                                        {{ date("F", mktime(0, 0, 0, $batch->month, 10)) }} {{ $batch->year }}
                                   </h4>
                                   <div>
                                        @if($batch->status == 'draft' || $batch->status == 'reviewed')
                                             <div class="d-inline-flex gap-2">
                                                  <form action="{{ route('payroll.batches.refresh', $batch->id) }}" method="POST">
                                                       @csrf
                                                       <button class="btn btn-label-primary">
                                                            <i class="ti ti-refresh me-1"></i> Refresh Batch
                                                       </button>
                                                  </form>

                                                  @if($batch->status == 'draft')
                                                       <form action="{{ route('payroll.batches.approve', $batch->id) }}" method="POST">
                                                            @csrf
                                                            <button class="btn btn-success">
                                                                 <i class="ti ti-check me-1"></i> Approve Batch
                                                            </button>
                                                       </form>
                                                  @endif
                                             </div>
                                        @endif
                                        @if($batch->status == 'approved' || $batch->status == 'paid')
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-download me-1"></i> Export
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('payroll.batches.export-bank', $batch->id) }}">Bank Transfer File</a></li>
                                                <li><a class="dropdown-item" href="{{ route('payroll.batches.export-summary', $batch->id) }}">Payroll Summary Register</a></li>

                                            </ul>
                                        </div>
                                        @endif
                                   </div>
                              </div>

                              <div class="row mb-4">
                                   <div class="col-md mb-3">
                                        <div class="card h-100">
                                             <div class="card-body text-center">
                                                  <div class="badge rounded-pill p-2 bg-label-primary mb-2"><i
                                                            class="ti ti-users fs-3"></i></div>
                                                  <h5 class="card-title mb-1">{{ $batch->entries->count() }}</h5>
                                                  <p class="mb-0">Employees</p>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md mb-3">
                                        <div class="card h-100">
                                             <div class="card-body text-center">
                                                  <div class="badge rounded-pill p-2 bg-label-success mb-2"><i
                                                            class="ti ti-currency-dollar fs-3"></i></div>
                                                  <h5 class="card-title mb-1">
                                                       {{ number_format($batch->entries->sum('net_salary'), 2) }}</h5>
                                                  <p class="mb-0">Total Net Payout</p>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md mb-3">
                                        <div class="card h-100">
                                             <div class="card-body text-center">
                                                  <div class="badge rounded-pill p-2 bg-label-danger mb-2"><i
                                                            class="ti ti-trending-down fs-3"></i></div>
                                                  <h5 class="card-title mb-1">
                                                       {{ number_format($batch->entries->sum('total_deductions'), 2) }}</h5>
                                                  <p class="mb-0">Total Deductions</p>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md mb-3">
                                        <div class="card h-100">
                                             <div class="card-body text-center">
                                                  <div class="badge rounded-pill p-2 bg-label-info mb-2"><i
                                                            class="ti ti-building fs-3"></i></div>
                                                  <h5 class="card-title mb-1">
                                                       {{ number_format($batch->entries->sum('total_employer_contribution'), 2) }}</h5>
                                                  <p class="mb-0">Total Er. Contrib</p>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-md mb-3">
                                        <div class="card h-100">
                                             <div class="card-body text-center">
                                                  <div class="badge rounded-pill p-2 bg-label-secondary mb-2"><i
                                                            class="ti ti-chart-bar fs-3"></i></div>
                                                  <h5 class="card-title mb-1">
                                                       {{ number_format($batch->entries->sum('ctc'), 2) }}</h5>
                                                  <p class="mb-0">Total CTC</p>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card shadow-sm mb-4">
                                   <div class="card-header p-0">
                                        <ul class="nav nav-tabs card-header-tabs ms-0 mt-2" id="payrollEntryTabs" role="tablist">
                                             @foreach($structures as $index => $structure)
                                                  <li class="nav-item" role="presentation">
                                                       <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                                               id="tab-{{ $structure->id }}-btn" 
                                                               data-bs-toggle="tab" 
                                                               data-bs-target="#tab-{{ $structure->id }}" 
                                                               type="button" 
                                                               role="tab">
                                                            {{ $structure->name }}
                                                            <span class="badge rounded-pill bg-label-primary ms-1">
                                                                 {{ $batch->entries->where('salary_structure_id', $structure->id)->count() }}
                                                            </span>
                                                       </button>
                                                  </li>
                                             @endforeach
                                        </ul>
                                   </div>
                                   <div class="card-body p-0">
                                        <div class="tab-content p-4" id="payrollEntryTabsContent">
                                             @foreach($structures as $index => $structure)
                                                  <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                                       id="tab-{{ $structure->id }}" 
                                                       role="tabpanel">
                                                       
                                                       <div class="table-responsive text-nowrap">
                                                            <table class="table table-hover table-striped table-bordered align-middle">
                                                                 <thead class="bg-light">
                                                                      <tr>
                                                                           <th>Employee</th>
                                                                           <th>Salary Structure</th>
                                                                           <th class="text-center">Payable Days</th>
                                                                           <th class="text-end">Gross</th>
                                                                           <th class="text-end">Deductions</th>
                                                                           <th class="text-end text-info">Er. Contrib</th>
                                                                           <th class="text-end">Net Salary</th>
                                                                           <th class="text-end text-info">CTC</th>
                                                                           <th class="text-center">Actions</th>
                                                                      </tr>
                                                                 </thead>
                                                                 <tbody>
                                                                      @php 
                                                                           $structureEntries = $batch->entries->filter(function($entry) use ($structure) {
                                                                                return $entry->salary_structure_id == $structure->id;
                                                                           });
                                                                      @endphp
                                                                      @forelse($structureEntries as $entry)
                                                                           <tr>
                                                                                <td>
                                                                                     <div class="d-flex align-items-center">
                                                                                          <div>
                                                                                               <strong>{{ $entry->employee->full_name }}</strong><br>
                                                                                               <small class="text-muted">{{ $entry->employee->employeeID }}</small>
                                                                                          </div>
                                                                                     </div>
                                                                                </td>
                                                                                <td>
                                                                                     <span class="badge bg-label-secondary">{{ $entry->structure->name ?? 'N/A' }}</span>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                     <span class="badge bg-label-info">{{ number_format($entry->attendance_days, 1) }}</span>
                                                                                </td>
                                                                                <td class="text-end">{{ number_format($entry->gross_salary, 2) }}</td>
                                                                                <td class="text-end">{{ number_format($entry->total_deductions, 2) }}</td>
                                                                                <td class="text-end text-info">{{ number_format($entry->total_employer_contribution, 2) }}</td>
                                                                                <td class="text-end">
                                                                                     <strong class="text-primary">{{ number_format($entry->net_salary, 2) }}</strong>
                                                                                </td>
                                                                                <td class="text-end fw-bold text-info">{{ number_format($entry->ctc, 2) }}</td>
                                                                                <td class="text-center">
                                                                                     <a href="{{ route('payroll.entries.payslip', $entry->id) }}"
                                                                                        class="btn btn-sm btn-label-secondary">
                                                                                          <i class="ti ti-download me-1"></i> Payslip
                                                                                     </a>
                                                                                </td>
                                                                           </tr>
                                                                      @empty
                                                                           <tr>
                                                                                <td colspan="8" class="text-center py-4 text-muted">No entries for this structure.</td>
                                                                           </tr>
                                                                      @endforelse
                                                                 </tbody>
                                                            </table>
                                                       </div>
                                                  </div>
                                             @endforeach
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <x-footer />
                    </div>
               </div>
          </div>
     </div>
@endsection