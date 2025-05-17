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
                    <h4 class="fw-bold py-3 mb-2 "><span class="text-white ">  /</span> Thought Of The Day</h4>
                    <!-- Header -->
                    <div class="row mt-md-4">


                        @if ($thought)
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Thought content -->
                                    <div class="card-box bg-white rounded mb-3">
                                        <h5 class="mb-0 fw-bold p-2 bg-white bg-label-warning">
                                            {{ \Carbon\Carbon::parse($thought->display_date)->format('M-d') }}: Today
                                        </h5>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-8 card p-0 mx-auto">
                                            <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                                                <h5 class="text-white my-1">Thought Of The Day</h5>
                                            </div>

                                            <div class="card-body">
                                                <div class="meta my-1">
                                                    <h4 class="wrd-br text-center fw-bold">
                                                        {{ $thought->thoughts_title ?? 'No Thought Available Today' }}
                                                    </h4>
                                                </div>
                                                <p>
                                                    {{ $thought->thoughts_details ?? 'Stay positive and keep growing!' }}
                                                </p>
                                                @if ($thought->picture)
                                                    <img class="w-100 mb-3" src="{{ asset('storage/' . $thought->picture) }}" alt="Thought of the Day">
                                                @endif
                                                {{-- <p class="text-fade">
                                                    <b>Thank You</b><br>
                                                    Sujatha P
                                                </p> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @else
                                <div class="alert alert-warning text-center">No thought available for today.</div>
                            @endif



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

