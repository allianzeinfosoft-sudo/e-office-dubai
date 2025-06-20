<div class="card">
    <div class="card-body bg-white">
        <h5 class="card-title">{{ $ksp->ksp_title }}</h5>
        <h6 class="card-subtitle text-muted"> {{ '@'. $ksp->createdBy?->full_name ?? 'N/A' }}
            <div class="d-flex align-items-center lh-1 me-3 mb-2 mb-sm-0">
                <span class="badge badge-dot bg-success me-1"></span>
                <span class="text-muted" style="font-size: 12px;">{{ $ksp->category->category_name ?? 'N/A' }}</span>
            </div>
    </h6>
    </div>
    <img class="img-fluid" src="{{ asset('storage/ksps/'.$ksp->ksp_featured_image) }}" alt="{{ $ksp->ksp_title }}">
    <div class="card-body">
        <p class="card-text"> {!!  $ksp->ksp_description !!} </p>
        <p class="card-text"> <small class="text-muted">{{ \Carbon\Carbon::parse($ksp->created_at)->format('F j Y, h:i A') }}</small> </p>
    </div>
</div>
