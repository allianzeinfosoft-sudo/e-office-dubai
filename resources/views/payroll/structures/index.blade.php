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
                                   <h4 class="fw-bold py-3 mb-0">Pay Groups</h4>
                                   <a href="{{ route('payroll.structures.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Structure
                                   </a>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <div class="table-responsive">
                                             <table class="table table-hover">
                                                  <thead>
                                                       <tr>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                            <th>Components</th>
                                                            <th>Created At</th>
                                                            <th>Actions</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       @forelse($structures as $structure)
                                                            <tr>
                                                                 <td><strong>{{ $structure->name }}</strong></td>
                                                                 <td>{{ \Illuminate\Support\Str::limit($structure->description, 50) }}
                                                                 </td>
                                                                 <td>{{ $structure->components->count() }}</td>
                                                                 <td>{{ $structure->created_at->format('d M Y') }}</td>
                                                                 <td>
                                                                      <div class="d-flex gap-2">
                                                                           <a href="{{ route('payroll.structures.edit', $structure->id) }}"
                                                                                class="btn btn-sm btn-icon btn-label-primary">
                                                                                <i class="ti ti-edit"></i>
                                                                           </a>
                                                                           <form action="{{ route('payroll.structures.destroy', $structure->id) }}"
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
                                                                 <td colspan="5" class="text-center">No structures defined yet.
                                                                 </td>
                                                            </tr>
                                                       @endforelse
                                                  </tbody>
                                             </table>
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