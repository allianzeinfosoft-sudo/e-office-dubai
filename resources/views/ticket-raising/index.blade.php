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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openTicketOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New Ticket</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-tickets table border-top table-stripedc" id="datatables-tickets">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        {{-- <th>Image</th> --}}
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Ticket Title</th>
                                        <th>Issue Date</th>
                                        <th>Close Date</th>
                                        <th>Status</th>
                                        <th>Comment</th>
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

<!-- Add Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="tickets_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Ticket</h5>
                <span class="text-white slogan">Create New Ticket</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-ticket-form/>
            </div>
        </div>
    </div>
</div>


{{-- view ticket details --}}
<div class="modal fade" id="ticketDetail" tabindex="-1" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered1 modal-lg modal-simple modal-add-new-cc">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body" style="border: 0.5px solid #474a4e; border-radius: 10px;">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Ticket Details</h3>
        </div>
        <div class="mb-3">
          <p><strong>Description:</strong></p>
          <p id="description" class="mb-3 text-muted"></p>

          <p><strong>Picture:</strong></p>
          <div id="picture_div">

            </div>
            <input type="hidden" id="ticket_id" value="">
        </div>
        <div class="col-12 text-center">

        {{-- @if(Auth::user()->employee->department->department == 'Technical') --}}
          <button type="button" id="show-close-form" class="btn btn-label-primary">
            Close Ticket
          </button>
           <button type="button" id="mark-as-read" class="btn btn-label-primary">
            Mark As Read
          </button>
        {{-- @endif --}}
        </div>
        <form id="close-ticket-form" style="display: none;" class="mt-4">
          <div class="mb-3">
            <label for="comment" class="form-label">Comment (optional)</label>
            <textarea id="comment" class="form-control" rows="3" placeholder="Enter your closing comment..."></textarea>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-danger">Submit & Close Ticket</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

{{-- view ticket details --}}





