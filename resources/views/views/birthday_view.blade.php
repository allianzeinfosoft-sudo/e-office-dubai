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

            @php
                $currentMonth = \Carbon\Carbon::now()->format('F');
            @endphp
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-2"><span class="text-muted fw-light"> Views /</span> Birthdays</h4>
                    <!-- Header -->
                    <div class="row mt-md-4">

                      <!---Gallery-->

                      @if($todaysBirthdays->isNotEmpty())
                        <div class="card-box bg-white rounded mb-4">
                            <h5 class="mb-0 fw-bold p-2 bg-label-danger ">🎉 Today's Birthdays</h5>
                        </div>
                        <div class="row gap-2 mb-4">
                            @foreach($todaysBirthdays as $emp)
                                <div class="col-md-2 flex-column rounded py-3 d-flex theme-transparent text-center">
                                    <span class="text-white mb-3 fw-bold">{{ $emp['birth_date'] }}</span>
                                    <img src="{{ asset('/storage/' . $emp['profile_image']) }}"
                                        alt="{{ $emp['full_name'] }}"
                                        class="mx-auto border-theme rounded-circle w-px-100" />
                                    <span class="fw-bold mt-3 rounded bg-black p-2 text-white">{{ $emp['full_name'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card-box bg-white rounded mb-4">
                            <h5 class="mb-0 fw-bold p-2 bg-label-danger ">🎉 Today's Birthdays</h5>
                        </div>
                        <div class="text-center mt-5">
                            <h5 class="text-muted">No Birthday Today.</h5>
                        </div>
                    @endif

                      <div class="container-fluid">
                        @forelse($birthdays as $month => $employees)
                            <div class="row mb-3">
                                <div class="card-box bg-white rounded">
                                    <h5 class="mb-0 fw-bold p-2 bg-white
                                        {{ $month === $currentMonth ? 'bg-label-primary text-white' : 'bg-label-success' }}">
                                        {{ $month }} Birthdays
                                    </h5>
                                </div>
                                <div class="row gap-2">
                                    @foreach($employees as $emp)
                                        <div class="col-md-2 flex-column rounded mt-3 py-3 d-flex theme-transparent text-center">
                                            <span class="text-white mb-3 fw-bold ">{{ $emp['birth_date'] }}</span>
                                            <img src="{{ asset('/storage/' . $emp['profile_image']) }}"
                                                 alt="{{ $emp['full_name'] }}"
                                                 class="mx-auto border-theme rounded-circle w-px-100" />
                                            <span class="fw-bold mt-3 rounded bg-black p-2 text-white">{{ $emp['full_name'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center mt-5">
                                <h5 class="text-muted">No employee birthdays found.</h5>
                            </div>
                        @endforelse
                    </div>


                      <!--Gallery-->
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
