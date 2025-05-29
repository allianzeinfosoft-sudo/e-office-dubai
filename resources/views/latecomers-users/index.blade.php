@extends('layouts.app')

@section('css')

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
                                <div class="col-md-3 mt-15">
                                    <label for="formDate">From</label>
                                    <input type="text" name="from_date" class="form-control" id="from_date" value="{{ date('d-m-Y') }}" />
                                </div>

                                <div class="col-md-3 mt-15">
                                    <label for="to_date">To</label>
                                    <input type="text" name="to_date" class="form-control" id="to_date" value="{{ date('d-m-Y') }}" />
                                </div>

                                <div class="col-md-3 mt-15">
                                    <button type="button" class="btn btn-primary mt-15">Filter</button>
                                </div>

                            </div>

                             <!-- Filter Result  -->   
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="datatables-basic datatables-custom-attendance table border-top table-stripedc table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="8%">Sl. No.</th>
                                                <th><i class="ti ti-users"></i></th>
                                                <th>Fullname</th>
                                                <th>Count</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>


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
        lateComersList();
        
    });

    function lateComersList() {
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();

        $('.datatables-basic').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                type: "GET",
                url: "{{ route('list-of-latecomers-data') }}",
                data: {
                    from_date: fromDate,
                    to_date: toDate,
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'Sl. No' },
                { data: 'user', name: '<i class="ti ti-users"></i>' },
                { data: 'fullname', name: 'Fullname' },
                { data: 'count', name: 'Count' },
                { data: 'actions', name: 'Actions' },
            ]
        });
    }
</script>
@endpush
