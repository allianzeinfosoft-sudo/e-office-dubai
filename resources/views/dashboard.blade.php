@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/ui-carousel.css') }}" />
<style>
  .gallery-thumbs {
      height: 90px; /* Adjust overall height */
      padding: 10px 0;
      box-sizing: border-box;
  }

  /* Each thumbnail */
  .gallery-thumbs .swiper-slide {
      width: 70px;              /* Fixed thumbnail width */
      height: 70px;             /* Fixed thumbnail height */
      background-size: cover;   /* Cover entire area */
      background-position: center;
      background-repeat: no-repeat;
      border-radius: 8px;
      cursor: pointer;
      opacity: 0.5;
      transition: opacity 0.3s ease;
  }

  .gallery-thumbs .swiper-slide-thumb-active {
      opacity: 1;
      border: 2px solid #007bff; /* Highlight active */
  }
</style>
@endsection

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
                        <img
                            src="{{ $employee && $employee->profile_image ? asset('storage/' . $employee->profile_image) : asset('assets/img/avatars/default-avatar.png') }}"
                            alt="Avatar Image"
                            class="rounded-circle w-px-100"
                        >
                      </div>
                      <h5 class="mb-2">{{ $employee->full_name ?? '' }}</h5>
                      <div class="d-flex gap-3 mb-2 justify-content-center text-primary fw-bold">
                        <span><i class="ti ti-color-swatch mt-n2"></i> {{ $employee->department->department ?? '' }}</span>
                        <span><i class="ti ti-user mt-n2"></i> {{ $employee->designation->designation ?? '' }} </span>
                      </div>
                      <span><i class="ti ti-mail mt-n1 me-1"></i> <a href="mailto:{{ $employee->user->email ?? ''}}"> {{ $employee->user->email ?? ''}} </a></span>
                      <div class="d-flex justify-content-center mt-2 mb-3">
                        <span class="fw-bold"><i class="ti ti-calendar mt-n2"></i>Date of Join : &nbsp;</span><span>{{ $employee && $employee->join_date ? date('d-m-Y', strtotime($employee->join_date)) : ''  }}</span>
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
                              <h4 class="mb-0 me-2" id="leaveCount">{{ round($leaveCount,1) }}</h4>
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
                        <small class="text-muted"></small>
                      </div>
                      <div class="dropdown d-none d-sm-flex">
                        {{-- <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="ti ti-calendar"></i>
                        </button> --}}
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
                              <h5 class="mb-0 me-2" id="totalLeavesTaken">0</h5>
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
                              <h5 class="mb-0 me-2" id="leaveBalance">0</h5>
                              <small>Leave Balance</small>
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
                              <h5 class="mb-0 me-2" id="pendingLeaves">0</h5>
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
                              <h5 class="mb-0 me-2" id="totalLeavesAllotted">0</h5>
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
                              <h5 class="mb-1" id="pastYearLeaves">0</h5>
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
                                <span class="badge bg-label-warning me-2" id="full_leave_category">Full: 0</span>
                                <span class="badge bg-label-primary me-2" id="half_leave_category">Half: 0</span>
                                <span class="badge bg-label-success" id="off_leave_category">Off Days: 0</span>
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

                {{-- If Current Feeds Birthdays and Appriciations  will show else not--}}
               @if(!empty($feed_data))
                  <div class="col-md-6 col-12 mt-15">
                      <div class="card card-bg">
                          <div class="card-body p-0">
                              <div class="text-center">
                                  <!-- Main Swiper -->
                                  <div class="swiper gallery-top bday-card">
                                      <div class="swiper-wrapper">
                                          @foreach ($feed_data as $item)

                                              @if($item['type'] == 'birthday')
                                                  {{-- Each employee = one slide --}}
                                                  @foreach($item['employees'] as $employee)
                                                      <div class="swiper-slide bday-card">
                                                          <canvas class="confetti-canvas" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:10;"></canvas>
                                                          <div class="card-bday">
                                                              <img class="bdy-img mt-5 rounded-circle"
                                                                  src="{{ asset('storage/' . ($employee['profile_image'] ?? 'profile_pics/default-avatar.png')) }}"
                                                                  alt="{{ $employee['full_name'] }}" width="100" height="150">
                                                                <br>
                                                                     <span class="bday-name">{{ $employee['full_name'] }}</span>
                                                          </div>
                                                          <p class="bdy-name mt-2">{{ $employee['full_name'] }}</p>

                                                      </div>
                                                  @endforeach
                                              @else
                                                  {{-- Appreciation = one slide --}}
                                                  <div class="swiper-slide card-app">
                                                      <div class="card-app-content p-3">
                                                        {{-- <div class="appreciation-employees mt-3">
                                                            @foreach($item['employees'] as $employee)
                                                                <img class="rounded-circle mx-1"
                                                                    src="{{ asset('storage/' . ($employee['profile_image'] ?? 'profile_pics/default-avatar.png')) }}"
                                                                    title="{{ $employee['full_name'] }}" width="100" height="100">
                                                                    <br>
                                                                     <span class="bday-name">{{ $employee['full_name'] }}</span>
                                                            @endforeach
                                                        </div> --}}
                                                        <div class="appreciation-employees mt-3 d-flex flex-wrap align-items-center justify-content-center gap-3">
                                                            @foreach($item['employees'] as $employee)
                                                                <div class="text-center">
                                                                    <img class="rounded-circle"
                                                                        src="{{ asset('storage/' . ($employee['profile_image'] ?? 'profile_pics/default-avatar.png')) }}"
                                                                        title="{{ $employee['full_name'] }}"
                                                                        width="80" height="80">
                                                                    <div class="bday-name mt-2">{{ $employee['full_name'] }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <div class="cng-img text-center">
                                                            <img class="w-40" src="../../assets/img/backgrounds/cng.png">
                                                            <img class="w-25" src="{{ asset($item['image'] ? '/storage/appreciation_flowers/'.$item['image'] : '/assets/avatars/default-avatar.png') }}" alt="Appreciation Background">
                                                        </div>
                                                        <p class="fs-6 text-truncate" style="max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            {!! strip_tags($item['message']) !!}
                                                        </p>
                                                        <div class="text-end">
                                                            <a href="{{ route('view_appreciation') }}" class="btn btn-primary btn-sm">Read More..</a>
                                                        </div>
                                                      </div>
                                                  </div>
                                              @endif
                                          @endforeach
                                      </div>

                                      <!-- Swiper Navigation -->
                                      <div class="swiper-button-next swiper-button-white"></div>
                                      <div class="swiper-button-prev swiper-button-white"></div>



                                  </div>

                                  <!-- Thumbnail Swiper -->
                                  @if(count($feed_data)> 1)
                                  <div class="swiper gallery-thumbs">

                                        <div class="swiper-wrapper">
                                            {{-- @foreach ($feed_data as $item)
                                                @if($item['type'] == 'birthday' || $item['type'] == 'appreciation')
                                                    @if(count($item['employees']) > 1)
                                                        @foreach($item['employees'] as $employee)
                                                            <div class="swiper-slide" style="background-image: url('{{ asset('storage/' . ($employee['profile_image'] ?? '/assets/avatars/default-avatar.png')) }}')"></div>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <div class="swiper-slide" style="background-image: url('{{ asset($item['image'] ? '/storage/appreciation_flowers/'.$item['image'] : '/assets/avatars/default-avatar.png') }}')"></div>
                                                @endif
                                            @endforeach --}}
                                        </div>
                                    </div>
                                    @else

                                             @foreach ($feed_data as $item)
                                                @if($item['type'] == 'birthday')
                                                    @if(count($item['employees']) > 1)
                                                        @foreach($item['employees'] as $employee)
                                                            <div class="swiper-slide" style="background-image: url('{{ asset('storage/' . ($employee['profile_image'] ?? '/assets/avatars/default-avatar.png')) }}')"></div>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <div class="swiper-slide" style="background-image: url('{{ asset($item['image'] ? '/storage/appreciation_flowers/'.$item['image'] : '/assets/avatars/default-avatar.png') }}')"></div>
                                                @endif
                                            @endforeach

                                    @endif




                              </div>
                          </div>
                      </div>
                  </div>

                  @else

                  <!--Current Productive Time Analytics -->
                  <div class="col-md-6 col-12 my-auto">
                    <div class="card card-bg">
                      <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                          <h5 class="card-title mb-0">Current Productive Time Analytics</h5>
                          <small class="text-muted">{{ date('F') }}</small>
                        </div>
                      </div>
                      <div class="card-body pb-5">

                        <x-charts.attendance-donut-chart
                          id="attendanceDonut"
                          :labels="['Outstanding', 'Very Good', 'Good', 'Above Average', 'Average', 'Poor']"
                          :donutsData="[$work_analysis['Outstanding'], $work_analysis['Very Good'], $work_analysis['Good'], $work_analysis['Above Average'], $work_analysis['Average'], $work_analysis['Poor']]"
                          :backgroundColors="['#fee802', '#3fd0bd', '#826bf8', '#2b9bf4', '#f86624', '#ea5455']"
                          height="360px"
                        />
                      </div>
                    </div>
                  </div>

                  @endif



                {{--  --}}

                <!--Current Productive Time Analytics -->

                <!--current attendence Analytics -->
                {{-- <div class="col-12 mt-3" style="visibility: hidden">
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
                </div> --}}
                <!--current attendence Analytics -->

                <!-- Holiday List-->
                <div class="col-xl-6 col-md-6 col-6 mt-3">
                  <div class="card card-bg">
                    <div class="card-header d-flex justify-content-between align-items-md-center align-items-start">
                      <h5 class="card-title mb-0">Holiday List</h5>
                    </div>
                    <div class="card-datatable">
                      <div class="table-responsive">
                        <table class="table table-striped table-hover table-highlight table-checkable table-holiday" data-provide="datatable" data-display-rows="25" data-info="true" data-search="true" data-length-change="true" data-paginate="true">
                            <thead>
                              <tr>
                                <th data-sortable="true" data-direction="asc">SL</th>
                                <th data-sortable="true">Name of Holiday</th>
                                <th data-sortable="true">Date of Holiday</th>
                              </tr>
                            </thead>
                            <tbody>
                              @if($holidays->count() > 0)
                                @foreach($holidays as $key => $holiday)
                                  <tr class="{{ $key + 1 }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td> {{ $holiday->name ?? '' }}</td>
                                    <td> {{ date('d F Y', strtotime($holiday->date)) ??  '' }}</td>
                                  </tr>
                                @endforeach
                              @endif

                              <!-- <tr class="162">
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
                              </tr>      -->
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
                      <h5 class="card-title mb-0">HOD List</h5>
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
                                <th data-sortable="true">POC (Point of Contact)</th>
                                <th data-sortable="true">HOD</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>SDU</td>
                                    <td>Nandu Sreekantan (<a href="mailto:{{ 'nandus@mail.allianzegroup.com' ?? ''  }}">{{ 'nandus@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Binoj Nair (<a href="mailto:{{ 'binojn@mail.allianzegroup.com' ?? ''  }}">{{ 'binojn@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Debt Accounts</td>
                                    <td>Muhammad Shafi (<a href="mailto:{{ 'muhammedshafi@mail.allianzegroup.com' ?? ''  }}">{{ 'muhammedshafi@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Binoj Nair (<a href="mailto:{{ 'binojn@mail.allianzegroup.com' ?? ''  }}">{{ 'binojn@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Engineering</td>
                                    <td>Alwin Joy (<a href="mailto:{{ 'alwinj@mail.allianzegroup.com' ?? ''  }}">{{ 'alwinj@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Binoj Nair (<a href="mailto:{{ 'binojn@mail.allianzegroup.com' ?? ''  }}">{{ 'binojn@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>

                                 <tr>
                                    <td>BPO</td>
                                    <td>Yedu Rajeev (<a href="mailto:{{ 'yedur@mail.allianzegroup.com' ?? ''  }}">{{ 'yedur@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Danish Charals (<a href="mailto:{{ 'danishc@mail.allianzegroup.com' ?? ''  }}">{{ 'danishc@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>SEO</td>
                                    <td>Jaison Thomas (<a href="mailto:{{ 'jaisont@mail.allianzegroup.com' ?? ''  }}">{{ 'jaisont@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Danish Charals (<a href="mailto:{{ 'danishc@mail.allianzegroup.com' ?? ''  }}">{{ 'danishc@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Editorial</td>
                                    <td>Jaison Thomas (<a href="mailto:{{ 'jaisont@mail.allianzegroup.com' ?? ''  }}">{{ 'jaisont@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Danish Charals (<a href="mailto:{{ 'danishc@mail.allianzegroup.com' ?? ''  }}">{{ 'danishc@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Designing</td>
                                    <td>Jaison Thomas (<a href="mailto:{{ 'jaisont@mail.allianzegroup.com' ?? ''  }}">{{ 'jaisont@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Danish Charals (<a href="mailto:{{ 'danishc@mail.allianzegroup.com' ?? ''  }}">{{ 'danishc@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Human Resources</td>
                                    <td>Sujatha Menon (<a href="mailto:{{ 'sujathap@mail.allianzegroup.com' ?? ''  }}">{{ 'sujathap@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Sujatha Menon (<a href="mailto:{{ 'sujathap@mail.allianzegroup.com' ?? ''  }}">{{ 'sujathap@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Administration</td>
                                    <td>Sujatha Menon (<a href="mailto:{{ 'sujathap@mail.allianzegroup.com' ?? ''  }}">{{ 'sujathap@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Sujatha Menon (<a href="mailto:{{ 'sujathap@mail.allianzegroup.com' ?? ''  }}">{{ 'sujathap@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>IT Tech Support</td>
                                    <td>Vishnu Panicker (<a href="mailto:{{ 'vishnukp@mail.allianzegroup.com' ?? ''  }}">{{ 'vishnukp@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Vishnu Panicker (<a href="mailto:{{ 'vishnukp@mail.allianzegroup.com' ?? ''  }}">{{ 'vishnukp@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Finance and Accounts</td>
                                    <td>Nirmal Sebastian (<a href="mailto:{{ 'nirmals@mail.allianzegroup.com' ?? ''  }}">{{ 'nirmals@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Nirmal Sebastian (<a href="mailto:{{ 'nirmals@mail.allianzegroup.com' ?? ''  }}">{{ 'nirmals@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>
                                 <tr>
                                    <td>Marketing</td>
                                    <td>Anuraj A N (<a href="mailto:{{ 'anuraja@mail.allianzegroup.com' ?? ''  }}">{{ 'anuraja@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                    <td>Anuraj A N (<a href="mailto:{{ 'anuraja@mail.allianzegroup.com' ?? ''  }}">{{ 'anuraja@mail.allianzegroup.com' ?? ''  }} </a>)</td>
                                </tr>

                              {{-- @if($uniqueTeamLeads->isNotEmpty())
                                @foreach($uniqueTeamLeads as $uniqueTeamLead)
                                <tr class="{{ $uniqueTeamLead->id }}">
                                  <td>{{ $uniqueTeamLead->department->department ?? '' }}</td>
                                  <td> {{ $uniqueTeamLead->full_name ?? '' }} </td>
                                  <td><a href="mailto:{{ $uniqueTeamLead->user->email ?? ''  }}">{{ $uniqueTeamLead->user->email ?? ''  }} </a>	</td>
                                </tr>
                                @endforeach
                              @endif
                                <tr>
                                    <td>Finance and Accounts</td>
                                    <td>Nirmal Sebastian</td>
                                    <td><a href="mailto:{{ 'nirmals@mail.allianzegroup.com' ?? ''  }}">{{ 'nirmals@mail.allianzegroup.com' ?? ''  }} </a></td>
                                </tr> --}}
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
<script src="{{ asset('assets/js/ui-carousel.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
  $(function () {
    $('.table-holiday').DataTable();

    var galleryThumbs = new Swiper('.gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 'auto', // Flexible thumbnails
        freeMode: true,
        watchSlidesProgress: true,
    });

    var galleryTop = new Swiper('.gallery-top', {
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 4000, // 4 seconds per slide
            disableOnInteraction: false, // keep autoplay even after user interaction
        },
        speed: 800, // smooth slide transition
        loop: true, // Infinite scroll
        thumbs: {
            swiper: galleryThumbs
        }
    });

  });

  updateLeaveSummary('current_month');

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
      $('#leaveBalance').text(response.leaveBalance);
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
