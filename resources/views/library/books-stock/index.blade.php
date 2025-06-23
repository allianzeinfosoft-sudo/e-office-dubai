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
    
    .book-cover {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold"><span class="text-muted fw-light">e-Library /</span> Book Listing</h4>
                        <a class="btn btn-primary" href="javascript:void(0);" onclick="openBookStockOffcanvas()">
                            <i class="ti ti-plus me-1"></i> Add Book
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <div class="card card-bg">
                                <div class="card-body">

                                    <form method="GET" action="{{ route('e-library.books') }}" class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <input type="text" name="reg_no" value="{{ request('reg_no') }}" class="form-control" placeholder="Search by Reg. No">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="title" value="{{ request('title') }}" class="form-control" placeholder="Search by Title">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="author" value="{{ request('author') }}" class="form-control" placeholder="Search by Author">
                                        </div>
                                        <div class="col-md-3">
                                            <select name="category_id" class="form-select">
                                                <option value="">All Categories</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-secondary me-2" type="submit"><i class="ti ti-search"></i> Filter</button>
                                            <a href="{{ route('e-library.books') }}" class="btn btn-outline-secondary"><i class="ti ti-refresh"></i> Reset</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Book Cards --}}
                    <div class="row">
                        @forelse($books as $book)
                            <div class="col-xl-3 col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        @if($book->cover)
                                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="book-cover mb-3">
                                        @endif
                                        <h6 class="fw-bold mb-1">{{ $book->title }}</h6>
                                        <small class="text-muted d-block mb-1">Reg No: {{ $book->reg_no }}</small>
                                        <p class="mb-2">{{ \Illuminate\Support\Str::limit(strip_tags($book->description), 80) }}</p>
                                        <span class="badge bg-primary">{{ $book->category->name ?? 'Uncategorized' }}</span>

                                        <div class="mt-3 d-flex justify-content-between">
                                            <button class="btn btn-sm btn-warning" onclick="openBookStockOffcanvas({{ $book->id }})">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteBook({{ $book->id }})">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning">No books found matching your criteria.</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <x-footer />
            </div>
        </div>
    </div>
</div>

@include('library.books-stock.create') {{-- Offcanvas for book form --}}
@stop

@push('js')
<script>
    function openBookStockOffcanvas(id = null) {
        const $form = $('#book-stock-form');
        $form[0].reset();
        $('#book_id').val('');
        $('#book_offcanvas .offcanvas-title').text('Add Book');

        const offcanvas = new bootstrap.Offcanvas($('#book_offcanvas')[0]);
        offcanvas.show();

        if (id) {
          
            const url = "{{ route('e-library.edit', ':id') }}".replace(':id', id);
            $.get(url, function (data) {
                $('#book_id').val(data.book.id);
                $('#book_title').val(data.book.title);
                $('#book_author').val(data.book.author);
                $('#book_category').val(data.book.category_id).trigger('change');
                $('#book_description').val(data.book.description);
                $('#reg_no').val(data.book.reg_no);
                $('#book_offcanvas .offcanvas-title').text('Edit Book');
            });
        }
    }

    function deleteBook(id) {
        if (confirm('Are you sure you want to delete this book?')) {
            $.ajax({
                url: "{{ route('e-library.book-destroy', ':id') }}".replace(':id', id),
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                success: function (response) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 800);
                },
                error: function () {
                    toastr.error("Failed to delete book.");
                }
            });
        }
    }
</script>
@endpush
