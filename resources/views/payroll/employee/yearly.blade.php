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
                                   <h4 class="fw-bold py-3 mb-0">Yearly Salary Statement</h4>
                                   <form action="{{ route('my-payroll.yearly-statement') }}" method="GET"
                                        class="d-flex gap-2">
                                        <select name="year" class="form-select" onchange="this.form.submit()">
                                             @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                                  <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                             @endfor
                                        </select>
                                   </form>
                              </div>

                              <div class="row mb-4">
                                   <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                             <div class="card-body">
                                                  <h6 class="text-white opacity-75 mb-1">Total Gross</h6>
                                                  <h4 class="text-white mb-0">{{ number_format($summary['gross'], 2) }}</h4>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                             <div class="card-body">
                                                  <h6 class="text-white opacity-75 mb-1">Net Received</h6>
                                                  <h4 class="text-white mb-0">{{ number_format($summary['net'], 2) }}</h4>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="card bg-danger text-white">
                                             <div class="card-body">
                                                  <h6 class="text-white opacity-75 mb-1">Total TDS</h6>
                                                  <h4 class="text-white mb-0">{{ number_format($summary['tds'], 2) }}</h4>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                             <div class="card-body">
                                                  <h6 class="text-white opacity-75 mb-1">Total PF</h6>
                                                  <h4 class="text-white mb-0">{{ number_format($summary['pf'], 2) }}</h4>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <div class="table-responsive">
                                             <table class="table table-bordered table-striped">
                                                  <thead>
                                                       <tr>
                                                            <th>Month</th>
                                                            <th>Gross Salary</th>
                                                            <th>Statutory (PF/ESI)</th>
                                                            <th>TDS</th>
                                                            <th>Other Ded.</th>
                                                            <th>LOP Amount</th>
                                                            <th>Net Salary</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       @foreach($entries->sortBy('batch.month') as $entry)
                                                            <tr>
                                                                 <td><strong>{{ date("F", mktime(0, 0, 0, $entry->batch->month, 10)) }}</strong>
                                                                 </td>
                                                                 <td>{{ number_format($entry->gross_salary, 2) }}</td>
                                                                 <td>{{ number_format($entry->pf_amount + $entry->esi_amount, 2) }}
                                                                 </td>
                                                                 <td class="text-danger">
                                                                      {{ number_format($entry->tds_amount, 2) }}</td>
                                                                 <td>{{ number_format($entry->total_deductions - $entry->pf_amount - $entry->esi_amount - $entry->tds_amount, 2) }}
                                                                 </td>
                                                                 <td>{{ number_format($entry->lop_amount, 2) }}</td>
                                                                 <td><strong
                                                                           class="text-success">{{ number_format($entry->net_salary, 2) }}</strong>
                                                                 </td>
                                                            </tr>
                                                       @endforeach
                                                  </tbody>
                                                  <tfoot>
                                                       <tr class="table-dark">
                                                            <th>TOTAL</th>
                                                            <th>{{ number_format($summary['gross'], 2) }}</th>
                                                            <th>{{ number_format($summary['pf'] + $summary['esi'], 2) }}</th>
                                                            <th>{{ number_format($summary['tds'], 2) }}</th>
                                                            <th>{{ number_format($summary['deductions'] - $summary['pf'] - $summary['esi'] - $summary['tds'], 2) }}
                                                            </th>
                                                            <th>{{ number_format($summary['lop'], 2) }}</th>
                                                            <th>{{ number_format($summary['net'], 2) }}</th>
                                                       </tr>
                                                  </tfoot>
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