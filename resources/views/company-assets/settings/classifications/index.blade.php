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
                        <div class="md-4 mb-2">
                        <a class="btn btn-primary" href="{{route('assets.dashboard'); }}">Assets Dashboad</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="card">
                                <div class="card-datatable table-mom">
                                    <table class="datatables-basic datatable-classification table border-top table-hover table-striped">
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
                                    <h5 class="card-title" id="form-title">Add New Classification</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('classification.store') }}" method="POST" enctype="multipart/form-data" id="classification-form">
                                        @csrf
                                        <input type="hidden" name="id" id="target_id">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Classification</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <button type="button" class="btn btn-secondary" id="cancel-button">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>

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

@stop

@push('js')
<script>
    $(function() {
        $('.datatable-classification').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                type: "GET",
                url: "{{ route('classification.index') }}",
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
                            <a href="javascript:void(0)" onclick="editClassification(${row.id})" class="btn btn-sm btn-icon btn-primary">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deleteClassification(${row.id})">
                                <i class="ti ti-trash"></i>
                            </button>`;
                    }
                }
            ]
        });

        $('#classification-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var formData = new FormData(this);
            var url = form.attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        toastr["success"](response.message);
                        form.trigger('reset');
                        $('#form-title').text('Add New Classification');
                        $('.datatable-classification').DataTable().ajax.reload();
                        $('#target_id').val('');
                    } else {
                        toastr["error"] (response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    alert('Error: ' + errors.name[0]);
                }
            });
        });
    });

    function editClassification(id) {
        const url = "{{ route('classification.edit', ':assetClassification') }}".replace(':assetClassification', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#form-title').text('Edit Classification');
                    $('#target_id').val(response.data.id);
                    $('#name').val(response.data.name);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error:'+ xhr.responseText);
            }
        });
    }

    function deleteClassification(id) {
        if (confirm('Are you sure you want to delete this classification?')) {
            $.ajax({
                url: "{{ route('classification.destroy', ':assetClassification') }}".replace(':assetClassification', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatable-classification').DataTable().ajax.reload();
                },
                error: function() {
                    alert("Error deleting classification. Please try again.");
                }
            });
        }
    }


    document.getElementById('cancel-button').addEventListener('click', function () {
        document.getElementById('classification-form').reset();
        document.getElementById('target_id').value = ''; // Optional: reset hidden field
        $('#form-title').text('Add New Classification');
    });


</script>
@endpush
