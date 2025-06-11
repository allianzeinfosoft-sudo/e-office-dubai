<li class="timeline-item timeline-item-primary pb-4 border-left-dashed">
    <span class="timeline-indicator timeline-indicator-primary">
        <div class="avatar avatar-xs me-2">
            <img src="{{  $comment->employee?->profile_image ? asset('/storage/' . $comment->employee?->profile_image) : asset('assets/img/avatars/default-avatar.png') }}" alt="Avatar" class="rounded-circle">
        </div>
    </span>
    <div class="timeline-event">
        <div class="timeline-header border-bottom mb-3">
            <h6 class="mb-0">{{ $comment->employee?->full_name ?? 'Unknown User' }} Commented</h6>
            <span class="text-muted">{{ $comment->created_at->format('d M, Y H:i:s') }}</span>
        </div>
        <div class="mb-2">
            <span>{{ $comment->comment }}</span>
        </div>
    </div>
</li>