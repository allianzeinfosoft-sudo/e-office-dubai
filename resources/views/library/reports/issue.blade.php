@extends('layouts.app')

@section('css')
<style>
    .dt-buttons{
        float: left;
        margin-top: 10px;
        margin-left: 10px;
    }
</style>
@stop

@section('content')

<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <x-menu />
        <div class="layout-page">
            <x-header />
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">e-Library /</span> Issue Report</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row gy-2 gx-3 align-items-center">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="from_date" id="from_date">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="to_date" id="to_date">
                </div>
                <div class="col-md-3">
                    <label for="book_id" class="form-label">Book</label>
                    <select class="form-select" name="book_id" id="book_id">
                        <option value="">All</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="employee_id" class="form-label">Issued To</label>
                    <select class="form-select" name="employee_id" id="employee_id">
                        <option value="">All</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mt-4">
                    <button type="submit" class="btn btn-primary mt-2">Filter</button>
                    <button type="button" id="resetBtn" class="btn btn-outline-secondary mt-2">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable">
            <table class="table table-striped" id="issueReportTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Issued To</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

                <x-footer />
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')
<script>
$(function () {
    const table = $('#issueReportTable').DataTable({
         dom: 'Bfrtip',
         buttons: [
            { extend: 'excelHtml5', title: 'All Books Issue Report'},
            { extend: 'pdfHtml5', title: 'All Books Issue Report', orientation: 'portrait', pageSize: 'A4'},
            { extend: 'print', title: 'All Books Issue Report'}
        ],
        processing: false,
        serverSide: false,
        ajax: {
            url: "{{ route('e-library.reports.issue-report-data') }}",
            data: function (d) {
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
                d.book_id = $('#book_id').val();
                d.author = $('#author').val();
                d.employee_id = $('#employee_id').val();
            }
        },
        dataSrc: "data",
        columns: [
            { data: 'row', name: '#', orderable: false, searchable: false },
            { data: 'book_title', name: 'Book' },
            { data: 'book_author', name: 'Author' },
            { data: 'employee_name', name: 'Issued To' },
            { data: 'issue_date', name: 'Issue Date' },
            { data: 'return_date', name: 'Return Date' },
            { data: 'status_label', name: 'Status' }
        ]
    });

    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });

    $('#resetBtn').on('click', function () {
        $('#filterForm')[0].reset();
        table.ajax.reload();
    });
});
</script>
@endpush
