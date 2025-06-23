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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">e-Library /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New Category</span>
                                </span>
                            </a>
                        </div>
                            @if($categories->count() > 0)
                                @foreach($categories as $category)
                                    <div class="col-xl-3 col-md-4 mb-4">
                                        <div class="card card-bg h-100">
                                            <div class="card-header d-flex justify-content-between">
                                                <div class="card-title mb-0">
                                                    <h5 class="mb-0"> {{ $category->name ?? 'N/A'}} </h5>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="earningReportsId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId" style="">
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="openOffcanvas('{{ $category->id }}')"><i class="ti ti-pencil me-1 ti-sm"></i> Edit</a>
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="deleteCategory('{{ $category->id }}')"><i class="ti ti-trash me-1 ti-sm text-danger"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                            <ul class="p-0 m-0">
                                                <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                                                <div class="badge bg-label-success rounded p-2"><i class="ti ti-books ti-sm"></i></div>
                                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                                    <h6 class="mb-0 ms-3">Total Books</h6>
                                                    <div class="d-flex">
                                                    <p class="mb-0 fw-semibold">{{ $category->total_books }}</p>
                                                    <p class="ms-3 text-success mb-0">Nos</p>
                                                    </div>
                                                </div>
                                                </li>
                                                <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                                                <div class="badge bg-label-info rounded p-2"><i class="ti ti-book ti-sm"></i></div>
                                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                                    <h6 class="mb-0 ms-3">Issued</h6>
                                                    <div class="d-flex">
                                                    <p class="mb-0 fw-semibold">{{ $category->issued_books }}</p>
                                                    <p class="ms-3 text-success mb-0">Nos</p>
                                                    </div>
                                                </div>
                                                </li>
                                                <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                                                <div class="badge bg-label-warning rounded p-2"><i class="ti ti-bookmark ti-sm"></i></div>
                                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                                    <h6 class="mb-0 ms-3">Damage/Lost</h6>
                                                    <div class="d-flex">
                                                    <p class="mb-0 fw-semibold">{{ $category->damaged_books }}</p>
                                                    <p class="ms-3 text-success mb-0">Nos.</p>
                                                    </div>
                                                </div>
                                                </li>
                                                <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                                                <div class="badge bg-label-primary rounded p-2"><i class="ti ti-archive ti-sm"></i></div>
                                                <div class="d-flex justify-content-between w-100 flex-wrap">
                                                    <h6 class="mb-0 ms-3">Stock</h6>
                                                    <div class="d-flex">
                                                    <p class="mb-0 fw-semibold">{{ $category->available_books }}</p>
                                                    <p class="ms-3 text-success mb-0">Nos</p>
                                                    </div>
                                                </div>
                                                </li>
                                            </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            
                    </div>
                </div>

                <x-footer /> 
                <div class="content-backdrop fade"></div>
                <div class="layout-overlay layout-menu-toggle"></div>
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="category_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="category-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Book Category</h5>
                <span class="text-white slogan">Create New Category</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-books-category-form />
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    
    $(function(){
        
        $('#books-category-form').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);
            const url = form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
                },
                success: function (response) {
                    toastr["success"](response.message);
                    form.trigger('reset');
                    const offcanvasElement = document.getElementById('category_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) offcanvas.hide();
                    window.location.reload();
                },
                error: function (xhr) {
                    let message = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors).join('\n');
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr["error"](message);
                },
                complete: function () {
                    form.find('button[type="submit"]').prop('disabled', false).text('Save');
                }
            });
        });

    });

    function openOffcanvas(id = null) {
        const $form = $('#books-category-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#category_offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create KSP</h5><span class="text-white slogan">Create New Knowledge Sharing Program</span>`);

        const offcanvasElement = $('#category_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if(id) {
            const url = "{{ route('e-library.categories.edit', ':id') }}".replace(':id', id);
            $('#target_id').val(id);
            $('#category_offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit KSP</h5><span class="text-white slogan">Edit Knowledge Sharing Program</span>`);
            $('#current-attachment').remove();
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#name').val(data.category.name);
                    $('#parent_id').val(data.category.parent_id).trigger('change');
                },
                error: function () {
                    alert('Failed to load book category data.');
                }
            });
        }
    }

    function addCategoryModal() {
        $('#addCategoryModal').modal('show');
    }

  
    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this book category?')) {
            $.ajax({
                url: "{{ route('e-library.categories.destroy', ':id') }}".replace(':id', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                },
                error: function() {
                    alert("Error deleting categotry. Please try again.");
                }
            });
        }
    }


</script>
@endpush
