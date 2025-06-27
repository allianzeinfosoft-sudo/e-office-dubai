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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-7">
                            <div class="card">
                                <div class="card-datatable table-mom">
                                    <table class="datatables-basic datatable-category table border-top table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="8%">No.</th>
                                                <th>Name</th>
                                                <th width="15%">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>  
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="card card-bg">
                                <div class="card-header">
                                    <h5 class="card-title" id="form-title">Add New Category</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data" id="category-form">
                                        @csrf
                                        <input type="hidden" name="id" id="target_id">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Category Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <x-footer /> 
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $(function() {
        $('.datatable-category').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                type: "GET",
                url: "{{ route('category.index') }}",
                dataType: "json",
                dataSrc: "data"
            },
            columns: [
                { data: 'row', name: 'No' },
                { data: 'name', name: 'Name' },
                {
                    data: null,
                    title: 'Actions',
                    render: function (data, type, row) {
                        return `
                            <a href="javascript:void(0)" onclick="editCategory(${row.id})" class="btn btn-sm btn-icon btn-primary">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deleteCategory(${row.id})">
                                <i class="ti ti-trash"></i>
                            </button>`;
                    }
                }
            ]
        });

        $('#category-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.message);
                        form.trigger('reset');
                        $('#form-title').text('Add New Category');
                        $('.datatable-category').DataTable().ajax.reload();
                        $('#target_id').val('');
                    } else {
                        toastr["error"](response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON?.errors?.name?.[0]);
                }
            });
        });
    });

    function editCategory(id) {
        const url = "{{ route('category.edit', ':id') }}".replace(':id', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#form-title').text('Edit Category');
                    $('#target_id').val(response.data.id);
                    $('#name').val(response.data.name);
                } else {
                    alert(response.message);
                }
            }
        });
    }

    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: "{{ route('category.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatable-category').DataTable().ajax.reload();
                }
            });
        }
    }
</script>
@endpush
