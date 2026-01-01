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
<div class="container text-center py-5">

    <h2 class="mb-3 text-success">
        🎉 You have successfully completed the test!
    </h2>

    <p class="fs-5">
        <strong>Test:</strong> {{ $testUser?->test?->title }}
    </p>

    <p class="fs-5">
        <strong>Score:</strong> {{ $testUser->score }} / {{ $testUser->total_marks }}
    </p>

    <p class="fs-5">
        <strong>Result:</strong>
        @if($testUser->result === 'pass')
            <span class="badge bg-success">PASS</span>
        @else
            <span class="badge bg-danger">FAIL</span>
        @endif
    </p>

    <hr class="my-4">

    @if($testUser->result === 'pass')
        <h5 class="text-success mb-3">
            👏 Congratulations! You passed the test.
        </h5>

        <a href="{{ route('training-tests.certificate', $testUser?->test?->id) }}"
           class="btn btn-primary">
            <i class="ti ti-award"></i> Download Certificate
        </a>
    @else
        <h5 class="text-warning mb-3">
            💪 Don’t worry! Better luck next time.
        </h5>
    @endif

    <div class="mt-4">
        <a href="{{ route('training-tests.index') }}" class="btn btn-secondary">
            Back to Tests
        </a>
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
@endsection
