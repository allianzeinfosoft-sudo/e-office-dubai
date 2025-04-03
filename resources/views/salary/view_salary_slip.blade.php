@extends('layouts.app')

@section('content')
 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <x-menu /> <!-- Load the menu component here -->

      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <x-header />
        <!-- / Navbar -->

        <!-- Content wrapper -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}

            </div>
        @endif

        <div class="content-wrapper">
          <!-- Content -->


          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Salary Slip</h4>
          <div class="card mb-4">
            <form id="SalaryForm" class="card-body" method="get" action="{{ route('fetch.salarySlip') }}">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                <div class="row g-3">
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <label for="select2Basic" class="form-label">Select Username:</label>
                            <select id="username" name="username" class="select2 form-select form-select-lg" data-allow-clear="true">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id ?? '' }}" {{ (old('username') == $user->id) ? 'selected' : '' }}>{{ $user->employee->full_name ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            @php
                                use App\Helpers\CustomHelper;
                                $helper = new CustomHelper();
                            @endphp
                            <label for="select2Basic" class="form-label">Select Month:</label>

                            <select id="select2Month" name="select2Month" class="select2 form-select form-select-lg" data-allow-clear="true">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}" {{ old('select2Month') == $month ? 'selected' : '' }}>
                                        {{  $helper->getMonthNames($month) }}  {{-- Ensure the function name is correct --}}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="select2Basic" class="form-label">Select Year:</label>
                            <select id="select2Year" name="select2Year" class="select2 form-select form-select-lg" data-allow-clear="true">
                            @for ($year = 2000 ; $year <= 2050 ; $year++)
                                <option value="{{ $year }}" {{ (old('select2Year') == $year) ? 'selected' : '' }}>{{ $year ?? '' }}</option>
                            @endfor
                            </select>
                        </div>
                    </div>
              </div>
              <div class="pt-4">
                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              </div>
            </form>
          </div>

          <div class="card">
            <div class="card-datatable table-responsive pt-0">
              <table class="salary-slip-table table">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>EmployeeName</th>
                    <th>Department</th>
                    <th>PF No</th>
                    <th>Created Date</th>
                    <th>Salary Month</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
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
  <!-- / Layout wrapper -->
@endsection


@section('js')
<script>
$(function () {

  var dataTableSalarySlip = $('.salary-slip-table'),
    dt_permission;
  // Users List datatable
  if (dataTableSalarySlip.length) {
    dt_salary_slip = dataTableSalarySlip.DataTable({
    // ajax: assetsPath + 'json/permissions-list.json', // JSON file to add data

    ajax: {
          url: "/fetch/salarySlip",
          type: "GET",
          dataType: "json",
          dataSrc: "data"
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'full_name', title: 'EmployeeName' },
        { data: 'department', title: 'Department' },
        { data: 'pf_no', title: 'PF No'},
        { data: 'created_date', title: 'Created Date'},
        { data: 'salary_slip_month', title: 'Salary Month' },
        { data: '' }
      ],

    });
  }

});
</script>
@stop
