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

                    <div class="row   align-items-center">
                        <div class="col-md-6 pb-3">
                             <a class="btn btn-danger" href="{{ route('assets.dashboard') }}">
                                <i class="ti ti-home me-0 me-sm-1 ti-xs"></i>
                            </a>
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


                     <div class="card">
                            <div class="card-datatable table-mom">
                                <div class="card-datatable table-responsive">
                                    <table class="table table-bordered table-striped" id="asset-item-table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>Sl No.</th>
                                                <th>Asset ID</th>
                                                <th>Classificatin</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Item</th>
                                                <th>Brand Name</th>
                                                <th>Model</th>
                                                <th>Serial Number</th>
                                                <th>Specifications</th>
                                                <th>Price</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($assets as $item)
                                             <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \App\Helpers\CustomHelper::itemCodeGenerater($item->id) }}</td>
                                                <td>{{ $item->register_lineitem?->asset_classification?->name ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->asset_category?->name ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->asset_type?->name ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->asset_item?->name ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->asset_brand ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->item_model ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->serial_number ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->asset_description ?? '-' }}</td>
                                                <td>{{ $item->register_lineitem?->asset_price ?? '-' }}</td>
                                                <td>
                                                    {{-- <a href="javascript:void(0)" onclick="openOffcanvas({{$item->register_lineitem->id}})" class="btn btn-sm btn-icon btn-primary">
                                                        <i class="ti ti-edit"></i>
                                                    </a>--}}
                                                    <a href="javascript:void(0)" onclick="deleteAssetItem({{$item->register_lineitem->id  ?? ''}}, this)" class="btn btn-sm btn-icon btn-danger">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

@push('js')
<script>
 function deleteAssetItem(id,element) {
        if (confirm('Are you sure you want to delete this Asset Entry?')) {
            $.ajax({
                url: 'items/delete/' + id,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                    success: function (response) {
                    toastr["success"](response.message); // ✅ Use success toast
                    $(element).closest('tr').remove();
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr["error"](xhr.responseJSON.message); // Show JSON error
                    } else {
                        toastr["error"]("Failed to delete asset.");
                        console.error(xhr.responseText); // Debug response in console
                    }
                }
            });
        }
    }

</script>
@endpush
