@extends('layouts.app')

@section('content')
<div class="layout-wrapper layout-content-navbar {{ $background_class ?? 'bg-eoffice' }}">
    <div class="layout-container">
        <x-menu /> <!-- Load the menu component here -->
      <!-- Layout container -->
      <div class="layout-page ">
        <!-- Navbar -->

        <x-header />

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row justify-content-between">
                <!-- Sales last year -->
                <div class="col-xl-5 col-lg-6 col-md-12">
                  <div class="card card-bg ">                   
                    <div class="card-body text-center py-3">
                      <div class="d-flex justify-content-end">
                        <a href="javascript:;"><span class="badge bg-label-warning">{{ $employee->employeeID ?? '' }}</span></a>
                      </div>
                      <div class="mx-auto my-3">
                        <img src="{{ $employee->profile_image ? asset('storage/' . $employee->profile_image) : asset('assets/img/avatars/1.png' ) }}"  alt="Avatar Image" class="rounded-circle w-px-100">
                      </div>
                      <h5 class="mb-2">{{ $employee->full_name ?? '' }}</h5>
                      <div class="d-flex gap-3 mb-2 justify-content-center text-primary fw-bold">
                        <span><i class="ti ti-color-swatch mt-n2"></i> {{ $employee->department->department ?? '' }}</span>
                        <span><i class="ti ti-user mt-n2"></i> {{ $employee->designation->designation ?? '' }} </span>
                      </div>
                      <span><i class="ti ti-mail mt-n1 me-1"></i> <a href="mailto:{{ $employee->user->email ?? ''}}"> {{ $employee->user->email ?? ''}} </a></span>                     
                      <div class="d-flex justify-content-center mt-2 mb-3">
                        <span class="fw-bold"><i class="ti ti-calendar mt-n2"></i>Date of Join : &nbsp;</span><span>{{ $employee->dob ? date('d-m-Y', strtotime($employee->dob)) : ''  }}</span>
                      </div>                     
                    </div>
                  </div>
                </div>

                <!--Attendence Analystics-->
                <div class="col-md-12 col-lg-6 col-xl-7 my-auto">
                  <div class="card card-bg">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0">
                        <h5 class="mb-0">Attendence Analystics</h5>
                        <small class="text-muted">May</small>
                      </div>
                      <div class="dropdown d-none d-sm-flex">
                        <button
                          type="button"
                          class="btn dropdown-toggle px-0"
                          data-bs-toggle="dropdown"
                          aria-expanded="false">
                          <i class="ti ti-calendar"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <li><a href="javascript:void(0);" class="dropdown-item filter-range" data-range="today">Today</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item filter-range" data-range="yesterday">Yesterday</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item filter-range" data-range="last_7_days">Last 7 Days</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item filter-range" data-range="last_30_days">Last 30 Days</a></li>
                          <li><hr class="dropdown-divider" /></li>
                          <li><a href="javascript:void(0);" class="dropdown-item filter-range" data-range="current_month">Current Month</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item filter-range" data-range="last_month">Last Month</a></li>
                        </ul>
                      </div>
                    </div>
                    <div class="card-body d-flex row pb-0">
                      <div class="col-sm-6 col-xl-6 mb-5">                        
                        <div class="d-flex align-items-center">
                          <span class="badge bg-label-warning rounded me-2 p-2">
                            <i class="ti ti-clock ti-sm"></i>
                          </span>
                          <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                              <h4 class="mb-0 me-2" id="totalWorkingTime" >{{ $totalWorkingTime }}</h4>                               
                            </div>
                            <span>Total Working Time</span>
                          </div>
                        </div>                          
                      </div>
                      <div class="col-sm-6 col-xl-6 mb-5">                       
                        <div class="d-flex align-items-center">
                          <span class="badge bg-label-warning rounded me-2 p-2">
                            <i class="ti ti-hourglass-high  ti-sm"></i>
                          </span>
                          <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                              <h4 class="mb-0 me-2" id="averageWorkingTime">{{ $averageWorkingTime }}</h4>
                            </div>
                            <span>Avg. Working Time</span>
                          </div>                         
                        </div>                          
                      </div>
                      <div class="col-sm-6 col-xl-6 mb-5">
                        <div class="d-flex align-items-center">
                          <span class="badge bg-label-warning rounded me-2 p-2">
                            <i class="ti ti-info-circle ti-sm"></i>
                          </span>
                          <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                              <h4 class="mb-0 me-2" id="workingDays">{{ $workingDays }}</h4>
                            </div>
                            <span>No. of Working Days</span>
                          </div>                          
                        </div>                          
                      </div>
                      <div class="col-sm-6 col-xl-6 mb-5">                        
                        <div class="d-flex align-items-center">
                          <span class="badge bg-label-warning rounded me-2 p-2">
                            <i class="ti ti-calendar ti-sm"></i>
                          </span>
                          <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                              <h4 class="mb-0 me-2" id="leaveCount">{{ $leaveCount }}</h4>
                            </div>
                            <span>Leave</span>
                          </div>                          
                        </div>                         
                      </div> 
                    </div>
                  </div>
                </div>
                <!--Attendence Analystics-->

                <!--Leave Summary-->
                <div class="col-md-12 col-lg-6 col-xl-6 mt-3">
                  <div class="card card-bg pb-3">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0">
                        <h5 class="mb-0">Leave Summary</h5>
                        <small class="text-muted">May</small>
                      </div>
                      <div class="dropdown d-none d-sm-flex">
                        <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="ti ti-calendar"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <li> <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" onclick="updateLeaveSummary('today')" >Today</a> </li>
                          <li> <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" onclick="updateLeaveSummary('yesterday')">Yesterday</a></li>
                          <li> <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" onclick="updateLeaveSummary('last_7_days')">Last 7 Days</a></li>
                          <li> <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" onclick="updateLeaveSummary('last_30_days')">Last 30 Days</a></li>
                          <li>
                            <hr class="dropdown-divider" />
                          </li>
                          <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" onclick="updateLeaveSummary('current_month')">Current Month</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" onclick="updateLeaveSummary('last_month')">Last Month</a></li>
                        </ul>

                          
                      </div>
                    </div>
                    <div class="card-body row pb-0">
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2" id="leaveThisMonth">0</h5>
                              <small>This Month Leave(s)</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-bolt ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2" id="totalLeavesTaken">1.5</h5>
                              <small>Total Leave(s) Taken</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-drone ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2" id="offDays">1</h5>
                              <small>Off Days</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-drone ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2" id="pendingLeaves">12.5</h5>
                              <small>Pending Leave(s)</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-server ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>                      
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2" id="totalLeavesAllotted">15</h5>
                              <small>Total Leave(s) Alloted</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-hourglass-high ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6 mb-4">
                        <div class="card">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-1" id="pastYearLeaves">10</h5>
                              <small>Past Year Leave(s)</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-calendar ti-sm"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12 col-sm-12">
                        <div class="card">                          
                          <div class="card-body d-flex justify-content-between align-items-center">                            
                            <div class="card-title mb-0">
                              <div id="leaveCategorySummary">
                                <span class="badge bg-label-warning me-2">Full: 1</span>                          
                                <span class="badge bg-label-primary me-2">Half: 1</span>                           
                                <span class="badge bg-label-success">Off Days: 1</span>                              
                              </div>                        
                              <small>Leave(s) Category Wise</small>
                            </div>
                            <div class="card-icon">
                              <span class="badge bg-label-warning rounded-pill p-2">
                                <i class="ti ti-info-circle"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>                      
                    </div>
                  </div>
                </div>
                <!--Leave Summary-->

                <!--Current Productive Time Analytics -->
                <div class="col-md-6 col-12 my-auto">
                  <div class="card card-bg">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <div>
                        <h5 class="card-title mb-0">Current Productive Time Analytics</h5>
                        <small class="text-muted">May</small>
                      </div>
                      <div class="dropdown d-none d-sm-flex">
                        <button
                          type="button"
                          class="btn dropdown-toggle px-0"
                          data-bs-toggle="dropdown"
                          aria-expanded="false">
                          <i class="ti ti-calendar"></i>
                        </button>
                         <ul class="dropdown-menu dropdown-menu-end">
                          <li><a href="javascript:void(0);"  class="dropdown-item d-flex align-items-center">Today</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
                          <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
                          <li><hr class="dropdown-divider" /></li>
                          <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
                          <li><a href="javascript:void(0);"  class="dropdown-item d-flex align-items-center">Last Month</a></li>
                        </ul>
                      </div>
                    </div>
                    <div class="card-body pb-5">
                      <x-charts.attendance-donut-chart
                        id="attendanceDonut"
                        :labels="['Completed', 'Half Days', 'Off', 'Custom', 'Holidays', 'Leaves']"
                        :donutsData="[100, 20, 30, 40, 50, 60]"
                        :backgroundColors="['#fee802', '#3fd0bd', '#826bf8', '#2b9bf4', '#f86624', '#ea5455']"
                        height="360px"
                      />
                    </div>
                  </div>
                </div>
                <!--Current Productive Time Analytics -->
                  
                <!--current attendence Analytics -->
                <div class="col-12 mt-3">
                  <div class="card card-bg">
                    <div class="card-header d-flex justify-content-between align-items-md-center align-items-start">
                      <h5 class="card-title mb-0">Current Attendance Analytics</h5>
                      <div class="dropdown">
                        <i class="ti ti-calendar"></i>
                      </button>
                      
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li> <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a></li>
                      </ul>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive mb-4">
                        <table class="table">
                          <thead>
                            <tr>                                    
                                <th>Month</th>
                                <th><small>Avg Working hours</small></th>
                                <th>Total Working hours</th>
                                <th>No Of Working Days</th>
                                <th>leaves</th>
                                <th>Off</th>
                                <th>Year</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if($worksBrakesData)
                              @foreach($worksBrakesData as $key => $data)
                              @php

                              @endphp
                              <tr>
                                <td>{{ $data['month'] ?? '' }}</td>
                                <td class="text-special">{{ $data['avg_working_hours'] ?? '00:00:00' }}</td>
                                <td>{{ $data['total_working_hours'] ?? '00:00:00' }}</td>
                                <td>{{ $data['working_days'] ?? 0 }}</td>
                                <td>{{ $data['leaves'] ?? 0 }}</td>
                                <td><label class="label label-success">{{ $data['off_days'] ?? 0 }}</label></td>
                                <td><label class="label label-inverse">{{ $data['year'] ?? '' }}</label></td>
                            </tr>                                                                                      
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>

                      <x-charts.apex-bar-chart 
                        elementId="barChart"
                        :series="[
                            [
                              'name' => 'Working Hours', 
                              'data' => $barChartData['working_hours'] ?? [],
                            ],
                            [
                              'name' => 'Beak Hours',
                              'data' => $barChartData['break_hours'] ?? [],
                            ],
                        ]"
                        :categories="$barChartData['dates'] ?? []"
                        height="300" />
                    </div>
                  </div>
                </div>
                <!--current attendence Analytics --> 
               
                <!-- Holiday List-->
                <div class="col-xl-6 col-md-6 col-6 mt-3">
                  <div class="card card-bg">
                    <div class="card-header d-flex justify-content-between align-items-md-center align-items-start">
                      <h5 class="card-title mb-0">Holiday List</h5>
                      <div class="dropdown">
                        <button
                          type="button"
                          class="btn dropdown-toggle p-0"
                          data-bs-toggle="dropdown"
                          aria-expanded="false">
                          <i class="ti ti-calendar"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-highlight table-checkable" data-provide="datatable" data-display-rows="25" data-info="true" data-search="true" data-length-change="true" data-paginate="true">
                            <thead>
                              <tr>
                                <th data-sortable="true" data-direction="asc">SL</th>
                                <th data-sortable="true">Name of Holiday</th>
                                <th data-sortable="true">Date of Holiday</th>                               
                              </tr>
                            </thead>
                            <tbody>
                              
                              <tr class="158">
                                <td>1</td>
                                <td>Republic Day</td>
                                <td>26-January-2025</td>                               
                              </tr>
                              <tr class="162">
                                <td>2</td>
                                <td>Sivarathri</td>
                                <td>26-February-2025</td>                               
                              </tr>
                              <tr class="163">
                                <td>3</td>
                                <td>Id-Ul-Fitr(Ramzan)</td>
                                <td>31-March-2025</td>                               
                              </tr>
                              <tr class="164">
                                <td>4</td>
                                <td>Vishu</td>
                                <td>14-April-2025</td>                               
                              </tr>
                              <tr class="165">
                                <td>5</td>
                                <td>Good Friday</td>
                                <td>18-April-2025</td>                               
                              </tr>
                              <tr class="159">
                                <td>6</td>
                                <td>May Day	</td>
                                <td>01-May-2025</td>                               
                              </tr>
                              <tr class="160">
                                <td>7</td>
                                <td>Independence Day</td>
                                <td>15-August-2025</td>                               
                              </tr>
                              <tr class="166">
                                <td>8</td>
                                <td>First Onam</td>
                                <td>04-September-2025</td>                               
                              </tr>
                              <tr class="171">
                                <td>9</td>
                                <td>Thiruvonam/Milad-i-Sherif</td>
                                <td>05-September-2025</td>                               
                              </tr>
                              <tr class="168">
                                <td>10</td>
                                <td>Mahanavami</td>
                                <td>01-October-2025</td>                               
                              </tr>
                              <tr class="161">
                                <td>11</td>
                                <td>Gandhi Jayanthi</td>
                                <td>02-October-2025</td>                               
                              </tr>
                              <tr class="169">
                                <td>12</td>
                                <td>Deepavali</td>
                                <td>20-October-2025</td>                               
                              </tr>
                              <tr class="170">
                                <td>13</td>
                                <td>Christmas</td>
                                <td>25-December-2025</td>                               
                              </tr>                              
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>
                <!-- Holiday List-->

                <!-- HOD mail List-->
                <div class="col-xl-6 col-md-6 col-6 mt-3">
                  <div class="card card-bg">
                    <div class="card-header d-flex justify-content-between align-items-md-center align-items-start">
                      <h5 class="card-title mb-0">HOD mail List</h5>
                      <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle p-0" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="ti ti-calendar"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover table-highlight table-checkable" data-provide="datatable" data-display-rows="25" data-info="true" data-search="true" data-length-change="true" data-paginate="true">
                            <thead>
                              <tr>
                               <th data-sortable="true" data-direction="asc">Department</th>
                                <th data-sortable="true">Name</th>
                                <th data-sortable="true">Emails</th>                               
                              </tr>
                            </thead>
                            <tbody>
                              @if($uniqueTeamLeads->isNotEmpty())
                                @foreach($uniqueTeamLeads as $uniqueTeamLead)
                                <tr class="{{$uniqueTeamLead->id}}">
                                  <td>{{ $uniqueTeamLead->department->department ?? '' }}</td>
                                  <td> {{ $uniqueTeamLead->full_name ?? '' }} </td>
                                  <td><a href="mailto:{{ $uniqueTeamLead->user->email ?? ''  }}">{{ $uniqueTeamLead->user->email ?? ''  }} </a>	</td>                               
                                </tr>
                                @endforeach
                              @endif                                                        
                            </tbody>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>
                <!-- HOD mail List-->
              </div>
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <x-footer /> 
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
  </div>
