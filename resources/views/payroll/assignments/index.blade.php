@extends('layouts.app')

@section('css')
<style>
    .card-header.cursor-pointer:hover {
        background-color: rgba(0,0,0,0.03);
    }
    .card-header.cursor-pointer[aria-expanded="true"] .ti-chevron-right {
        transform: rotate(90deg);
        transition: transform 0.2s;
    }
    .ti-chevron-right {
        transition: transform 0.2s;
        display: inline-block;
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
                              <div class="d-flex justify-content-between align-items-center mb-4">
                                   <h4 class="fw-bold py-3 mb-0">Pay Configuration</h4>
                                   <a href="{{ route('payroll.assignments.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Assign Salary to Employee
                                   </a>
                              </div>

                              @forelse($structures as $structure)
                                   <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center cursor-pointer" 
                                             data-bs-toggle="collapse" data-bs-target="#collapse{{ $structure->id }}" aria-expanded="false">
                                             <div class="d-flex align-items-center">
                                                  <i class="ti ti-chevron-right fs-4 me-2"></i>
                                                  <h5 class="mb-0 fw-bold">{{ $structure->name }}</h5>
                                             </div>
                                             <div class="d-flex align-items-center">
                                                  <span class="badge bg-label-info me-3" style="font-size: 0.9rem;">
                                                       <i class="ti ti-users me-1"></i> {{ $structure->assignments_count }} Employees
                                                  </span>
                                                  <small class="text-muted">Click to toggle list</small>
                                             </div>
                                        </div>
                                        <div id="collapse{{ $structure->id }}" class="collapse">
                                             <div class="card-body pt-0">
                                                  <div class="table-responsive">
                                                       <table class="table table-hover table-sm">
                                                            <thead class="table-light">
                                                                 <tr>
                                                                      <th>Employee</th>
                                                                      <th>Monthly CTC</th>
                                                                      <th>Annual CTC</th>
                                                                      <th class="text-center">Statutory</th>
                                                                      <th>Effective Date</th>
                                                                      <th>Status</th>
                                                                      <th class="text-end">Actions</th>
                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 @forelse($structure->assignments as $assignment)
                                                                      <tr>
                                                                           <td>
                                                                                <strong>{{ $assignment->employee->full_name }}</strong><br>
                                                                                <small class="text-muted">{{ $assignment->employee->employeeID }}</small>
                                                                           </td>
                                                                           <td class="fw-semibold text-primary">₹{{ number_format($assignment->monthly_ctc, 2) }}</td>
                                                                           <td>₹{{ number_format($assignment->annual_ctc, 2) }}</td>
                                                                           <td class="text-center text-nowrap">
                                                                                <span class="badge badge-dot bg-{{ $assignment->pf_eligible ? 'success' : 'danger' }} me-1" title="PF Eligible"></span>
                                                                                <span class="badge badge-dot bg-{{ $assignment->esi_eligible ? 'success' : 'danger' }}" title="ESI Eligible"></span>
                                                                                <small class="ms-1" style="font-size: 0.70rem;">PF/ESI</small>
                                                                           </td>
                                                                           <td class="text-nowrap">{{ date('d M Y', strtotime($assignment->effective_date)) }}</td>
                                                                           <td>
                                                                                <span class="badge bg-{{ $assignment->status ? 'success' : 'secondary' }} rounded-pill" style="font-size: 0.7rem;">
                                                                                     {{ $assignment->status ? 'Active' : 'Inactive' }}
                                                                                </span>
                                                                           </td>
                                                                           <td class="text-end">
                                                                                <div class="d-inline-block text-nowrap">
                                                                                     <a href="{{ route('payroll.assignments.edit', $assignment->id) }}"
                                                                                          class="btn btn-sm btn-icon btn-text-secondary rounded-pill">
                                                                                          <i class="ti ti-edit"></i>
                                                                                     </a>
                                                                                     <form action="{{ route('payroll.assignments.destroy', $assignment->id) }}"
                                                                                          method="POST" class="d-inline">
                                                                                          @csrf @method('DELETE')
                                                                                          <button class="btn btn-sm btn-icon btn-text-danger rounded-pill"
                                                                                               onclick="return confirm('Are you sure you want to delete this assignment?')">
                                                                                               <i class="ti ti-trash"></i>
                                                                                          </button>
                                                                                     </form>
                                                                                </div>
                                                                           </td>
                                                                      </tr>
                                                                 @empty
                                                                      <tr>
                                                                           <td colspan="7" class="text-center text-muted py-3">No active assignments under this structure.</td>
                                                                      </tr>
                                                                 @endforelse
                                                            </tbody>
                                                       </table>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              @empty
                                   <div class="card p-5 text-center">
                                        <div class="card-body">
                                             <i class="ti ti-alert-circle fs-1 text-muted mb-3 d-block"></i>
                                             <h5>No Salary Structures Found</h5>
                                             <p class="text-muted">Create a structure first before assigning it to employees.</p>
                                             <a href="{{ route('payroll.structures.create') }}" class="btn btn-outline-primary">Create Structure</a>
                                        </div>
                                   </div>
                              @endforelse
                         </div>
                         <x-footer />
                    </div>
               </div>
          </div>
     </div>
@endsection