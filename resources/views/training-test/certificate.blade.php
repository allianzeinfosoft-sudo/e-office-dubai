@extends('layouts.app')

@section('css')
<style>
.certificate-wrapper {
    display: flex;
    justify-content: center;
    margin: 30px auto;
}

/* MAIN CERTIFICATE */
.certificate-bg {
    width: 1000px;      /*   A4 landscape ratio */
    height: 794px;
    background-image: url('/assets/certificates/certificate-bg.png');
    background-size: cover;      /* 🔥 important */
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    padding: 80px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
}

/* CONTENT LAYER */
.certificate-content {
    position: relative;
    z-index: 2;
    padding: 170px;
}

/* TYPOGRAPHY */
.certificate-title {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 20px;
}



.certificate-name {
    font-size: 36px;
    font-weight: bold;
    margin: 25px 0;
}



.certificate-test-title {
    font-size: 24px;
    margin-top: 92px;
    font-weight: 600;
}



/* FOOTER */
.certificate-footer {
    position: absolute;
    bottom: 125px;
    right: 132px;
    font-size: 14px;
}

</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl container-p-y">
                     <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>
                     <div class="mb-3">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>

                        <a href="{{ route('training-test.certificate.download', $testUser->id) }}"
                        class="btn btn-success" >
                            <i class="ti ti-download"></i> Download Certificate
                        </a>


                    </div>


                    <div class="card">
                        <div class="card-body position-relative">
                            <div class="certificate-wrapper">
                                <div class="certificate-bg">

                                    <div class="certificate-content text-center">


                                        <h3 class="certificate-name">{{ auth()->user()->employee->full_name }}(
                                            {{ $testUser->user->employee->department->department ?? 'Department' }}
                                        )</h3>



                                        <h4 class="certificate-test-title">
                                            {{ $testUser->test->title }}
                                        </h4>

                                        <div class="certificate-footer">
                                            {{-- <span>Date: {{ now()->format('d M Y') }}</span> --}}
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>

                </div>

                <x-footer />
            </div>
        </div>
    </div>
</div>
@stop