@endsection
@push('js')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
  $(document).on('click', '.filter-range', function () {
  const range = $(this).data('range');

  $.ajax({
    url: "{{ route('attendance.analytics') }}",
    method: 'GET',
    data: { range: range },
    beforeSend: function () {
      // Optionally show loader
    },
    success: function (response) {
      $('#totalWorkingTime').text(response.totalWorkingTime);
      $('#averageWorkingTime').text(response.averageWorkingTime);
      $('#workingDays').text(response.workingDays);
      $('#leaveCount').text(response.leaveCount);
    },
    error: function () {
      alert('Failed to fetch attendance data.');
    }
  });
});


function updateLeaveSummary(range) {
  $.ajax({
    url: "{{ route('leave.summary') }}",
    method: 'GET',
    data: { range: range },
    success: function (response) {
      $('#leaveThisMonth').text(response.leaveThisMonth);
      $('#totalLeavesTaken').text(response.totalLeavesTaken);
      $('#offDays').text(response.offDays);
      $('#pendingLeaves').text(response.pendingLeaves);
      $('#totalLeavesAllotted').text(response.totalLeavesAllotted);
      $('#pastYearLeaves').text(response.pastYearLeaves);

      // Update category summary
      $('#leaveCategorySummary').html(`
        <span class="badge bg-label-warning me-2">Full: ${response.fullLeaves}</span>
        <span class="badge bg-label-primary me-2">Half: ${response.halfLeaves}</span>
        <span class="badge bg-label-success">Off Days: ${response.offDays}</span>
      `);
    },
    error: function () {
      alert('Failed to fetch leave summary.');
    }
  });
}

</script>
@endpush
