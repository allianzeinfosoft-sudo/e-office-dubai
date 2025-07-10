@forelse($allocations as $index => $allocation)
    @php
        $firstItem = $allocation->items->first();
    @endphp

    <div class="card accordion-item {{ $index === 0 ? 'active' : '' }}">
        <h2 class="accordion-header d-flex align-items-center">
            <button
                type="button"
                class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                data-bs-toggle="collapse"
                data-bs-target="#accordionWithIcon-{{ $index + 1 }}"
                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                <i class="ti ti-user ti-xs me-2"></i>

                {{-- Allocation #{{ $allocation->id }} --}}
                @if($firstItem)
                    {{ $firstItem->item_name }} Asset ID: {{ $firstItem->asset_id }}, SN: {{ $firstItem->serial_number }}
                @endif
                -( {{ $allocation->employee->full_name ?? 'N/A' }} )
            </button>
        </h2>

        <div id="accordionWithIcon-{{ $index + 1 }}"
             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
            <div class="accordion-body">
                @if($allocation->items->count())
                    <ul>
                        @foreach($allocation->items as $item)
                            <li>
                                Asset ID: {{ $item->asset_id }}<br>
                                Brand Name: {{ $item->masterItem->name }}<br>
                                Model: {{ $item->model }}<br>
                                Serial Number: {{ $item->serial_number }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No items found for this allocation.</p>
                @endif
            </div>
        </div>
    </div>
@empty
    <p class="text-center">No allocations found for the selected user.</p>
@endforelse

<div id="allocation-pagination">
    {{ $allocations->links('pagination::bootstrap-5') }}
</div>
