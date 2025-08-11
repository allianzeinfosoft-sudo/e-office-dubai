<div class="card">
    <div class="card-datatable table-mom">
        <div class="card-datatable table-responsive">
            <table class="table table-bordered table-striped" id="allocation-item-table" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>Asset ID</th>
                        <th>Item Name</th>
                        <th>Brand Name</th>
                        <th>Model</th>
                        <th>Key/ID</th>
                        <th>Specification</th>
                        <th>User / Location</th>
                        <th>Department</th>
                        <th>Project</th>
                        <th>Allocated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                @php $serial = 1; @endphp
                <tbody>
                    @foreach($allocations as $allocIndex => $allocation)
                        @foreach($allocation->items as $itemIndex => $item)
                            <tr>
                                {{-- Serial Number --}}
                                <td>{{ $serial++ }}</td>

                                {{-- Asset ID --}}
                                <td>{{ \App\Helpers\CustomHelper::itemCodeGenerater($item->asset_mapping_id) }}</td>

                                {{-- Item Name --}}
                                <td>{{ $item->item_name ?? 'N/A' }}</td>

                                {{-- Brand --}}
                                <td>{{ $item->masterItem->name ?? 'N/A' }}</td>

                                {{-- Model --}}
                                <td>{{ $item->model ?? '-' }}</td>

                                {{-- Serial Number --}}
                                <td>{{ $item->register_lineitem->item_key_id ?? '-' }}</td>

                                {{-- Specification --}}
                                <td>{{ $item->specification ?? '-' }}</td>

                                {{-- User or Location --}}
                                <td>
                                    @if($allocation->user_type === 'employee')
                                        {{ $allocation->employee->full_name ?? 'N/A' }}
                                    @elseif ($allocation->user_type === 'location')
                                        {{ $allocation->location->name ?? 'N/A' }}
                                    @endif
                                </td>

                                {{-- Department --}}
                                <td>{{ $allocation->department_name->department ?? 'N/A' }}</td>

                                {{-- Project --}}
                                <td>{{ $item->project_info->project_name ?? 'N/A' }}</td>

                                {{-- Allocated At --}}
                                <td>{{ $allocation->created_at->format('d-m-Y') }}</td>

                                {{-- Remarks --}}
                                {{-- <td>{{ $allocation->remarks ?? '-' }}</td> --}}

                                {{-- Actions --}}
                                <td>
                                    <form action="{{ route('assets.return', $item->id) }}" method="POST" class="return-form" id="return-form" data-id="{{ $allocation->id }}">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea name="comment" class="form-control" rows="1" placeholder="Return comment (optional)"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Return to Store
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div id="allocation-pagination">
    {{ $allocations->links('pagination::bootstrap-5') }}
</div>

