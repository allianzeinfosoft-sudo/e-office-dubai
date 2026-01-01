@extends('layouts.app')
@section('css')
<style>
    .option-box {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .option-box:hover {
        background-color: #f8f9fa;
        border-color: #7367f0;
    }

    .option-box input:checked + span {
        font-weight: 600;
    }

    .sticky-submit {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding-top: 15px;
        border-top: 1px solid #eee;
        margin-top: 20px;
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
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openTrainingTestOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add Training Test</span>
                                </span>
                            </a>
                        </div>
                    </div>


                    <div class="card shadow-sm">
                        <div class="card-body">

                            <!-- Test Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $test->title }}</h4>
                                    <small class="text-muted">
                                        Total Marks: {{ $test->total_marks }} |
                                        Questions: {{ $test->questions->count() }}
                                    </small>
                                </div>
                                <span class="badge bg-label-primary fs-6">
                                    Training Test
                                </span>
                            </div>

                            <hr>

                            <!-- Test Form -->
                            <form method="POST" action="{{ route('training-tests.submit', $test->id) }}">
                                @csrf

                                @foreach($test->questions as $index => $q)
                                    <div class="card mb-4 border">
                                        <div class="card-body">

                                            <!-- Question -->
                                            <div class="d-flex align-items-start mb-3">
                                                <span class="badge bg-primary me-3">
                                                    Q{{ $index + 1 }}
                                                </span>
                                                <h6 class="mb-0 fw-semibold">
                                                    {{ $q->question }}
                                                </h6>
                                            </div>

                                            <!-- Options -->
                                            @foreach(['a','b','c','d'] as $opt)
                                                <label class="d-flex align-items-center border rounded p-3 mb-2 option-box">
                                                    <input
                                                        type="radio"
                                                        name="answers[{{ $q->id }}]"
                                                        value="{{ $opt }}"
                                                        class="form-check-input me-3"
                                                        required
                                                    >
                                                    <span>
                                                        <strong class="me-1">{{ strtoupper($opt) }}.</strong>
                                                        {{ $q->{'option_'.$opt} }}
                                                    </span>
                                                </label>
                                            @endforeach

                                        </div>
                                    </div>
                                @endforeach

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end sticky-submit">
                                    <button class="btn btn-primary btn-lg" onclick="return confirm('Are you sure you want to submit the test?')">
                                        <i class="ti ti-check me-1"></i> Submit Test
                                    </button>
                                </div>
                            </form>

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

@stop

@push('js')
<script>
    window.onbeforeunload = function() {
    return "Your test progress may be lost.";
};
</script>
@endpush













