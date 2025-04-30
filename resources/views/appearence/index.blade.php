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
.selected-btn {
    background-color: #198754 !important; /* Bootstrap green */
    border-color: #198754 !important;
    color: white;
}
</style>
@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container bg-eoffice">
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />











            <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-2"><span class="text-muted fw-light"></span> Settings</h4>
              <!-- Header -->
              <div class="row mt-md-2">
                <div class="tab row-bordered mb-3">
                  <button class="tablinks active" onclick="openBg(event, 'Feed')">Feed Background</button>
                  <button class="tablinks" onclick="openBg(event, 'Login')">Login Background</button>
                  <button class="tablinks" onclick="openBg(event, 'All')">All Background</button>
                </div>

                <!---feed page Background change-->
                <div id="Feed" class="tabcontent container-fluid show">
                    <div class="card">
                      <div class="d-flex align-items-center p-3 justify-content-between">
                        <h5 class="fw-bold mb-0">Change Feed Background Style</h5>
                        <a class="btn btn-info waves-effect h-px-40 waves-light" href="javascript:void(0);" onclick="openBackgroundOffcanvas()">
                            Upload Image <i class="mx-1 ti ti-arrow-big-up-lines ti-sm"></i>
                        </a>
                      </div>
                      <div class="row px-3">
                        <span>No image uploaded</span>
                      </div>
                    </div> <!-- container-fluid, tm-container-content -->
                    <!--Gallery-->
                </div>
                <!---feed page Background change-->

                <!---login page Background change-->
                <div id="Login" class="tabcontent container-fluid">
                  <div class="card">
                    <div class="d-flex align-items-center p-3 justify-content-between">
                      <h5 class="fw-bold mb-0">Change Login Background Image</h5>
                      <button type="button" class="btn btn-info waves-effect h-px-40 waves-light" href="javascript:void(0);" onclick="openBackgroundOffcanvas()">Upload Image <i class="mx-1 ti ti-arrow-big-up-lines ti-sm"></i></button>
                    </div>
                    <div class="row px-3">
                        <span>No image uploaded</span>
                    </div>
                  </div> <!-- container-fluid, tm-container-content -->
                  <!--Gallery-->
                </div>
                <!---login page Background change-->

                <!---all page Background change-->
                <div id="All" class=" tabcontent container-fluid ">
                  <div class="card">
                    <div class="d-flex align-items-center p-3 justify-content-between">
                      <h5 class="fw-bold mb-0">Change Background Image</h5>
                      <button type="button" class="btn btn-info waves-effect h-px-40 waves-light">Upload Image <i class="mx-1 ti ti-arrow-big-up-lines ti-sm"></i></button>
                    </div>
                    <div class="row px-3">
                        <span>No image uploaded</span>
                    </div>
                  </div> <!-- container-fluid, tm-container-content -->
                  <!--Gallery-->
                </div>
                <!---all page Background change-->
              </div>
            </div>
            <!-- / Content -->
                <x-footer />
                <!-- / Footer -->
        </div>
    </div>
</div>

<!-- Add Banner -->
<div class="offcanvas offcanvas-end w-35" data-bs-backdrop="static" tabindex="-1" id="appearence_background_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Background</h5>
                <span class="text-white slogan">Create New Background</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-appearence-background-form action="{{ route('appearences.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('appearence-background-form');


        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const selectedBackgroundTypes = Array.from(document.getElementsByName('background_type[]'))
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

            const imageInput = document.getElementById('image');
            const image = imageInput.files[0];

            let errors = [];

            // === Validation ===
           if (selectedBackgroundTypes.length === 0) {
                errors.push("At least one background type must be selected.");
            }

            if (!image) {
                errors.push("Image is required.");
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


    $(function () {
    $.ajax({
        type: "GET",
        url: "{{ route('appearences.index') }}", // Your endpoint
        dataType: "json",
        success: function (response) {
            if (response.data && response.data.length) {
                renderImages(response.data);
            }
        },
        error: function (xhr) {
            console.error("Failed to load background images:", xhr);
        }
    });



function renderImages(images) {
    // Clear previous content
    $('#Feed .row.px-3, #Login .row.px-3, #All .row.px-3').empty();

    images.forEach(function (image) {
        if (!image.background_type) return;

        const cat = image.background_type.toLowerCase();
        const isActive = image.is_active;

        // Determine button state
        const selectBtnClass = isActive ? 'btn-primary selected-btn' : 'btn-success';
        const selectBtnText = isActive ? '<i class="ti ti-check"></i>' : 'Select';

        const imageHtml = `<div class="col-xl-3 col-lg-3 col-md-4 col-sm-4 col-12 mb-5">
            <div class="parent shadow">
                <img src="/storage/${image.image}" alt="Image" class="img-fluid w-100 galery-cover">
            </div>
            <div class="d-flex justify-content-between shadow br-lb10 br-rb10 bg-white p-3">
                <button class="btn btn-danger waves-effect waves-light delete-background-image delete-image-${image.id}" data-id="${image.id}" data-bg_type="${cat}">
                    <i class="ti ti-trash"></i>
                </button>
                <button class="btn ${selectBtnClass} waves-effect waves-light select-background-image select-image-${image.id}" data-id="${image.id}" data-bg_type="${cat}">
                    ${selectBtnText}
                </button>
            </div>
        </div>`;

        // Append image to the correct section
        switch (cat) {
            case 'feeds':
                $('#Feed .row.px-3').append(imageHtml);
                break;
            case 'login':
                $('#Login .row.px-3').append(imageHtml);
                break;
            case 'main':
            case 'all':
            default:
                $('#All .row.px-3').append(imageHtml);
                break;
        }
    });
}




});


    /*delete banner function*/

    $(document).on('click', '.delete-background-image', function(e) {
        e.preventDefault();
        const backgroundId = $(this).data('id');

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
                        url: `/appearences/${backgroundId}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                        },
                        success: function(response) {

                            Swal.fire("Deleted!", "Background Image has been deleted.", "success").then(() => {
                                $('#datatables-background').DataTable().ajax.reload(); // Reload table
                            });

                        },
                        error: function(xhr) {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                        });

            }

    });
  });


function openBackgroundOffcanvas() {
    $('#appearence-background-form')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    var offcanvasElement = $('#appearence_background_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}



function openBg(evt, bgName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(bgName).style.display = "block";
        evt.currentTarget.className += " active";
      }


      /*set back ground image*/

      $(document).on('click', '.select-background-image', function () {
            const imageId = $(this).data('id');
            const backgroundType = $(this).data('bg_type');

            $.ajax({
                url: '/background-images/select',
                type: 'POST',
                data: {
                    image_id: imageId,
                    background_type: backgroundType,
                    _token: $('meta[name="csrf-token"]').attr('content') // ensure CSRF token is present
                },
                success: function (response) {
                    // Optional UI feedback before reload
                    $(`.select-image-${response.selected.image_id}`)
                        .html('<i class="ti ti-check"></i>')
                        .removeClass('btn-success')
                        .addClass('selected-btn btn-primary');

                    // Show success notification
                    toastr.success('Background image updated successfully.');

                    // Delay and reload
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // wait 1 second for user to see toast
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Error updating background image.');
                }
            });
        });


</script>
@endpush
