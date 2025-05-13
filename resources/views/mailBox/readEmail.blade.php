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
<div class="app-email-view-content py-4">
    <!-- Email View : Last mail-->
    <div class="card email-card-last mx-sm-4 mx-3 mt-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center mb-sm-0 mb-3">
        <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="flex-shrink-0 rounded-circle me-3" height="40" width="40" />
        <div class="flex-grow-1 ms-1">
            <h6 class="m-0">{{ $mail->fromUser->full_name ?? $mail->userData->username }}</h6>
            <small class="text-muted"> < {{ $mail->userData->email ?? '' }} ></small>
        </div>
        </div>
        <div class="d-flex align-items-center">
        <p class="mb-0 me-3 text-muted"> {{ \Carbon\Carbon::parse($mail->created_at)->format('F jS Y, h:i A') }} </p>
        <i class="email-list-item-bookmark ti ti-star ti-xs cursor-pointer me-2 {{ $mail->is_starred ? 'text-warning' : '' }}" onclick="markAsStarred({{ $mail->id }})" id="bookmark_{{ $mail->id }}"></i>
        <div class="dropdown me-3 d-flex align-self-center">
            <button class="btn p-0" type="button" id="dropdownEmailTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownEmailTwo">
                <a class="dropdown-item scroll-to-reply reply-button"
                    href="javascript:void(0)"
                    data-from-id="{{ $mail->from_user_id }}"
                    data-from-name="{{ $mail->userData->username ?? '' }}"
                    data-subject="{{ $mail->subject }}"
                    data-message="{!! htmlentities($mail->message) !!}"
                    data-date="{{ $mail->created_at->format('d M Y, h:i A') }}">
                    <i class="ti ti-corner-up-left me-1"></i>
                    <span class="align-middle">Reply</span>
                </a>
                <a class="dropdown-item scroll-to-forward forward-button"
                    href="javascript:void(0)"
                    data-from-id="{{ $mail->from_user_id }}"
                    data-from-name="{{ $mail->userData->username ?? '' }}"
                    data-subject="Fwd: {{ $mail->subject }}"
                    data-message="{!! htmlentities($mail->message) !!}"
                    data-date="{{ $mail->created_at->format('d M Y, h:i A') }}">
                    <i class="ti ti-share me-1"></i>
                    <span class="align-middle">Forward</span>
                </a>
            </div>
        </div>
        </div>
    </div>
    <div class="card-body">
        {!! $mail->message !!}
        <hr />
        @php
            $attachments = json_decode($mail->attachments, true);
        @endphp
        @if($attachments)
            <p class="email-attachment-title mb-2"> <i class="ti ti-paperclip cursor-pointer me-2"></i> Attachments </p>
            @foreach($attachments as $file)
            <div class="cursor-pointer"> <i class="ti ti-file"></i> <a href="{{ asset('storage/mail_attachments/' . $file) }}" target="_blank"><span class="align-middle ms-1">{{ $file }}</span></a>
            @endforeach
        @endif
        </div>
    </div>
    </div>
</div>
