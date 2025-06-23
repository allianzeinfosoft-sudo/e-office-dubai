@extends('layouts.app')

@section('css')
<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
        border-radius: 0px;
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">e-Library /</span> Issue Register</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openIssueOffcanvas()">
                                <i class="ti ti-plus me-1"></i> Issue Book
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable">
                                <table class="table table-hover datatable-book-issues">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book</th>
                                            <th>User</th>
                                            <th>Issue Date</th>
                                            <th>Return Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
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

<!-- Offcanvas: Issue Book -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="issue_offcanvas">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex align-items-center gap-2">
            <i class="ti ti-book fs-2 text-white"></i>
            <span>
                <h5 class="offcanvas-title text-white">Issue Book</h5>
                <span class="text-white slogan">Register new book issue</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <form id="issue-form">
            @csrf
            <div class="mb-3">
                <label for="book_id" class="form-label">Book</label>
                <select name="book_id" id="book_id" class="form-control select2" required>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->reg_no }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="issued_to" class="form-label">User</label>
                <select name="issued_to" id="issued_to" class="form-control select2" required>
                    @foreach($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="issue_date" class="form-label">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date" class="form-control" required>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Issue</button>
            </div>
        </form>
    </div>
</div>

<!-- Offcanvas: Return Confirmation -->
<div class="offcanvas offcanvas-end w-35" data-bs-backdrop="static" tabindex="-1" id="return_offcanvas">
    <div class="offcanvas-header bg-warning p-3">
        <span class="d-flex align-items-center gap-2">
            <i class="ti ti-backspace fs-2 text-dark"></i>
            <span>
                <h5 class="offcanvas-title text-dark">Return Book</h5>
                <span class="text-dark slogan">Mark this book as returned</span>
            </span>
        </span>
        <button type="button" class="btn btn-dark offcanvas-close" data-bs-dismiss="offcanvas"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body text-center">
        <p>Are you sure you want to return this book?</p>
        <input type="hidden" id="return_id">
        <div class="d-flex justify-content-center gap-3">
            <button onclick="submitReturn()" class="btn btn-success">Yes, Return</button>
            <button class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    const issueForm = $('#issue-form');
    const issueOffcanvas = new bootstrap.Offcanvas(document.getElementById('issue_offcanvas'));
    const returnOffcanvas = new bootstrap.Offcanvas(document.getElementById('return_offcanvas'));

    let bookTable;

    $(function() {
        bookTable = $('.datatable-book-issues').DataTable({
            ajax: "{{ route('e-library.book-issues.index') }}",
            columns: [
                { data: 'id' },
                { data: 'book.title' },
                { data: 'user.name' },
                { data: 'issue_date' },
                { data: 'return_date', defaultContent: '-' },
                { data: 'status' },
                {
                    data: null,
                    render: function(data) {
                        let btn = '';
                        if (data.status === 'issued') {
                            btn += `<button class="btn btn-sm btn-success" onclick="openReturnOffcanvas(${data.id})"><i class="ti ti-rotate"></i></button>`;
                        }
                        return btn;
                    }
                }
            ]
        });

        issueForm.on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('e-library.book-issues.store') }}",
                method: 'POST',
                data: issueForm.serialize(),
                success: function(res) {
                    toastr.success(res.message || "Book issued successfully");
                    issueOffcanvas.hide();
                    issueForm[0].reset();
                    bookTable.ajax.reload();
                },
                error: function(err) {
                    toastr.error("Issue failed");
                }
            });
        });
    });

    function openIssueOffcanvas() {
        issueForm[0].reset();
        issueOffcanvas.show();
    }

    function openReturnOffcanvas(id) {
        $('#return_id').val(id);
        returnOffcanvas.show();
    }

    function submitReturn() {
        const id = $('#return_id').val();
        $.ajax({
            url: `/e-library/return-book/${id}`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                toastr.success(res.message || "Returned");
                returnOffcanvas.hide();
                bookTable.ajax.reload();
            },
            error: function() {
                toastr.error("Failed to return book");
            }
        });
    }
</script>
@endpush
