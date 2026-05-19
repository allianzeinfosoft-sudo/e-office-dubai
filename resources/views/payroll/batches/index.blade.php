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
                                   <h4 class="fw-bold py-3 mb-0">Payroll Processing</h4>
                                   <div class="d-flex gap-2">
                                        <a href="{{ route('payroll.batches.create') }}" class="btn btn-primary">
                                             <i class="ti ti-plus me-1"></i> Create Manual Batch
                                        </a>
                                        {{-- <button class="btn btn-primary" data-bs-toggle="modal"
                                             data-bs-target="#generateBatchModal">
                                             <i class="ti ti-plus me-1"></i> Generate New Batch
                                        </button> --}}
                                   </div>
                              </div>

                              @if(session('success'))
                                   <div class="alert alert-success mt-3">
                                        {{ session('success') }}
                                   </div>
                              @endif
                              @if(session('error'))
                                   <div class="alert alert-danger mt-3">
                                        {{ session('error') }}
                                   </div>
                              @endif

                              <div class="card">
                                   <div class="card-body">
                                        <div class="table-responsive">
                                             <table class="table table-hover">
                                                  <thead>
                                                       <tr>
                                                            <th>Period</th>
                                                             {{-- <th>Department</th> --}}
                                                             <th>Structure</th>
                                                             <th>Type</th>
                                                            <th>Status</th>
                                                            <th>Employees</th>
                                                            <th>Net Payout</th><th>Total CTC</th>
                                                            <th>Processed At</th>
                                                            <th>Actions</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       @forelse($batches as $batch)
                                                            <tr>
                                                                 <td><strong>{{ date("F", mktime(0, 0, 0, $batch->month, 10)) }}
                                                                           {{ $batch->year }}</strong></td>
                                                                 {{-- <td>{{ $batch->department->name ?? 'All Departments' }}</td> --}}
                                                                  <td>{{ $batch->structure->name ?? 'All Structures' }}</td>
                                                                  <td> {!! $batch->is_part_wise ? "<span class=\"badge bg-label-info\">Part-wise</span>" : "<span class=\"badge bg-label-secondary\">Regular</span>" !!} </td>
                                                                 <td>
                                                                      <span
                                                                           class="badge bg-{{ $batch->status == 'approved' ? 'success' : ($batch->status == 'paid' ? 'info' : ($batch->status == 'draft' ? 'secondary' : 'warning')) }}">
                                                                           {{ ucfirst($batch->status) }}
                                                                      </span>
                                                                 </td>
                                                                 <td>{{ $batch->entries_count ?? $batch->entries->count() }}</td>
                                                                 <td>{{ number_format($batch->total_net_payout ?? $batch->entries->sum('net_salary'), 2) }}</td>
                                                                 <td>{{ number_format($batch->total_ctc ?? $batch->entries->sum('ctc'), 2) }}</td>
                                                                 <td>{{ $batch->processed_at ? $batch->processed_at->format('d M Y') : 'N/A' }}
                                                                 </td>
                                                                 <td>
                                                                      <div class="d-flex gap-2">
                                                                           <a href="{{ route('payroll.batches.show', $batch->id) }}"
                                                                                class="btn btn-sm btn-icon btn-label-info" title="View Details">
                                                                                <i class="ti ti-eye"></i>
                                                                           </a>
                                                                           @if($batch->status == 'draft')
                                                                            <a href="{{ route('payroll.batches.edit', $batch->id) }}"
                                                                                class="btn btn-sm btn-icon btn-label-warning" title="Edit Batch">
                                                                                <i class="ti ti-edit"></i>
                                                                            </a>
                                                                           @endif
                                                                            @if($batch->status == 'draft')
                                                                                 <form action="{{ route('payroll.batches.approve', $batch->id) }}"
                                                                                      method="POST" class="d-inline">
                                                                                      @csrf
                                                                                      <button class="btn btn-sm btn-icon btn-label-success" title="Approve Batch">
                                                                                           <i class="ti ti-check"></i>
                                                                                      </button>
                                                                                 </form>
                                                                            @endif
                                                                            @if($batch->status !== 'paid')
                                                                                 <form action="{{ route('payroll.batches.destroy', $batch->id) }}"
                                                                                      method="POST" class="d-inline"
                                                                                      onsubmit="return confirm('Are you sure you want to delete this batch? All entries and components will be permanently removed.')">
                                                                                      @csrf
                                                                                      @method('DELETE')
                                                                                      <button class="btn btn-sm btn-icon btn-label-danger"
                                                                                           title="Delete Batch">
                                                                                           <i class="ti ti-trash"></i>
                                                                                      </button>
                                                                                 </form>
                                                                            @endif
                                                                       </div>
                                                                 </td>
                                                            </tr>
                                                       @empty
                                                            <tr>
                                                                 <td colspan="9" class="text-center">No payroll batches found.
                                                                 </td>
                                                            </tr>
                                                       @endforelse
                                                  </tbody>
                                             </table>
                                        </div>
                                        <div class="mt-3">
                                             {{ $batches->links() }}
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <x-footer />
                    </div>
               </div>
          </div>
     </div>

     <!-- Generate Batch Modal -->
     <div class="modal fade" id="generateBatchModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
               <form action="{{ route('payroll.batches.generate') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                         <div class="modal-header bg-primary">
                              <h5 class="modal-title text-white">Generate Payroll Batch</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>
                         <div class="modal-body p-4">
                              <div class="alert alert-info py-2">
                                   <i class="ti ti-info-circle me-1"></i> This will calculate salary for all active employees
                                   for the selected period.
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Month</label>
                                   <select name="month" class="form-select" required>
                                        @for($i = 1; $i <= 12; $i++)
                                             <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                                  {{ date("F", mktime(0, 0, 0, $i, 10)) }}
                                             </option>
                                        @endfor
                                   </select>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Year</label>
                                   <select name="year" class="form-select" required>
                                        @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                             <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                   </select>
                              </div>
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary">Process Payroll</button>
                         </div>
                    </div>
               </form>
          </div>
     </div>
@endsection