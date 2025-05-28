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
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openOffcanvas()">
                                <!-- {{ route('project.create') }} -->
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-basic datatables-productivity-targets table border-top table-stripedc">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Task</th>
                                        <th>Assigned By</th>
                                        <th>Target Date</th>
                                        <th>Rec/hr</th>
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
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="productivity_target_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Create Productivity Target</h5>
                <span class="text-white slogan">Create New Productivity Target</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-productivity-target-form  action="{{ route('productivity-target.store') }}" method='post' />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
    $(function(){
        var productivityTagetTable = $('.datatables-productivity-targets');

        if(productivityTagetTable.length){
            productivityTagetTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('productivity-target.index') }}", // Fixed syntax
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'projectName', title: 'Project' },
                    { data: 'projectTask', title: 'Task' },
                    { data: 'employee', title: 'Assigned By' },
                    { data: 'target_year', title: 'Target Date',
                        render: function (data, type, row) {
                            return `<span>${row.target_month}/ ${row.target_year}</span>`;
                        }
                     },
                    { data: 'rph', title: 'Rec/hr' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-project" onclick="openOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger delete-project" onclick="deleteProductivityTarget(${row.id})" data-id="${row.id}"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    }
                ]
            });
        }

    });

    function openOffcanvas(targetId = null) {
    $('#productivityTargetForm')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Productivity Target</h5><span class="text-white slogan">Edit Productivity Target</span>`);
        $.ajax({
            url: `/productivity-target/${targetId}/edit`,
            type: 'GET',
            success: function (data) {
                console.log(data.target_year);
                const target_month = data.target_year+'-'+data.target_month;
                $('#target_id').val(data.id);
                $('#project_id').val(data.project_id).trigger('change');
                setTimeout(() => {
                    $('#project_task_id').val(data.project_task_id).trigger('change');
                     setTimeout(() => {
                        $('#assignedBy').val(data.assignedBy).trigger('change');
                    }, 300);

                }, 300);



                $('#rph').val(data.rph);
                $('#target_year').val(target_month).trigger('change');

            }
        });
    }

    var offcanvasElement = $('#productivity_target_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

function deleteProductivityTarget(id) {
    if (!confirm("Are you sure you want to delete this productivity target?")) {
        return;
    }

    $.ajax({
        url: "{{ url('productivity-target') }}/" + id,
        type: "DELETE",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.success) {
                //alert(response.message || "Deleted successfully!");
                toastr["error"](response.message);
                // Refresh the DataTable
                $('.datatables-productivity-targets').DataTable().ajax.reload(null, false);
            } else {
               // alert(response.message || "Something went wrong!");
                toastr["error"](response.message);
            }
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
}

</script>
@endpush
