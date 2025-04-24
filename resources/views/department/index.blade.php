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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openDepartmentOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-department table border-top table-stripedc" id="datatables-department">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Department Name</th>
                                        <th>Designation Name</th>
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

<!-- Add Banner -->
<div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="department_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Department</h5>
                <span class="text-white slogan">Create New Department</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-department-form action="{{ route('department.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>

    document.addEventListener("DOMContentLoaded", function () {


        const form = document.getElementById('department-form');
        form.addEventListener('submit', function (e) {

            e.preventDefault();

            const branch = document.getElementById('basicBranchname3').value.trim();
            const department = document.getElementById('department').value.trim();
            const designation = document.getElementById('designation').value.trim();
            let errors = [];

            if (!branch) {
                errors.push("Branch name is required.");
            }

            if (!department) {
                errors.push("Department name is required");
            }

            if (!designation) {
                errors.push("Designation name is required");
            }


            let errorBox = document.getElementById('formErrors');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'formErrors';
                errorBox.className = 'alert alert-danger mt-3';
                form.prepend(errorBox);
            }

            if (errors.length > 0) {
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else {
                errorBox.innerHTML = ''; // Clear old errors
                form.submit(); // Submit manually only if no errors
            }

        });
    });


    $(function() {
        var departmentTable = $('.datatables-department'),
        select2 = $('.select2');
        if (departmentTable.length) {

            departmentTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('departments.index') }}", // Fixed syntax
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: null,
                        title: 'S.No',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false, // Optional: prevent sorting on this column
                        searchable: false // Optional: exclude from search
                    },
                    { data: 'department', title: 'Deaprtment' },
                    { data: 'designation', title: 'Designation' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('departments.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-department" onclick="openDepartmentOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-department" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    /*delete banner function*/

    $(document).on('click', '.delete-department', function(e) {
        e.preventDefault();
        const departmentId = $(this).data('id');

        Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                        $.ajax({
                        url: `/departments/${departmentId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Department has been deleted.", "success").then(() => {
                                $('#datatables-department').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openDepartmentOffcanvas(targetId = null) {
    $('#department-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID

    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Department</h5><span class="text-white slogan">Edit New Department</span>`);
        $.ajax({
            url: `/departments/${targetId}/edit`,
            type: 'GET',
            success: function (data) {


                $('#target_id').val(data.id);
                $('#basicBranchname3').val(data.branch).trigger('change');

                setTimeout(function () {
                    $('#department').val(data.department.id).trigger('change');
                }, 500);
                $('#designation').val(data.designation);
            }
        });
    }
    var offcanvasElement = $('#department_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

</script>
@endpush
