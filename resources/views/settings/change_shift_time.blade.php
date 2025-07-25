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

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openChangeShiftOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Change Shift Time</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-banner table border-top table-stripedc" id="datatables-shift-times">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Shift Name</th>
                                        <th>Shift Time</th>
                                        <th>Login Limited Time</th>
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
<div class="offcanvas offcanvas-end w-35" data-bs-backdrop="static" tabindex="-1" id="change_shift_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Change Shift Time</h5>
                <span class="text-white slogan">Change users shift time</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-change-shift-from action="{{ route('update.user.shift') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>

    // document.addEventListener("DOMContentLoaded", function () {
    //     const form = document.getElementById('banner-form');


    //     form.addEventListener('submit', function (e) {
    //         e.preventDefault(); // Always prevent default first

    //         // Get values

    //         const banner_title = document.getElementById('banner_title').value.trim();
    //         const display_date = document.getElementById('display_date').value.trim();

    //         let errors = [];

    //         // === Validation ===
    //         if (!banner_title) {
    //             errors.push("Banner Title is required.");
    //         }

    //         if (!display_date) {
    //             errors.push("Display date is required.");
    //         } else if (isNaN(Date.parse(display_date))) {
    //             errors.push("Display date must be a valid date.");
    //         }

    //         // if (!picture) {
    //         //     errors.push("Picture is required.");
    //         // }

    //         if (!hiddenBanner_details) {
    //             errors.push("Banner details reason is required");
    //         }

    //         // === Show errors or submit ===
    //         let errorBox = document.getElementById('formErrors');
    //         if (!errorBox) {
    //             errorBox = document.createElement('div');
    //             errorBox.id = 'formErrors';
    //             errorBox.className = 'alert alert-danger mt-3';
    //             form.prepend(errorBox);
    //         }

    //         if (errors.length > 0) {
    //             errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
    //         } else {
    //             errorBox.innerHTML = ''; // Clear old errors
    //             form.submit(); // Submit manually only if no errors
    //         }
    //     });
    // });


    $(function() {

        var shifTimeTable = $('#datatables-shift-times'),

        select2 = $('.select2');
        if (shifTimeTable.length) {

            shifTimeTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('users.shifts') }}", // Fixed syntax
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: null,
                        title: 'S.No',
                        width: '40px',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        title: 'Name',
                        render: function (data, type, row) {
                            let image = row.picture
                                ? `<img src="/storage/${row.picture}" alt="user-avatar" class="rounded-circle me-2" height="32" width="32">`
                                : `<span class="me-2">No Image</span>`;

                            return `
                                <div class="d-flex align-items-center">
                                    ${image}
                                    <span>${data}</span>
                                </div>
                            `;
                        }
                    },
                    { data: 'user_name', title: 'Username' },
                    { data: 'shift_name', title: 'Shift Name'},
                    {
                        data: null,
                        title: 'Shift Time',
                        render: function(data, type, row) {
                            return row.shift_start_time + ' - ' + row.shift_end_time;
                        }
                    },
                    { data: 'wildcard_entry', title: 'Login Limited Time'},

                ]
            });
        }
    });



function openChangeShiftOffcanvas(targetId = null) {
    $('#chagne-shift-form')[0].reset();
    var offcanvasElement = $('#change_shift_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}

</script>
@endpush
