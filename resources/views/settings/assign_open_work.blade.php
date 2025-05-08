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
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>
                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-assign-open-work table border-top table-stripedc" id="datatables-assign-open-work">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Modified Date</th>
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="banner_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Banner</h5>
                <span class="text-white slogan">Create New Banner</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-banner-form action="{{ route('banner.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>

    $(function() {


        var assignOpenWorkTable = $('.datatables-assign-open-work'),
        select2 = $('.select2');
        if (assignOpenWorkTable.length) {

            assignOpenWorkTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('assign_open_work') }}", // Fixed syntax
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

                    {
                        // User full name and email
                        targets: 1,
                        responsivePriority: 4,
                        render: function (data, type, full, meta)
                            {
                                let userView = "/user/profile/"+full['id'];
                                var $name = full['full_name'],
                                $image = full['picture'];

                                if ($image) {
                                    var $output = '<img src="/storage/' + $image + '" alt="Avatar" class="rounded-circle">';
                                } else {
                                // For Avatar badge
                                var stateNum = Math.floor(Math.random() * 6);
                                var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                                var $state = states[stateNum],
                                    $name = full['full_name'],
                                    $initials = $name.match(/\b\w/g) || [];
                                $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                                $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
                                }
                                // Creates full output for row
                                var $row_output =
                                    '<div class="d-flex justify-content-start align-items-center user-name">' +
                                    '<div class="avatar-wrapper">' +
                                    '<div class="avatar avatar-sm me-3">' +
                                $output +
                                '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                '<a href="' +
                                userView +
                                '" class="text-body text-truncate"><span class="fw-semibold">' +
                                $name +
                                '</span></a>' +
                                '<small class="text-muted">';
                                return $row_output;
                            }
                    },
                    {
                        data: null,
                        title: 'Status',
                        render: function (data, type, row, full) {
                            const isChecked = row.open_work_status ? 'checked' : '';
                            return `
                                <label class="switch switch-success">
                                    <input type="checkbox" class="switch-input toggle-open-work" data-id="${row.id}" ${isChecked} />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="ti ti-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="ti ti-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Success</span>
                                </label>
                            `;
                        }
                    },
                    {
                        data: 'updated_date', title: 'Modified Date'
                    }
                ]
            });
        }
    });

    /*delete banner function*/


    $(document).on('change', '.toggle-open-work', function () {
        const checkbox = $(this);
        const id = checkbox.data('id');
        const isChecked = checkbox.is(':checked');
        const originalState = !isChecked; // In case user cancels, revert to this

        Swal.fire({
            title: "Are you sure?",
            text: `You are about to ${isChecked ? 'enable' : 'disable'} Open Work.`,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#dc3545",
            confirmButtonText: "Yes, confirm",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with status update
                $.ajax({
                    url: "{{ route('open.work.assign') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        status: isChecked,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire("Updated!", "Status has been updated.", "success");
                        console.log('Status updated successfully:', response);
                    },
                    error: function (xhr) {
                        Swal.fire("Error!", "Failed to update status.", "error");
                        // Revert checkbox on failure
                        checkbox.prop('checked', originalState);
                        console.error('Error updating status:', xhr.responseText);
                    }
                });
            } else {
                // If cancelled, revert checkbox state
                checkbox.prop('checked', originalState);
            }
        });
});





</script>
@endpush
