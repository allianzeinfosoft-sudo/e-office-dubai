@extends('layouts.app')

@section('css')
<style>
    .dt-buttons{
        float: left;
        margin-top: 5px;
        margin-left: 10px;
    }
    .dataTables_filter{
        float: right;
        margin-top: 5px;
        margin-right: 10px;
    }
</style>
@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4 text-muted"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <div class=" float-end mt-15 mr-20"></div>
                            
                            <!-- Filter -->
                            <div class="row mb-2">
                                {{-- From Date --}}
                                <div class="col-md-3 mt-15">
                                    <label for="from_month">Month</label>
                                    <div class="d-flex gap-1">
                                        <select name="from_month" id="from_month" class="form-control">
                                            @foreach (range(1, 12) as $month)
                                                <option value="{{ $month }}" {{ $month == date('n') ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="from_year" id="from_year" class="form-control">
                                            @for ($year = 2014; $year <= date('Y'); $year++)
                                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Button --}}
                                <div class="col-md-3 mt-15">
                                    <button type="button" onclick="incompleteList()" id="incompleteList" class="btn btn-primary mt-15">Filter</button>
                                </div>
                            </div>

                             <!-- Filter Result  -->   
                            <table class="datatables-basic datatables-late-comers table border-top table-stripedc table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th width="8%">Sl. No.</th>
                                        <th><i class="ti ti-users"></i></th>
                                        <th>Fullname</th>
                                        <th>Username</th>
                                        <th>Total Work Hrs.</th>
                                        <th>Worked Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>

                <!-- Overlay -->
                <div class="layout-overlay layout-menu-toggle"></div>

                <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function(){
        incompleteList();
    });

    function incompleteList() {
    const month = $('#from_month').val();
    const year = $('#from_year').val();

    $('.datatables-late-comers').DataTable().destroy();

    $('.datatables-late-comers').DataTable({
        processing: true,
        serverSide: false,
        dom: 'Bfrtip',
        ajax: {
            url: "{{ route('list-of-incomplete-work-data') }}",
            type: "GET",
            data: {
                month: month,
                year: year,
            }
        },
        buttons: [
            { extend: 'excelHtml5', title: 'Incomplete Working Hours Report of ' + month + ' ' + year },
            { extend: 'pdfHtml5', title: 'Incomplete Working Hours Report of ' + month + " " + year, orientation: 'landscape', pageSize: 'A4'},
            { extend: 'print', title: 'Incomplete Working Hours Report of ' + month + ' ' + year }
        ],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'profile_image', name: 'user' },
            { data: 'fullname', name: 'fullname' },
            { data: 'username', name: 'User Name' },
            { data: 'total_working_hours', name: 'Total Work Hrs' },
            { data: 'total_worked_hours', name: 'Worked Hours' },
            { data: 'status', name: 'Status' },
        ],
        columnDefs: [
            { orderable: false, targets: [1, 4] },
            { searchable: false, targets: [1, 4] }
        ]
    });
}

</script>
@endpush
