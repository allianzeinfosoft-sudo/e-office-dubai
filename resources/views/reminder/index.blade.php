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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openReminderOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="datatables-basic datatables-reminder table border-top table-stripedc" id="datatables-reminder">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>User Name</th>
                                        <th>Event Name</th>
                                        <th>Event Description</th>
                                        <th>From Date</th>
                                        <th>Time</th>
                                        <th>Repeat On</th>
                                        <th>Every</th>
                                        <th>Action</th>
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="reminder_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel">Set Reminder</h5>
                <span class="text-white slogan">Create New Reminder</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-reminder-form action="{{ route('reminder.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById('reminder-form');

            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Stop the form initially

                const eventName = document.getElementById('event_name').value.trim();
                const displayTime = document.getElementById('display_time').value.trim();
                const startDate = document.getElementById('start_date').value.trim();
                const repeatChecked = document.getElementById('flexSwitchCheckChecked').checked;
                const repeatMode = document.getElementById('repeat_mode').value;
                const endDate = document.getElementById('end_date').value.trim();
                const weekdayCheckboxes = document.querySelectorAll('input[name="weekdays[]"]:checked');
                const weekdays = Array.from(weekdayCheckboxes).map(cb => cb.value); // This gives you an array of selected values

                let errors = [];

                if (!eventName) {
                    errors.push("Event Name is required.");
                }

                if (!displayTime) {
                    errors.push("Display Time is required.");
                }

                if (!startDate) {
                    errors.push("Start Date is required.");
                }


                if (repeatChecked) {


                    if (!repeatMode) {
                        errors.push("Repeat Mode is required.");
                    }
                    else{

                        if(repeatMode === 'weekly')
                        {
                            const selectedWeekdays = document.querySelectorAll('input[name="weekdays[]"]:checked');
                            if (selectedWeekdays.length === 0) {
                                errors.push("At least one weekday must be selected.");
                            }
                        }

                        if (repeatMode === 'monthly') {
                            const type1 = document.getElementById('monthly_type1').checked;
                            const type2 = document.getElementById('monthly_type2').checked;

                            if (type1) {
                                const onDay1 = document.querySelector('select[name="onday1"]').value;
                                if (!onDay1) {
                                    errors.push("On Day (number) is required for Monthly type 1.");
                                }
                            } else if (type2) {
                                const onDay2 = document.querySelector('select[name="onday2"]').value;
                                const onDay3 = document.querySelector('select[name="onday3"]').value;

                                if (!onDay2 || !onDay3) {
                                    errors.push("Both Week Position and Weekday are required for Monthly type 2.");
                                }
                            } else {
                                errors.push("Please select a Monthly recurrence type (On Day or On The).");
                            }
                        }

                        if (repeatMode === 'yearly') {
                            const yearDay = document.querySelector('select[name="on_year_day"]').value;
                            if (!yearDay) {
                                errors.push("On Day (yearly) is required.");
                            }

                            if (!endDate)
                            {
                                errors.push("End Date is required for repeating reminders.");
                            }
                        }


                    }


                    if (!endDate) {
                        errors.push("End Date is required for repeating reminders.");
                    }
                }

                let oldErrorBox = document.querySelector('#clientErrors');
                if (oldErrorBox) {
                    oldErrorBox.remove(); // Remove existing client-side error box
                }

                if (errors.length > 0) {
                    const errorHtml = `
                        <div class="alert alert-danger" id="clientErrors">
                            <ul class="mb-0">
                                ${errors.map(error => `<li>${error}</li>`).join('')}
                            </ul>
                        </div>
                    `;

                    form.insertAdjacentHTML('beforebegin', errorHtml); // Add errors above the form
                } else {
                    form.submit(); // Proceed if no errors
                }
            });
        });



    $(function() {
        var bannerTable = $('.datatables-reminder'),
        select2 = $('.select2');
        if (bannerTable.length) {

            bannerTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('reminder.index') }}", // Fixed syntax
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
                    { data: 'user_name', title: 'User Name' },
                    { data: 'event_name', title: 'Event Name' },
                    { data: 'event_description', title: 'Event Description' },
                    { data: 'start_date', title: 'From Date'},
                    { data: 'display_time', title: 'Time'},
                    { data: 'repeat_on', title: 'Repeat On'},
                    {

                            data: 'every',
                            title: 'Every',
                            render: function (data, type, row) {
                                // Handle stringified array like '["sun", "mon"]'
                                if (typeof data === 'string') {
                                    try {
                                        const parsed = JSON.parse(data);
                                        if (Array.isArray(parsed)) {
                                            data = parsed;
                                        }
                                    } catch (e) {
                                        // Not JSON, leave it as a string
                                    }
                                }

                                let output = '';

                                if (Array.isArray(data)) {
                                    output = data.map(item =>
                                        typeof item === 'string'
                                            ? item.charAt(0).toUpperCase() + item.slice(1)
                                            : item
                                    ).join(', ');
                                } else if (typeof data === 'string') {
                                    output = data.charAt(0).toUpperCase() + data.slice(1);
                                }

                                // Append month name if repeat_on is 'yearly'
                                if (row.repeat_on === 'yearly' && row.yearly_in_month) {

                                    output = `${row.yearly_in_month} ${output} `;
                                }

                                return output;
                            }

                    },

                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            const editUrl = "{{ route('reminder.edit', ':id') }}".replace(':id', row.id);
                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-reminder" onclick="openReminderOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-reminder" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    /*delete banner function*/

    $(document).on('click', '.delete-reminder', function(e) {
        e.preventDefault();
        const reminderId = $(this).data('id');

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
                        url: `/reminder/${reminderId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Reminder has been deleted.", "success").then(() => {
                                $('#datatables-reminder').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openReminderOffcanvas(targetId = null) {
    $('#reminder-form')[0].reset();
    $('#weekly-div, #month-div, #onday-one-div, #onday-two-div, #onday-three-div, #onday-year-div').addClass('d-none');
    $('#target_id').val('');
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Reminder</h5><span class="text-white slogan">Edit New Reminder</span>`);
        $.ajax({
            url: `/reminder/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                const reminder = data.reminder;
                $('#target_id').val(reminder.id);
                $('#user').val(reminder.user_id).trigger('change');

                $('#event_name').val(reminder.event_name);
                $('#event_description').val(reminder.event_description);
                $('#display_time').val(reminder.display_time);
                $('#start_date').val(reminder.start_date);

                // Reset switches, sections, and checkboxes
                $('#flexSwitchCheckChecked').prop('checked', false).trigger('change');
                $('#end-date-section, #repeat-mode-section, #weekly-div, #month-div, #onday-one-div, #onday-two-div, #onday-year-div').addClass('d-none');
                $('[name="weekdays[]"]').prop('checked', false);
                $('#month').val('');
                $('#onday1').val('');
                $('#on_year_day').val('');
                $('#onday2').val('');
                $('#onday3').val('');


                // Handle repeat_status
                if (reminder.repeat_status) {
                    $('#flexSwitchCheckChecked').prop('checked', true).trigger('change');

                    $('#end_date').val(reminder.end_date);
                    $('#repeat_mode').val(reminder.repeat_mode);
                    $('#end-date-section').removeClass('d-none');
                    $('#repeat-mode-section').removeClass('d-none');

                    // Repeat Mode: Weekly
                    if (reminder.repeat_mode === 'weekly') {

                            if (reminder.day === 'string')
                            {
                                try {

                                    const parsed = JSON.parse(reminder.day);
                                    if (Array.isArray(parsed)) {

                                        parsed.forEach(function (day) {

                                            // $('#weekday_' + day).prop('checked', true);
                                        });
                                    }
                                } catch (e) {
                                    // Not JSON, leave it as a string
                                }
                            }

                            else  (Array.isArray(reminder.day))
                            {
                                const weekdays = JSON.parse(reminder.day);
                                weekdays.forEach(function (day) {
                                    $('#weekday_' + day).prop('checked', true);
                                });

                            }
                            $('#weekly-div').removeClass('d-none');
                        }

                    }

                    // Repeat Mode: Monthly
                    if (reminder.repeat_mode === 'monthly') {

                        if (reminder.monthly_type == 1) {
                            $('#onday-one-div').removeClass('d-none');
                            $('#onday-two-div').removeClass('d-none');
                            $('#monthly_type1').prop('checked', true);
                            $('#onday1').val(reminder.day);
                        } else if (reminder.monthly_type == 2) {
                            $('#onday-one-div').removeClass('d-none');
                            $('#onday-two-div').removeClass('d-none');
                            $('#monthly_type2').prop('checked', true);
                            $('#onday2').val(reminder.monthly_on_week_position);
                            $('#onday3').val(reminder.day);
                        }
                    }

                    // Repeat Mode: Yearly
                    if (reminder.repeat_mode === 'yearly') {
                        $('#onday-year-div').removeClass('d-none');
                        $('#month-div').removeClass('d-none');
                        $('#on_year_day').val(reminder.day);
                        $('#month').val(reminder.yearly_in_month);
                    }


                    $('#target_id').val(data.reminder.id);

                    var offcanvasElement = $('#reminder_offcanvas');
                    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
                    offcanvas.show();


                }




        });
    }else{
        var offcanvasElement = $('#reminder_offcanvas');
        var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
        offcanvas.show();
    }


}

</script>
@endpush
