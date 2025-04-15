@extends('layouts.app')

@section('css')
<style>
.w-35 {
    width: 35% !important;
}
.w-45 {
    width: 45% !important;
}
.offcanvas-close{
    position: absolute;
    top: 0px;
    left: -32px;  /* Moves the button outside the offcanvas */
    z-index: 1055; /* Ensures it stays on top */
    padding: 28px 10px;
    border-radius: 0px;
}
</style>
@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <!-- {{ route('project.create') }} -->
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-basic datatables-thoughts table border-top table-stripedc">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Details</th>
                                        <th>Display Date</th>
                                        <th>Create Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>


                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->
            </div>
        </div>
    </div>
</div>

<!-- Add Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="project_tasks_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Thought</h5>
                <span class="text-white slogan">Create New Thought</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-thoughts-form action="{{ route('thoughts.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function() {

        var thoughtsTable = $('.datatables-thoughts'),
        select2 = $('.select2');
        if (thoughtsTable.length) {

            thoughtsTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('thoughts.index') }}", // Fixed syntax
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [

                    { data: 'picture', title: 'Image'},
                    { data: 'thoughts_title', title: 'Title' },
                    { data: 'thoughts_detatils', title: 'Details' },
                    { data: 'display_date', title: 'Display Date' },
                    { data: 'created_at', title: 'Create Date'},
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            const editUrl = "{{ route('thoughts.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-thoughts" onclick="openOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger delete-thoughts" onclick="deleteThoughts(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        }
    });

    function deleteThoughts(thought) {
        if (confirm('Are you sure you want to delete this Task?')) {
            $.ajax({
                url: "{{ route('thoughts.destroy', ':Thoughts') }}".replace(':Thoughts', thought), // ✅ Correct route name
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert(response.message);
                    $('.datatables-thoughts').DataTable().ajax.reload(); // ✅ Ensure correct table ID
                    window.location.reload();
                },
                error: function(xhr) {
                    alert("Error deleting project. Please try again.");
                }
            });
        }
    }


function openOffcanvas(targetId = null) {
    $('#thoughts-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    // if (targetId) {
    //     $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Thoughts</h5><span class="text-white slogan">Edit New Thoughts</span>`);
    //     $.ajax({
    //         url: `/thoughts/${targetId}/edit`,
    //         type: 'GET',
    //         success: function (data) {
    //             $('#target_id').val(data.projectTask.id);
    //             $('#thoughts_title').val(data.projectTask.task_name);
    //             $('#display_date').val(data.projectTask.project_id);
    //             $('#details').val(data.projectTask.reporting_to);
    //             $('#picture').val(data.thouthgt.)
    //         }
    //     });
    // }
    var offcanvasElement = $('#project_tasks_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

</script>
@endpush
