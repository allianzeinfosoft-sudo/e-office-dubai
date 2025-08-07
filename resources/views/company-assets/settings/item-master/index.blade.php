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
                          <a class="btn btn-danger" href="{{ route('assets.dashboard') }}">
                                <i class="ti ti-home me-0 me-sm-1 ti-xs"></i>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="card">
                                <div class="card-datatable table-mom">
                                    <table class="datatables-basic datatable-item-master table border-top table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th width="8%">Sl.No</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Description</th>
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
                                    <h5 class="card-title" id="form-title">Add New Item</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('assets.itemmaster.store') }}" method="POST" enctype="multipart/form-data" id="item-master-form">
                                        @csrf
                                        <input type="hidden" name="id" id="target_id">

                                        <div class="mb-3">
                                            <label for="item_code" class="form-label">Item Code</label>
                                            <input type="text" class="form-control" id="item_code" name="item_code" required>
                                        </div>

                                         <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>

                                         <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description"></textarea>
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
        $('.datatable-item-master').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                type: "GET",
                url: "{{ route('assets.itemmaster.index') }}",
                dataType: "json",
                dataSrc: "data"
            },
            columns: [
                { data: 'row', name: 'No' },
                { data: 'item_code', name: 'Item Code' },
                { data: 'name', name: 'Item Name' },
                { data: 'description', name: 'Description'},
                {
                    data: null,
                    title: 'Actions',
                    render: function (data, type, row) {
                        return `
                            <a href="javascript:void(0)" onclick="editItemMaster(${row.id})" class="btn btn-sm btn-icon btn-primary">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deleteItemMaster(${row.id})">
                                <i class="ti ti-trash"></i>
                            </button>`;
                    }
                }
            ]
        });

        $('#item-master-form').submit(function(e) {
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
                        $('#form-title').text('Add New Item');
                        $('.datatable-item-master').DataTable().ajax.reload();
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

    function editItemMaster(id) {

        const url = "{{ route('assets.itemmaster.edit', ':assetItemMaster') }}".replace(':assetItemMaster', id);
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#form-title').text('Edit Item Master');
                    $('#target_id').val(response.data.id);
                    $('#item_code').val(response.data.item_code);
                    $('#name').val(response.data.name);
                    $('#description').val(response.data.description);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                alert('Error:'+ xhr.responseText);
            }
        });
    }

    function deleteItemMaster(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: "{{ route('assets.itemmaster.destroy', ':assetItemMaster') }}".replace(':assetItemMaster', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatable-item-master').DataTable().ajax.reload();
                },
                error: function() {
                    alert("Error deleting Item. Please try again.");
                }
            });
        }
    }


        document.getElementById('cancel-button').addEventListener('click', function () {
        document.getElementById('item-master-form').reset();
        document.getElementById('target_id').value = ''; // Optional: reset hidden field
        $('#form-title').text('Add New Item Master');
    });
</script>
@endpush
