@forelse($allocations as $index => $allocation)
    @php
        $firstItem = $allocation->items->first();
    @endphp

    {{-- @if($allocation->items->count()) --}}
        @foreach($allocation->items as $item)

            <div id="accordion-card-{{ $allocation->id }}" class="card accordion-item">
                <h2 class="accordion-header d-flex align-items-center">
                    <button
                        type="button"
                        class="accordion-button {{ $item->id !== 0 ? 'collapsed' : '' }}"
                        data-bs-toggle="collapse"
                        data-bs-target="#accordionWithIcon-{{ $item->id }}"
                        aria-expanded="{{ $item->id === 0 ? 'true' : 'false' }}">
                        <i class="ti ti-asset ti-xs me-2"></i>
                        {{-- Allocation #{{ $allocation->id }} --}}
                        @if($firstItem)
                            {{ $firstItem->item_name }} Asset ID: {{ $firstItem->asset_id }}, SN: {{ $firstItem->serial_number }}
                        @endif
                        -( {{ $allocation->employee->full_name ?? 'N/A' }} )
                    </button>
                </h2>

                <div id="accordionWithIcon-{{ $item->id }}" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <div class="row">
                            {{-- Allocation Details Card --}}
                            <div class="col-md-6 mb-3">
                                <div class="card shadow-sm border">
                                    <div class="card-header bg-primary text-white">
                                        Allocation Details
                                    </div>
                                    <div class="card-body mt-4">
                                        <p><strong>Employee:</strong> {{ $allocation->employee->full_name ?? 'N/A' }}</p>
                                        <p><strong>Department:</strong> {{ $allocation->department_name->department ?? 'N/A' }}</p>
                                        {{-- <p><strong>Status:</strong> {{ ucfirst($allocation->status) }}</p> --}}
                                        <p><strong>Remarks:</strong> {{ $allocation->remarks ?? '-' }}</p>
                                        <p><strong>Allocated At:</strong> {{ $allocation->created_at->format('d-m-Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Asset Items Card --}}
                            <div class="col-md-6 mb-3">
                                <div class="card shadow-sm border">
                                    <div class="card-header bg-secondary text-white">
                                        Asset Item
                                    </div>
                                    <div class="card-body mt-4">

                                        <p><strong>Asset ID:</strong> {{ $item->asset_id ?? 'N/A' }}</p>
                                        <p><strong>Brand Name:</strong> {{ $item->masterItem->name ?? 'N/A' }}</p>
                                        <p><strong>Model:</strong> {{ $item->model ?? '-' }}</p>
                                        <p><strong>Serial Number:</strong> {{ $item->serial_number ?? '-' }}</p>

                                    </div>
                                </div>
                            </div>

                        <form action="{{ route('assets.return', $item->id) }}" method="POST" class="return-form" data-id="{{ $allocation->id }}">
                            @csrf

                                <div class="mb-2">
                                    <textarea name="comment" class="form-control" rows="2" placeholder="Enter return comment (optional)"></textarea>
                                </div>

                                <button type="submit" class="btn btn-danger btn-sm w-20">
                                    Return to Store
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    {{-- @else
        <p>No items found for this allocation.</p>
    @endif --}}

@empty
    <p class="text-center">No allocations found for the selected user.</p>
@endforelse

<div id="allocation-pagination">
    {{ $allocations->links('pagination::bootstrap-5') }}
</div>
