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
                    <h4 class="fw-bold py-3 mb-2"><span class="text-white">Views /</span> Appreciations</h4>

                    <div class="row mt-md-4">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-10 mb-4 mx-auto">

                                    @forelse ($appreciations as $item)
                                        <div class="timeline-event card-app card mb-3" data-aos="fade-right">
                                            <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                                                <h5 class="text-white my-1">Congratulations</h5>
                                                <span class="badge bg-warning">{{ $item['display_date'] }}</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-wrap align-items-center justify-content-center my-3">
                                                    @foreach ($item['employees'] as $emp)
                                                        <div class="d-flex flex-column me-2 mb-2">
                                                            <img src="{{ asset('/storage/' . $emp['profile_image']) }}"
                                                                 alt="{{ $emp['full_name'] }}"
                                                                 class="mx-auto border-theme rounded-circle w-px-75" />
                                                            <span class="bday-name">{{ $emp['full_name'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="cng-img text-center">
                                                    <!-- <img src="{{ asset('storage/appreciation_flowers/' . ($item['image'] ?? 'cng.png')) }}"
                                                         alt="Appreciation Background"> -->
                                                    <img src="../../assets/img/backgrounds/cng.png" alt="Appreciation Background">
                                                    <img class="w-25" src="{{ asset('storage/appreciation_flowers/' . $item['image']) }}" alt="Appreciation Background">
                                                </div>
                                                <p class="mt-3 mb-2">
                                                    {!! nl2br(e($item['message'])) !!}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap">
                                                    <div>
                                                        <button type="button" class="btn btn-primary w-100">Congratulate...</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center mt-5">
                                            <h5 class="text-muted">No appreciations for today.</h5>
                                        </div>
                                    @endforelse

                                </div>
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
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="thoughts_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Thought</h5>
                <span class="text-white slogan">Create New Thought</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-thoughts-form action="{{ route('thoughts.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop
