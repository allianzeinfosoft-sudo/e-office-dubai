@extends('layouts.app')

@section('content')
     <div class="layout-wrapper layout-content-navbar">
          <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
               <x-menu />
               <div class="layout-page">
                    <x-header />
                    <div class="content-wrapper">
                         <div class="container-xxl flex-grow-1 container-p-y">
                              <h4 class="fw-bold py-3 mb-4">My Payslips</h4>

                              <div class="row">
                                   @forelse($payslips as $payslip)
                                        <div class="col-md-4 mb-4">
                                             <div class="card h-100">
                                                  <div class="card-body">
                                                       <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <div class="badge bg-label-primary p-2 rounded">
                                                                 <i class="ti ti-file-text fs-3"></i>
                                                            </div>
                                                            <h5 class="mb-0">
                                                                 {{ date("F", mktime(0, 0, 0, $payslip->batch->month, 10)) }}
                                                                 {{ $payslip->batch->year }}</h5>
                                                       </div>

                                                       <div class="mb-3">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                 <span class="text-muted">Net Salary:</span>
                                                                 <span
                                                                      class="fw-bold text-success">{{ number_format($payslip->net_salary, 2) }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between mb-1">
                                                                 <span class="text-muted">Gross Salary:</span>
                                                                 <span>{{ number_format($payslip->gross_salary, 2) }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                 <span class="text-muted">Total Deductions:</span>
                                                                 <span
                                                                      class="text-danger">{{ number_format($payslip->total_deductions + $payslip->lop_amount, 2) }}</span>
                                                            </div>
                                                       </div>

                                                       <div class="d-grid gap-2">
                                                            <a href="{{ route('my-payroll.payslips.download', $payslip->id) }}"
                                                                 class="btn btn-primary">
                                                                 <i class="ti ti-download me-1"></i> Download PDF
                                                            </a>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   @empty
                                        <div class="col-12 text-center py-5">
                                             <div class="mb-3">
                                                  <i class="ti ti-file-off fs-1 text-muted"></i>
                                             </div>
                                             <h5>No payslips found yet.</h5>
                                             <p class="text-muted">Once your monthly payroll is approved, your payslip will appear
                                                  here.</p>
                                        </div>
                                   @endforelse
                              </div>

                              <div class="mt-4">
                                   {{ $payslips->links() }}
                              </div>
                         </div>
                         <x-footer />
                    </div>
               </div>
          </div>
     </div>
@endsection