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
                                   <h4 class="fw-bold py-3 mb-0">Earnings & Deductions</h4>
                                   <a href="{{ route('payroll.components.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Component
                                   </a>
                              </div>

                              @if(session('success'))
                                   <div class="alert alert-success mt-3">
                                        {{ session('success') }}
                                   </div>
                              @endif

                              <div class="card">
                                   <div class="card-body">
                                        <div class="table-responsive">
                                             <table class="table table-hover">
                                                  <thead>
                                                       <tr>
                                                            <th>Name</th>
                                                            <th>Type</th>
                                                            <th>Statutory</th>
                                                            <th>Variable</th>
                                                            <th>CTC Var.</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       @forelse($components as $component)
                                                            <tr>
                                                                 <td><strong>{{ $component->name }}</strong></td>
                                                                 <td>
                                                                      <span
                                                                           class="badge bg-label-{{ $component->type == 'earning' ? 'success' : 'danger' }}">
                                                                           {{ ucfirst($component->type) }}
                                                                      </span>
                                                                 </td>
                                                                 <td>
                                                                      <i
                                                                           class="ti ti-{{ $component->is_statutory ? 'check text-success' : 'x text-danger' }}"></i>
                                                                 </td>
                                                                 <td>
                                                                      <i
                                                                           class="ti ti-{{ $component->is_variable ? 'check text-success' : 'x text-danger' }}"></i>
                                                                 </td>
                                                                 <td>
                                                                      <i
                                                                           class="ti ti-{{ $component->is_ctc_variable ? 'check text-success' : 'x text-danger' }}"></i>
                                                                 </td>
                                                                 <td>
                                                                      <span
                                                                           class="badge bg-{{ $component->status ? 'success' : 'secondary' }}">
                                                                           {{ $component->status ? 'Active' : 'Inactive' }}
                                                                      </span>
                                                                 </td>
                                                                 <td>
                                                                      <div class="d-flex gap-2">
                                                                           <a href="{{ route('payroll.components.edit', $component->id) }}"
                                                                                class="btn btn-sm btn-icon btn-label-primary">
                                                                                <i class="ti ti-edit"></i>
                                                                           </a>
                                                                           <form action="{{ route('payroll.components.destroy', $component->id) }}"
                                                                                method="POST" class="d-inline">
                                                                                @csrf @method('DELETE')
                                                                                <button
                                                                                     class="btn btn-sm btn-icon btn-label-danger"
                                                                                     onclick="return confirm('Are you sure?')">
                                                                                     <i class="ti ti-trash"></i>
                                                                                </button>
                                                                           </form>
                                                                      </div>
                                                                 </td>
                                                            </tr>
                                                       @empty
                                                            <tr>
                                                                 <td colspan="6" class="text-center">No components defined yet.
                                                                 </td>
                                                            </tr>
                                                       @endforelse
                                                  </tbody>
                                             </table>
                                        </div>
                                        <div class="mt-3">
                                             {{ $components->links() }}
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