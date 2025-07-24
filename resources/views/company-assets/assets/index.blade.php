@extends('layouts.app')

@section('css')
<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .w-90 { width: 90% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
        border-radius: 0px;
    }

    #item-line-table th {
        text-transform: uppercase;
        font-size: 0.6125rem !important;
        letter-spacing: 1px;
        padding-top: 0.68rem;
        padding-bottom: 0.68rem;
    }

    #item-line-table > :not(caption) > * > * {
    padding: 0.20rem 0.20rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    }
    #item-line-table .form-control{
        border-radius: 0.2rem !important;
        font-size: 0.6125rem !important;
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
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Assets /</span> {{ $meta_title }}</h4>
                    <div class="row">
                        <div class="md-4 mb-2">
                        <a class="btn btn-primary" href="{{route('assets.dashboard'); }}">Assets Dashboad</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <h5 class="card-header">Search Asset Item</h5>
                            <div class="card-body">
                                <form id="allocated-item-search" action="{{ route('assets.all') }}" method="GET">
                                    @csrf
                                    <div class="row align-items-end"> <!-- Align fields vertically -->

                                        <!-- Textbox -->
                                        <div class="col-md-4">

                                          <select class="form-control select2" name="asset_id" id="asset_id" aria-placeholder="Select Asset ID">
                                            <option value="">Select Asset ID</option>
                                            @foreach ($assetIds as $id)
                                                <option value="{{ $id }}" {{ request('asset_id') == $id ? 'selected' : '' }}>
                                                    {{ \App\Helpers\CustomHelper::itemCodeGenerater($id) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>

                                        <!-- Empty div for dynamic content -->
                                        <div class="col-md-4" id="user_details"></div>

                                        <!-- Buttons -->
                                        <div class="col-md-4">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>




                <!-- Accordion with Icon -->
                <div class="card mb-4 pb-4" id="asset-item-list-accodion">
                  <div class="accordion mt-3 " id="accordionWithIcon">

                        @foreach($assets as $item)

                            <div id="accordion-card-{{ $item->id }}" class="card accordion-item">
                                <h2 class="accordion-header d-flex align-items-center">
                                    <button
                                        type="button"
                                        class="accordion-button {{ $item->id !== 0 ? 'collapsed' : '' }}"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#accordionWithIcon-{{ $item->id }}"
                                        aria-expanded="{{ $item->id === 0 ? 'true' : 'false' }}">
                                        <i class="ti ti-asset ti-xs me-2"></i>
                                        {{-- Allocation #{{ $allocation->id }} --}}
                                        Asset ID: {{ \App\Helpers\CustomHelper::itemCodeGenerater($item->id) }}, SN: {{ $item->serial_number }}
                                    </button>
                                </h2>

                                <div id="accordionWithIcon-{{ $item->id }}" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        <div class="row">

                                            {{-- Asset Items Card --}}
                                            <div class="col-md-12 mb-3">
                                                <div class="card shadow-sm border">
                                                    <div class="card-header bg-secondary text-white">
                                                        Asset Item
                                                    </div>
                                                    <div class="card-body mt-4">

                                                        <p><strong>Asset ID:</strong> {{ \App\Helpers\CustomHelper::itemCodeGenerater($item->id) }}, SN: {{ $item->serial_number }}</p>
                                                        <p><strong>Brand Name:</strong> {{ $item->masterItem->name ?? 'N/A' }}</p>
                                                        <p><strong>Model:</strong> {{ $item->model ?? '-' }}</p>
                                                        <p><strong>Serial Number:</strong> {{ $item->serial_number ?? '-' }}</p>
                                                        <p><strong>Specification:</strong> {{ $item->register_lineitem?->asset_description ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                  </div>

                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $assets->links() }}
                </div>


                </div>
                <x-footer />
                <div class="content-backdrop fade"></div>
                <div class="layout-overlay layout-menu-toggle"></div>
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

@stop



