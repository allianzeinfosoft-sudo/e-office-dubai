<div class="card shadow-none border-0 rounded-0 app-email-view-header p-3 py-md-3 py-2">
    <!-- Email View : Title  bar-->
    <div class="d-flex justify-content-between align-items-center py-2">
    <div class="d-flex align-items-center overflow-hidden">
        <i class="ti ti-chevron-left ti-sm cursor-pointer me-2" onclick="openMail()"></i>
        <h6 class="text-truncate mb-0 me-2">{{ $mail->subject }}</h6>
        <span class="badge bg-label-danger rounded-pill">Private</span>
    </div>
    <!-- Email View : Action  bar-->
    <div class="d-flex align-items-center">
        <!-- <i class="ti ti-printer mt-1 cursor-pointer d-sm-block d-none"></i> -->
        <!-- <div class="dropdown ms-3">
        <button class="btn p-0" type="button" id="dropdownMoreOptions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMoreOptions">
            <a class="dropdown-item" href="javascript:void(0)"><i class="ti ti-mail ti-xs me-1"></i><span class="align-middle">Mark as unread</span></a>
            <a class="dropdown-item" href="javascript:void(0)"><i class="ti ti-mail-opened ti-xs me-1"></i><span class="align-middle">Mark as unread</span></a>
            <a class="dropdown-item" href="javascript:void(0)"><i class="ti ti-star ti-xs me-1"></i><span class="align-middle">Add star</span></a>
            <a class="dropdown-item" href="javascript:void(0)"><i class="ti ti-calendar ti-xs me-1"></i><span class="align-middle">Create Event</span></a>
            <a class="dropdown-item" href="javascript:void(0)"><i class="ti ti-volume-off ti-xs me-1"></i><span class="align-middle">Mute</span></a>
            <a class="dropdown-item d-sm-none d-block" href="javascript:void(0)"><i class="ti ti-printer ti-xs me-1"></i><span class="align-middle">Print</span></a>
        </div>
        </div> -->
    </div>

    </div>
    <hr class="app-email-view-hr mx-n3 mb-2" />

    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="ti ti-trash cursor-pointer me-3" data-bs-toggle="sidebar" data-target="#app-email-view" onclick="moveToFolder('trash', {{ $mail->id }})"></i>
            <i class="ti ti-mail-opened cursor-pointer me-3" onclick="markAsRead({{ $mail->id }})"></i>

            <div class="dropdown me-3">
                <button class="btn p-0" type="button" id="dropdownMenuFolderTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-folder"></i></button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuFolderTwo">
                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('spam', {{ $mail->id }})"><i class="ti ti-info-circle ti-xs me-1"></i><span class="align-middle">Spam</span></a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('draft', {{ $mail->id }})"><i class="ti ti-pencil ti-xs me-1"></i><span class="align-middle">Draft</span></a>
                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('trash', {{ $mail->id }})"><i class="ti ti-trash ti-xs me-1"></i><span class="align-middle">Trash</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="m-0" />

<!-- Email View : Content-->
<div class="email-read-content p-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h5 class="mb-1">{{ $mail->subject }}</h5>
            <small class="text-muted">
                {{ \Carbon\Carbon::parse($mail->external_date ?? $mail->created_at)->format('d M Y h:i A') }}
            </small>
        </div>
        <button class="btn-close" onclick="$('#app-email-view').removeClass('show')"></button>
    </div>

    <hr>

    <!-- Sender Section -->
    <div class="d-flex align-items-center mb-3">

        <!-- Sender Avatar -->
        <img src="{{ $mail->fromUser && $mail->fromUser->profile_image 
                        ? asset('storage/' . $mail->fromUser->profile_image)
                        : asset('assets/img/avatars/default-avatar.png') }}"
             class="rounded-circle me-3"
             width="45" height="45">

        <div>
            <div class="fw-bold">

                @if($mail->external_from)
                    {{ $mail->external_from }}
                @elseif($mail->fromUser)
                    {{ $mail->fromUser->full_name }}
                @else
                    Unknown Sender
                @endif

            </div>

            <div class="text-muted small">

                @if($mail->external_from)
                    {{-- Extract clean email from format: "Name <email>" --}}
                    @php
                        preg_match('/<(.*?)>/', $mail->external_from, $match);
                    @endphp
                    {{ $match[1] ?? $mail->external_from }}

                @elseif($mail->userData)
                    {{ $mail->userData->email }}

                @else
                    N/A
                @endif

            </div>
        </div>
    </div>

    <hr>

    <!-- Email Body -->
    <div class="email-body mt-3">
        {!! $mail->message !!}
    </div>

    @if(!empty($mail->attachments) && is_array($mail->attachments) && count($mail->attachments) > 0)
        <hr>

        <h6 class="fw-bold">Attachments ({{ count($mail->attachments) }})</h6>

        <div class="mt-2">
            @foreach($mail->attachments as $file)
                <div class="d-flex align-items-center mb-2">

                    <i class="ti ti-paperclip me-2"></i>

                    <div>
                        <div>{{ $file['filename'] }}</div>

                        <a href="{{ route('mail-boxes.download-attachment', [$mail->id, $loop->index]) }}"
                        class="small text-primary">
                            Download
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>

