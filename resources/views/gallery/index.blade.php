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
            @php
                $currentMonthLabel = \Carbon\Carbon::now()->format('Y-F'); // e.g., "2025-May"
            @endphp

                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-2"><span class="text-muted fw-light"> /</span> Gallery</h4>
                    <!-- Header -->

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openGalleryOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="row mt-md-4">

                        <!---Gallery-->
                        <div class="container-fluid ">
                            <div class="row tm-mb-90 g-4 tm-gallery">
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                                <figure class="effect-ming tm-video-item mb-0 br-lt10 br-rt10">
                                    <img src="../../assets/img/gallery/img-03.jpg" alt="Image" class="img-fluid w-100 galery-cover">
                                    <figcaption class="d-flex align-items-center justify-content-center">
                                        <h2>Clocks</h2>
                                        <a href="gallery-masnory.html"></a>
                                    </figcaption>
                                </figure>
                                <div class="d-flex justify-content-between br-lb10 br-rb10 bg-white p-3">
                                    <span class="text-black">18 Oct 2020</span>
                                    <span class="text-black">9,906 Images</span>
                                </div>
                            </div>
                            </div> <!-- row -->
                        </div> <!-- container-fluid, tm-container-content -->
                        <!--Gallery-->

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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="gallery_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Gallery Images</h5>
                <span class="text-white slogan">Upload new gallery images</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
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

@endpush