@stop
@push('js')
<script>
    // form validation
    var quillEditor2 = new Quill('#tickets-editor', { theme: 'snow',
        placeholder: 'Type your reason here...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            } });

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('tickets-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first
            const ticket_title = document.getElementById('ticket_title').value.trim();
            const ticket_description = document.getElementById('ticket_description').value.trim();
            const picture = document.getElementById('picture').value.trim();
            const tickets_details = quillEditor2.root.innerText.trim();
            const hiddenTicket_details = document.getElementById('ticket_description');
            hiddenTicket_details.value = tickets_details;

            let errors = [];
            // === Validation ===
            if (!ticket_title) {
                errors.push("Tickets Title is required.");
            }

            if (!hiddenTicket_details) {
                errors.push("Ticket description is required");
            }

            // === Show errors or submit ===
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

        var ticketsTable = $('.datatables-tickets'),
        select2 = $('.select2');
        if (ticketsTable.length) {

            ticketsTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('tickets.index') }}", // Fixed syntax
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
                    // {
                    //     data: 'picture',
                    //     title: 'Image',
                    //     render: function (data, type, row) {
                    //         if (data) {
                    //             return `<img src="/storage/${data}" alt="Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 6px;" />`;
                    //         } else {
                    //             return 'No Image';
                    //         }
                    //     }
                    // },
                    { data: 'employee', title: 'Employee' },
                    { data: 'ticket_department', title: 'Department' },
                    { data: 'ticket_title', title: 'Ticket Title' },
                    { data: 'issue_date_time', title: 'Issue Date' },
                    { data: 'close_date_time', title: 'Close Date'},
                    {
                        data: 'status',
                        title: 'Status',
                        render: function (data) {
                            if (data == 1) return `<button class="btn btn-sm btn-label-linkedin">Closed</button>`;
                             if (data == 2) return `<button class="btn btn-sm btn-primary">Issue on Processing</button>`;
                            if (data == 0 || data == '') return `<button class="btn btn-sm btn-danger">Pending</button>`;
                            return 'N/A';
                        }
                    },
                    { data: 'comment', title: 'Comment' },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {
                            let picture = row.picture;
                            let description = row.ticket_description;
                            let id = row.id;
                            let status = row.status;

                            const editUrl = "{{ route('tickets.edit', ':id') }}".replace(':id', row.id);
                            return `<button class="btn btn-sm btn-success me-2 open-modal" data-function="1" data-id="${id}" data-status="${status}" data-ticket_description="${description}" data-picture="${picture}"  data-bs-toggle="modal" data-bs-target="#ticketDetail"><i class="fa fa-eye"></i></button>
                            <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-primary edit-tickets" onclick="openTicketOffcanvas(${row.id})"><i class="ti ti-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-tickets" data-id="${row.id}"><i class="ti ti-trash"></i></a>`;
                        }
                    }
                ]
            });
        }
    });

    /*delete thoughts function*/

    $(document).on('click', '.delete-tickets', function(e) {
        e.preventDefault();
        const ticketId = $(this).data('id');

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
                        url: `/tickets/${ticketId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Ticket has been deleted.", "success").then(() => {
                                $('#datatables-tickets').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openTicketOffcanvas(targetId = null) {

    $('#tickets-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Tickets</h5><span class="text-white slogan">Edit New Tickets</span>`);
        $.ajax({
            url: `/tickets/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                let content = data.tickets.ticket_description;
                let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

                $('#target_id').val(data.tickets.id);
                $('#ticket_title').val(data.tickets.ticket_title);
                $('#ticket_description').val(cleanContent);
                // document.getElementById('thoughts-editor').textContent = cleanContent;
                quillEditor2.root.innerHTML = cleanContent;

                const previewEdit = document.getElementById("PicturePreview");
                previewEdit.src = `/storage/${data.tickets.picture}`;;
                previewEdit.style.display = "block";
                $('#picture').val('');
            }
        });
    }
    var offcanvasElement = $('#tickets_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}


$(document).on("click", ".open-modal", function() {

    let picture = $(this).data("picture");
    let ticket_description = $(this).data("ticket_description");
    let ticketId = $(this).data("id");
    let status = $(this).data("status");

    if (status === 0 || status === null || status === '') {
        $('#show-close-form').prop('disabled', true).text("Close Ticket");
    }else if(status === 1)
    {
         $('#show-close-form').prop('disabled', true).text("Already Closed");
    }
    else if(status === 2){
        $('#show-close-form').prop('disabled', false).text("Close Ticket");
        // $('#show-close-form').prop('disabled', false).show();
        $('#mark-as-read').prop('disabled', true).show();
    }

    $("#ticket_id").val(ticketId);
    $("#description").text(ticket_description);

    let image = `<img src="/storage/${picture}" alt="Image" style="object-fit: cover; border-radius: 6px; max-width: 100%; height: auto;" />`;
    $("#picture_div").html(image);

    $('#close-ticket-form').hide();
    $('#comment').val('');
    $('#ticketDetail').modal('show');

});

$('#show-close-form').on('click', function () {
    $('#close-ticket-form').slideDown();
});



$('#close-ticket-form').on('submit', function (e) {
    e.preventDefault(); // prevent default form submission

    let ticketId = $('#ticket_id').val();
    let comment = $('#comment').val();

    Swal.fire({
        title: "Are you sure?",
        text: "This will permanently close the ticket.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754", // green
        cancelButtonColor: "#6c757d",  // gray
        confirmButtonText: "Yes, close it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/tickets/' + ticketId + '/close',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    comment: comment
                },
                success: function (response) {
                    Swal.fire("Closed!", "The ticket has been closed.", "success").then(() => {
                        $('#ticketDetail').modal('hide');
                        $('#datatables-tickets').DataTable().ajax.reload(); // optional: refresh datatable
                    });
                },
                error: function (xhr) {
                    Swal.fire("Error!", "Something went wrong while closing the ticket.", "error");
                }
            });
        }
    });
});


// mark as read
$('#mark-as-read').on('click', function (e) {

    e.preventDefault();

    let ticketId = $('#ticket_id').val();
    let comment = $('#comment').val();

    Swal.fire({
        title: "Are you sure?",
        text: "This ticket will be marked as read.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754", // green
        cancelButtonColor: "#6c757d",  // gray
        confirmButtonText: "Yes, mark it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/tickets/' + ticketId + '/mark-as-read',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    comment: comment // optional, only if needed
                },
                success: function (response) {
                    Swal.fire("Marked!", "The ticket has been marked as read.", "success").then(() => {
                        $('#ticketDetail').modal('hide');
                        $('#datatables-tickets').DataTable().ajax.reload(null, false); // ✅ reload without resetting pagination
                    });
                },
                error: function (xhr) {
                    Swal.fire("Error!", "Something went wrong while marking the ticket as read.", "error");
                }
            });
        }
    });
});

</script>
@endpush
