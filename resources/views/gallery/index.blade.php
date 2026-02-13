@extends('layouts.app')

@section('css')
<style>
    .w-35 {
        width: 35% !important;
    }

    .w-45 {
        width: 45% !important;
    }

    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        /* Moves the button outside the offcanvas */
        z-index: 1055;
        /* Ensures it stays on top */
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
            @php
                $currentMonthLabel = \Carbon\Carbon::now()->format('Y-F'); // e.g., "2025-May"
            @endphp

            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-2"><span class="text-muted fw-light"> </span> Gallery</h4>
                    <!-- Header -->

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);"
                                onclick="openGalleryOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="row mt-md-4">

                        <!---Gallery-->
                        <div class="container-fluid">
                            <div class="row tm-mb-90 g-4 tm-gallery">
                                @foreach ($galleries as $gallery)
                                    @php
                                        $images = json_decode($gallery->file, true);
                                        $thumb = $images[0] ?? 'assets/img/placeholder.jpg'; // fallback image
                                    @endphp
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                                        <figure class="effect-ming tm-video-item mb-0 br-lt10 br-rt10"
                                            style="height: 240px;">
                                            <img src="{{ asset($thumb) }}" alt="Image" class="img-fluid w-100 galery-cover"
                                                style="width: 100%;">
                                            <figcaption class="d-flex align-items-center justify-content-center">
                                                <h2>{{ $gallery->title }}</h2>
                                                <a href="{{ route('gallery.show', $gallery->id) }}"></a>
                                            </figcaption>
                                        </figure>
                                        <div class="d-flex justify-content-between br-lb10 br-rb10 bg-white p-3">
                                            <span
                                                class="text-black">{{ \Carbon\Carbon::parse($gallery->display_date)->format('d M Y') }}</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-black">{{ count($images) }} Images</span>
                                                <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this gallery?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-label-danger" {{ count($images) > 0 ? 'disabled' : '' }}
                                                        title="{{ count($images) > 0 ? 'Only empty galleries can be deleted' : 'Delete Gallery' }}">
                                                        <i class="ti ti-trash ti-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div> <!-- row -->
                        </div>


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
<div class="offcanvas offcanvas-end w-45" data-bs-scroll="true" data-bs-backdrop="static" tabindex="-1"
    id="gallery_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Gallery Images</h5>
                <span class="text-white slogan">Upload new gallery images</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-gallery-form action="{{ route('gallery.store') }}" />
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
    <script>

        var quillLeaveEditor = new Quill('#gallery-editor',
            {
                theme: 'snow',
                placeholder: 'Type your reason here...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

        function openGalleryOffcanvas(targetId = null) {
            $('#gallery-form')[0].reset(); // Reset form
            $('#target_id').val(''); // Clear ID
            if (targetId) {
                $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Gallery</h5><span class="text-white slogan">Edit New Gallery</span>`);
                $.ajax({
                    url: `/gallery/${targetId}/edit`,
                    type: 'GET',
                    success: function (data) {

                        // let content = data.gallery.gallery_details;
                        // let cleanContent = content.replace(/^<p>|<\/p>$/g, '');

                        // $('#target_id').val(data.thoughts.id);
                        // $('#thoughts_title').val(data.thoughts.thoughts_title);
                        // $('#display_date').val(data.thoughts.display_date);
                        // $('#thoughts_details').val(cleanContent);
                        // // document.getElementById('thoughts-editor').textContent = cleanContent;
                        // quillEditor1.root.innerHTML = cleanContent;

                        // const previewEdit = document.getElementById("PicturePreview");
                        // previewEdit.src = `/storage/${data.thoughts.picture}`;;
                        // previewEdit.style.display = "block";

                        // $('#picture').val('');
                    }
                });
            }
            var offcanvasElement = $('#gallery_offcanvas');
            var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
            offcanvas.show();
        }
    </script>
@endpush