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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openBackgroundOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-datatable table-responsive">
                            <table class="hover_effect datatables-basic datatables-background table border-top table-stripedc" id="datatables-background">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Image</th>
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
{{-- <div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="banner_offcanvas" aria-labelledby="staticBackdropLabel">
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
</div> --}}

@stop


@push('js')
<script>

    // document.addEventListener("DOMContentLoaded", function () {
    //     const form = document.getElementById('banner-form');


    //     form.addEventListener('submit', function (e) {
    //         e.preventDefault();

    //         const banner_title = document.getElementById('banner_title').value.trim();
    //         const display_date = document.getElementById('display_date').value.trim();
    //         const picture = document.getElementById('picture').value.trim();
    //         const banner_details = quillBannerEditor1.root.innerText.trim();
    //         const hiddenBanner_details = document.getElementById('banner_details');
    //         hiddenBanner_details.value = banner_details;

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

        var backgroundTable = $('#datatables-background'),
        select2 = $('.select2');
        if (backgroundTable.length) {

            backgroundTable.DataTable({

                ajax: {
                    type: "GET",
                    url: "{{ route('appearences.index') }}", // Fixed syntax
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
                        data: 'image',
                        title: 'Image',
                        render: function (data, type, row) {
                            if (data) {
                                return `<img src="/storage/${data}" alt="Image" style="width: 150px; height: 150px; object-fit: cover; border-radius: 6px;" />`;
                            } else {
                                return 'No Image';
                            }
                        }
                    },

                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row, full) {

                            return `
                                <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-banner" data-id="${row.id}"><i class="ti ti-trash"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    /*delete banner function*/

//     $(document).on('click', '.delete-banner', function(e) {
//         e.preventDefault();
//         const bannerId = $(this).data('id');

//         Swal.fire({
//                 title: "Are you sure?",
//                 text: "You won't be able to revert this!",
//                 icon: "warning",
//                 showCancelButton: true,
//                 confirmButtonColor: "#d33",
//                 cancelButtonColor: "#3085d6",
//                 confirmButtonText: "Yes, delete it!"
//             }).then((result) => {
//                 if (result.isConfirmed) {

//                         $.ajax({
//                         url: `/banner/${bannerId}`,
//                         type: 'DELETE',
//                         data: {
//                             _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
//                         },
//                         success: function(response) {

//                             Swal.fire("Deleted!", "Banner has been deleted.", "success").then(() => {
//                                 $('#datatables-banner').DataTable().ajax.reload(); // Reload table
//                             });

//                         },
//                         error: function(xhr) {
//                             Swal.fire("Error!", "Something went wrong.", "error");
//                         }
//                         });

//             }

//     });
//   });


// function openBannerOffcanvas(targetId = null) {
//     $('#banner-form')[0].reset(); // Reset form
//     $('#target_id').val(''); // Clear ID
//     if (targetId) {
//         $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Banner</h5><span class="text-white slogan">Edit New Banner</span>`);
//         $.ajax({
//             url: `/banner/${targetId}/edit`,
//             type: 'GET',
//             success: function (data) {

//                 let content = data.banner.banner_details;
//                 let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

//                 $('#target_id').val(data.banner.id);
//                 $('#banner_title').val(data.banner.banner_title);
//                 $('#display_date').val(data.banner.display_date);
//                 $('#banner_details').val(cleanContent);
//                 // document.getElementById('thoughts-editor').textContent = cleanContent;
//                 quillBannerEditor1.root.innerHTML = cleanContent;

//                 const previewEdit = document.getElementById("PicturePreview");
//                 previewEdit.src = `/storage/${data.banner.picture}`;;
//                 previewEdit.style.display = "block";

//                 $('#picture').val('');
//             }
//         });
//     }
//     var offcanvasElement = $('#banner_offcanvas');
//     var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
//     offcanvas.show();
// }

</script>
@endpush
