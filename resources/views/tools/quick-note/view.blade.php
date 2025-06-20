<div class="row">

    <div class="card overflow-hidden mb-4">
        <div class="card-body">
            <div class="d-flex  align-items-center justify-content-between">
                <span> <i class="ti ti-user ti-md"></i> {{ $quick_note->createdBy->full_name }} </span>
                <span> <i class="ti ti-clock ti-md"></i> {{ date('d M Y', strtotime($quick_note->created_at)) }}</span>
            </div>
            <h4 class="d-flex align-items-center mt-2 mb-4">
                <span class="badge bg-label-success p-2 rounded me-3"><i class="ti ti-file ti-md"></i></span>
                {{ $quick_note->title }}
            </h4>
            <p> {{ $quick_note->note_description }} </p>

            <hr class="container-m-nx my-3">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <div class="article-info">
                    <p class="card-text mb-1">Assigned To :</p>
                    <h5 class="">{{ $quick_note->assignedTo?->full_name }}</h5>
                </div>
            </div>
        </div>
    </div>


    
    <div class="card">
        <div class="card-body pb-0">
            <form method="POST" id="quick_note_comments_form">
                @csrf
                <div class="row">
                    <div class="col-sm-12">
                        <label for="note_comment" class="form-label">Commets</label>
                        <textarea class="form-control" id="note_comment" name="note_comment" rows="3" placeholder="Enter Comment"></textarea>
                    </div>
                    <div class="col-sm-12 mb-3 text-end">
                        <input type="hidden" id="quick_note_id" name="quick_note_id" value="{{ $quick_note->id }}">
                        <button type="button" onclick="addComment()" class="btn btn-primary mt-3" id="comment_btn">Submit</button>
                    </div>
                </div>
            </form>
            <hr class="container-m-nx my-6">
            <ul class="timeline mt-4 mb-0" id="quick_note_comments_list">
                @if($comments->count() > 0)
                    @foreach($comments as $comment)
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
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
