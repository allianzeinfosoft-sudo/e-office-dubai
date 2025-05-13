@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />
<style>
  .app-email .app-emails-list .email-list li .email-list-item-time {
    width: 100px;
  }
</style>
@stop


@section('content')
  <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
          <!-- Menu -->
          <x-menu />

          <div class="layout-page">
              <!-- Navbar -->
              <x-header />

              <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> {{ $meta_title }}</h4>

                  <div class="app-email card">
                    <div class="row g-0">

                      <!-- Email Sidebar -->
                      <div class="col app-email-sidebar border-end flex-grow-0" id="app-email-sidebar">

                        <!-- Compose Button -->
                        <div class="btn-compost-wrapper d-grid">
                          <button class="btn btn-primary btn-compose" data-bs-toggle="modal" data-bs-target="#emailComposeSidebar" id="emailComposeSidebarLabel"> Compose</button>
                        </div>

                        <!-- Email Filters -->
                        <div class="email-filters py-2">
                          <!-- Email Filters: Folder -->
                          <ul class="email-filter-folders list-unstyled mb-4">
                            <li class="active d-flex justify-content-between" data-target="inbox">
                              <a href="javascript:void(0);" onclick="getMails('inbox');" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-mail"></i>
                                <span class="align-middle ms-2">Inbox</span>
                              </a>
                                <div class="badge bg-label-primary rounded-pill badge-center">{{ $counts['inbox'] }}</div>
                            </li>

                            <li class="d-flex justify-content-between" data-target="sent">
                                <a href="javascript:void(0);" onclick="getMails('sent');" class="d-flex flex-wrap align-items-center">
                                    <i class="ti ti-send ti-xs"></i>
                                    <span class="align-middle ms-2">Sent</span>
                                  </a>
                                  <div class="badge bg-label-success rounded-pill badge-center">{{ $counts['sent'] }}</div>
                            </li>

                            <li class="d-flex justify-content-between" data-target="draft">
                                <a href="javascript:void(0);" onclick="getMails('draft');" class="d-flex flex-wrap align-items-center">
                                    <i class="ti ti-file"></i>
                                    <span class="align-middle ms-2">Draft</span>
                                  </a>
                                  <div class="badge bg-label-info rounded-pill badge-center">{{ $counts['draft'] }}</div>
                            </li>

                            <li class="d-flex justify-content-between" data-target="starred">
                                <a href="javascript:void(0);" onclick="getMails('starred');" class="d-flex flex-wrap align-items-center">
                                    <i class="ti ti-star"></i>
                                    <span class="align-middle ms-2">Starred</span>
                                </a>
                                <div class="badge bg-label-warning rounded-pill badge-center">{{ $counts['starred'] }}</div>
                            </li>
                              
                            <li class="d-flex justify-content-between" data-target="spam">
                              <a href="javascript:void(0);" onclick="getMails('spam');" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-info-circle"></i>
                                <span class="align-middle ms-2">Spam</span>
                              </a>
                              <div class="badge bg-label-dark rounded-pill badge-center">{{ $counts['spam'] }}</div>
                            </li>

                            <li class="d-flex justify-content-between" data-target="trash">
                              <a href="javascript:void(0);" onclick="getMails('trash');" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-trash"></i>
                                <span class="align-middle ms-2">Trash</span>
                              </a>
                              <div class="badge bg-label-danger rounded-pill badge-center">{{ $counts['trash'] }}</div>
                            </li>
                          </ul>
                          <!--/ Email Filters -->
                        </div>

                      </div>
                      <!--/ Email Sidebar -->

                        <!-- Emails List -->
                        <div class="col app-emails-list">
                          <div class="shadow-none border-0">
                            <div class="emails-list-header p-3 py-lg-3 py-2">

                            <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center w-100">
                              <h5 class="text-capitalize" id="emails-list-title">Inbox </h5></h5>
                            </div>
                            
                          </div>

                          <hr class="mx-n3 emails-list-header-hr">
                          
                            <!-- Email List: Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                              <div class="d-flex align-items-center">
                                <div class="form-check mb-0 me-2">
                                  <input class="form-check-input" type="checkbox" id="email-select-all" />
                                  <label class="form-check-label" for="email-select-all"></label>
                                </div>
                                <i class="ti ti-trash email-list-delete cursor-pointer me-2" onclick="moveToTrash()"></i>
                                <input type="hidden" name="current_folder" id="current_folder">
                                <i class="ti ti-mail-opened email-list-read cursor-pointer me-2" onclick="markAsRead()"></i>

                                <div class="dropdown me-2">
                                  <button class="btn p-0" type="button" id="dropdownMenuFolderOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti ti-folder"></i></button>
                                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuFolderOne">
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('sent')"> <i class="ti ti-mail ti-xs me-1"></i> <span class="align-middle">Inbox</span> </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('spam')"> <i class="ti ti-info-circle ti-xs me-1"></i> <span class="align-middle">Spam</span> </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('draft')"> <i class="ti ti-file ti-xs me-1"></i> <span class="align-middle">Draft</span> </a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="moveToFolder('trash')"> <i class="ti ti-trash ti-xs me-1"></i> <span class="align-middle">Trash</span></a>
                                  </div>
                                </div>

                              </div>

                              <div class="email-pagination d-sm-flex d-none align-items-center flex-wrap justify-content-between justify-sm-content-end">
                                <span class="d-sm-block d-none mx-3 text-muted">1-10 of 653</span>
                                <i class="email-prev ti ti-chevron-left scaleX-n1-rtl cursor-pointer text-muted me-2"></i>
                                <i class="email-next ti ti-chevron-right scaleX-n1-rtl cursor-pointer"></i>
                              </div>
                            </div>
                          </div>

                          <hr class="container-m-nx m-0" />

                          <!-- Email List: Items -->
                          <div class="email-list pt-0">
                            <ul class="list-unstyled m-0">
                            </ul>
                          </div>

                        </div>
                        <div class="app-overlay"></div>
                      </div>
                      <!-- /Emails List -->

                      <!-- Email View -->
                      <div class="col  app-email-view flex-grow-0 bg-body" id="app-email-view">

                      </div>
                      <!-- Email View -->

                    </div>

                    <!-- Compose Email -->
                    <div class="app-email-compose modal" id="emailComposeSidebar" tabindex="-1" aria-labelledby="emailComposeSidebarLabel" aria-hidden="true">
                      <div class="modal-dialog m-0 me-md-4 mb-4 modal-lg">
                        <div class="modal-content p-0">
                          <div class="modal-header py-3 bg-body">
                            <h5 class="modal-title fs-5">Compose Mail</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body flex-grow-1 pb-sm-0 p-4 py-2">

                            <form class="email-compose-form" method="POST">
                              @csrf
                              <div class="email-compose-to d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0" for="emailContacts">To:</label>
                                <div class="select2-primary border-0 shadow-none flex-grow-1 mx-2">
                                  <select class="select2 select-email-contacts form-select" id="emailContacts" name="emailContacts" multiple>
                                    @if($employees->isnotempty())
                                        @foreach($employees as $employee)
                                            <option data-avatar="{{$employee->profile_image}}" value="{{$employee->user_id}}">{{ $employee->full_name }}</option>
                                        @endforeach
                                    @endif
                                  </select>
                                </div>
                                <div class="email-compose-toggle-wrapper">
                                  <a class="email-compose-toggle-cc" href="javascript:void(0);">Cc</a> | <a class="email-compose-toggle-bcc" href="javascript:void(0);">Bcc</a>
                                </div>
                              </div>

                              <div class="email-compose-cc d-none">
                                <hr class="container-m-nx my-2" />
                                <div class="d-flex align-items-center">
                                  <label for="email-cc" class="form-label mb-0">Cc: </label>
                                  <input type="text" class="form-control border-0 shadow-none flex-grow-1 mx-2" id="email-cc" placeholder="someone@email.com" />
                                </div>
                              </div>
                              <div class="email-compose-bcc d-none">
                                <hr class="container-m-nx my-2" />
                                <div class="d-flex align-items-center">
                                  <label for="email-bcc" class="form-label mb-0">Bcc: </label>
                                  <input type="text" class="form-control border-0 shadow-none flex-grow-1 mx-2" id="email-bcc" placeholder="someone@email.com" />
                                </div>
                              </div>
                              <hr class="container-m-nx my-2" />
                              <div class="email-compose-subject d-flex align-items-center mb-2">
                                <label for="email-subject" class="form-label mb-0">Subject:</label>
                                <input type="text" class="form-control border-0 shadow-none flex-grow-1 mx-2" id="email-subject" placeholder="Project Details" />
                              </div>
                              <div class="email-compose-message container-m-nx">
                                <div class="d-flex justify-content-end">
                                  <div class="email-editor-toolbar border-bottom-0 w-100">
                                    <span class="ql-formats me-0">
                                      <button class="ql-bold"></button>
                                      <button class="ql-italic"></button>
                                      <button class="ql-underline"></button>
                                      <button class="ql-list" value="ordered"></button>
                                      <button class="ql-list" value="bullet"></button>
                                      <button class="ql-link"></button>
                                      <button class="ql-image"></button>
                                    </span>
                                  </div>
                                </div>
                                <div class="email-editor"></div>
                              </div>
                              <hr class="container-m-nx mt-0 mb-2" />
                              <div class="email-compose-actions d-flex justify-content-between align-items-center mt-3 mb-3">
                              <div class="d-flex align-items-center">
                                <div class="btn-group">
                                  <button type="button" class="btn btn-primary btn-send-mail" data-folder="sent">
                                    <i class="ti ti-send ti-xs me-1"></i>Send
                                  </button>
                                  <button type="button" class="btn btn-outline-secondary btn-save-draft ms-2" data-folder="draft">
                                    <i class="ti ti-file-text ti-xs me-1"></i>Save Draft
                                  </button>
                                </div>
                                <label for="attach-file"><i class="ti ti-paperclip cursor-pointer ms-2"></i></label>
                                <input type="file" name="file-input" class="d-none" id="attach-file" />
                              </div>

                                <div class="d-flex align-items-center">
                                  <div class="dropdown">
                                    <button class="btn p-0" type="button" id="dropdownMoreActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMoreActions">
                                      <li><button type="button" class="dropdown-item">Add Label</button></li>
                                      <li><button type="button" class="dropdown-item">Plain text mode</button></li>
                                      <li> <hr class="dropdown-divider" /></li>
                                      <li><button type="button" class="dropdown-item">Print</button></li>
                                      <li><button type="button" class="dropdown-item">Check Spelling</button></li>
                                    </ul>
                                  </div>
                                  <button type="reset" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ti ti-trash"></i>
                                  </button>
                                </div>
                              </div>
                            </form>

                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /Compose Email -->
                  </div>



                  <!-- Footer -->
                  <x-footer />
                  <!-- / Footer -->

                  <div class="content-backdrop fade"></div>

                  <!-- Overlay -->
                  <div class="layout-overlay layout-menu-toggle"></div>

                  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
                  <div class="drag-target"></div>

                </div>
              </div>
          </div>
      </div>
  </div>
@stop


@push('js')
<!-- <script src="{{ asset('assets/js/app-email.js') }}"></script> -->
<script>
  var massgeQuill = new Quill('.email-editor', {
    modules: {
      toolbar: '.email-editor-toolbar'
    },
    placeholder: 'Write your message... ',
    theme: 'snow'
  });

  var replyQuill = new Quill('.email-reply-editor', {
          modules: {
            toolbar: '.email-reply-toolbar'
          },
          placeholder: 'Write your message... ',
          theme: 'snow'
        });
        
  
  $(function(){
    getMails('inbox');

    /* store function */
    $('.btn-send-mail').on('click', function() {
      submitMail(3);  // status = 1 for Sent
    });

    $('.btn-save-draft').on('click', function() {
      submitMail(1);  // status = 0 for Draft
    });


    // Filter based on folder type (Inbox, Sent, Draft etc...)
    const emailFilterByFolders = Array.from(document.querySelectorAll('.email-filter-folders li'));

    /* email filter */
    emailFilterByFolders.forEach(folder => {
      folder.addEventListener('click', e => {
        const currentTarget = e.currentTarget;
        // Remove 'active' class from all folders
        emailFilterByFolders.forEach(f => f.classList.remove('active'));
        // Add 'active' to the clicked folder
        currentTarget.classList.add('active');
        // Get the target data and call the appropriate function
        const currentTargetData = currentTarget.getAttribute('data-target');
        getMails(currentTargetData); // Assuming getMails handles the mail fetching based on folder
      });
    });

    /* select all */
    $('#email-select-all').on('click', function() {
      if (this.checked) {
        $('.email-list-item-input').prop('checked', true);
      } else {
        $('.email-list-item-input').prop('checked', false);
      }
    });

    // Toggle CC/BCC input
    const toggleCC = document.querySelector('.email-compose-toggle-cc');
    const toggleBCC = document.querySelector('.email-compose-toggle-bcc');

    if (toggleBCC) {
      toggleBCC.addEventListener('click', e => {
        Helpers._toggleClass(document.querySelector('.email-compose-bcc'), 'd-block', 'd-none');
      });
    }

    if (toggleCC) {
      toggleCC.addEventListener('click', e => {
        Helpers._toggleClass(document.querySelector('.email-compose-cc'), 'd-block', 'd-none');
      });
    }

    /* Serch Mail */
    const emailSearch = document.querySelector('.email-search-input');

    if (emailSearch) {
      emailSearch.addEventListener('keyup', e => {
        let searchValue = e.currentTarget.value.toLowerCase(),
          searchEmailListItems = {},
          selectedFolderFilter = document.querySelector('.email-filter-folders .active').getAttribute('data-target');

        // Filter emails based on selected folders
        if (selectedFolderFilter != 'inbox') {
          searchEmailListItems = [].slice.call(
            document.querySelectorAll('.email-list-item[data-' + selectedFolderFilter + '="true"]')
          );
        } else {
          searchEmailListItems = [].slice.call(document.querySelectorAll('.email-list-item'));
        }

        // console.log(searchValue);
        searchEmailListItems.forEach(searchEmailListItem => {
          let searchEmailListItemText = searchEmailListItem.textContent.toLowerCase();
          if (searchValue) {
            if (-1 < searchEmailListItemText.indexOf(searchValue)) {
              searchEmailListItem.classList.add('d-block');
            } else {
              searchEmailListItem.classList.add('d-none');
            }
          } else {
            searchEmailListItem.classList.remove('d-none');
          }
        });
      });
    }

    });

    function getMails(folder = 'inbox') {
      $('#current_folder').val(folder);
      $('#emails-list-title').text(folder);
      $.ajax({
          url: '/mail-boxes/folder/' + folder,
          type: 'GET',
          success: function(response) {
              $('#app-email-view').removeClass('show');
              let emailList = '';

              if (response.status && response.data.length > 0) {
                  response.data.forEach(function(mail) {
                    
                    const profileImage = mail && mail.from_user && mail.from_user.profile_image
                    ? `/storage/${mail.from_user.profile_image}`
                    : '../../assets/img/avatars/1.png';

                      emailList += `
                      <li class="email-list-item" data-starred="${mail.is_starred == 1 ? 'true' : 'false'}" onclick="openMail(${mail.id})">
                          <div class="d-flex align-items-center">
                              <div class="form-check mb-0">
                                  <input class="email-list-item-input form-check-input" type="checkbox" id="email-${mail.id}" value="${mail.id}" onclick="event.stopPropagation()" />
                                  <label class="form-check-label" for="email-${mail.id}"></label>
                              </div>
                              <i class="email-list-item-bookmark ti ti-star ti-xs d-sm-inline-block d-none cursor-pointer ms-2 me-3 ${mail.is_starred ? 'text-warning' : ''}" onclick="markAsStarred(${mail.id})" id="bookmark_${mail.id}"></i>
                              <img src="${ profileImage }" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-2" height="32" width="32" />
                              <div class="email-list-item-content ms-2 ms-sm-0 me-2">
                                  ${mail.mark_as_read ? '<em>' : '<strong>' }
                                  <span class="h6 email-list-item-username me-2">${mail.from_user ? mail.from_user.full_name : 'Unknown Sender'}</span>
                                  <span class="email-list-item-subject d-xl-inline-block d-block">${mail.subject.substring(0, 60)}...</span>
                                  ${mail.mark_as_read ? '</em>'  : '</strong>' }
                              </div>
                              <div class="email-list-item-meta ms-auto d-flex align-items-center">
                                  ${ mail.attachments ? `<span class="email-list-item-attachment ti ti-paperclip ti-xs cursor-pointer me-2 float-end float-sm-none"></span>` : '' }
                                  <span class="email-list-item-label badge badge-dot bg-danger d-none d-md-inline-block me-2" data-label="private"></span>
                                  <small class="email-list-item-time text-muted">${ timeAgo(mail.created_at) }</small>
                                  <ul class="list-inline email-list-item-actions text-nowrap">
                                      <li class="list-inline-item email-read"> 
                                        ${mail.mark_as_read 
                                        ? `<i class="ti ti-mail-opened" onclick="markAsRead(${mail.id})"></i>`
                                        : `<i class="ti ti-mail" onclick="markAsRead(${mail.id})"></i>` }
                                        </li>
                                      <li class="list-inline-item email-delete"><i class="ti ti-trash" onclick="moveToFolder('trash', ${mail.id})"></i></li>
                                  </ul>
                              </div>
                          </div>
                      </li>`;
                  });
              } else {
                  emailList = `<li class="p-3 text-center text-muted">📨 No mail fetched in "${folder}".</li>`;
              }

              $('.email-list ul').html(emailList);
              $('#emailComposeSidebar').modal('hide');
          },
          error: function(xhr) {
              $('.email-list ul').html(`<li class="p-3 text-center text-danger">⚠️ Error loading "${folder}" mails.</li>`);
              console.error(xhr.responseJSON?.message || 'Request failed');
          }
      });
  }


  function submitMail(status) {

    let toUsers = $('#emailContacts').val();
    let ccUsers = $('#email-cc').val().split(',').map(email => email.trim()).filter(Boolean);
    let bccUsers = $('#email-bcc').val().split(',').map(email => email.trim()).filter(Boolean);
    let subject = $('#email-subject').val();
    let message = massgeQuill.root.innerHTML;
    let formData = new FormData();

    formData.append('_token', $('input[name="_token"]').val());
    formData.append('to_user_ids', JSON.stringify(toUsers));
    formData.append('cc_user_ids', JSON.stringify(ccUsers));
    formData.append('bcc_user_ids', JSON.stringify(bccUsers));
    formData.append('subject', subject);
    formData.append('message', message);
    formData.append('status', status);  // 1 = Sent, 0 = Draft

    let files = $('#attach-file')[0].files;
    for (let i = 0; i < files.length; i++) {
        formData.append('attachments[]', files[i]);
    }

    $.ajax({
        url: '/mail-boxes',  // Laravel route
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.status) {
              alert(status === 3 ? 'Mail sent!' : 'Draft saved!');
              $('.email-compose-form')[0].reset();
              $('#emailContacts').val(null).trigger('change');
              massgeQuill.root.innerHTML = '';
              const composeModalEl = document.getElementById('emailComposeSidebar');
              const composeModal = bootstrap.Modal.getInstance(composeModalEl);
              if (composeModal) {
                composeModal.hide();  // ✅ Properly hides the modal
              }

            } else {
                alert('Something went wrong!');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseJSON);
            alert('Error occurred.');
        }
    });
}

function openMail(mailId = null){

  if(mailId){
    let url = "{{ route('mail-boxes.show', ':mailBox') }}".replace(':mailBox', mailId);
    $.ajax({
      type: "get",
      url: url,
      dataType: "json",
      success: function (response) {
        $('#app-email-view').html(response.html);
        var replyQuill = new Quill('.email-reply-editor', {
          modules: {
            toolbar: '.email-reply-toolbar'
          },
          placeholder: 'Write your message... ',
          theme: 'snow'
        });
      }
    });
  }

  $('#app-email-view').toggleClass('show');
}

function timeAgo(dateStr) {
  const now = new Date();
  const date = new Date(dateStr);
  const seconds = Math.floor((now - date) / 1000);

  const intervals = {
    year: 31536000,
    month: 2592000,
    week: 604800,
    day: 86400,
    hour: 3600,
    minute: 60,
    second: 1
  };

  for (let unit in intervals) {
    const value = Math.floor(seconds / intervals[unit]);
    if (value > 0) {
      return value === 1 ? `1 ${unit} ago` : `${value} ${unit}s ago`;
    }
  }
  return 'just now';
}

function moveToTrash(){
  var current_folder = $('#current_folder').val();

  if(current_folder == 'trash'){
    if(confirm('Are you sure you want to permanently delete the selected email(s)?')){

      let selectedIds = $('.email-list-item-input:checked').map(function () {
        return $(this).val();
      }).get(); // Convert jQuery object to array
    
      if (selectedIds.length === 0) {
        alert('No emails selected.');
        return;
      }
      const url = "{{ route('mail-boxes.destroy') }}"; // You will define this route

      $.ajax({
        type: "POST",
        url: url,
        data: {
          _token: '{{ csrf_token() }}',
          mailIds: selectedIds
        },
        dataType: "json",
        success: function (response) {
          alert('Email(s) permanently deleted.');
          getMails(current_folder);
        },
        error: function (xhr, status, error) {
          console.error('Error deleting emails:', error);
          alert('Failed to delete email(s).');
        }
      });
    }

  }else{
    // Get all checked checkboxes
    let selectedIds = $('.email-list-item-input:checked').map(function () {
      return $(this).val();
    }).get(); // Convert jQuery object to array
  
    if (selectedIds.length === 0) {
      alert('No emails selected.');
      return;
    }
  
    let url = "{{ route('mail-boxes.move-to-folder') }}"; // You will define this route
  
    $.ajax({
      type: "POST",
      url: url,
      data: {
        _token: '{{ csrf_token() }}',
        mailIds: selectedIds,
        folder: 'trash'
      },
      dataType: "json",
      success: function (response) {
        console.log('Moved to trash:', response);
        getMails(current_folder);
        // Optionally reload or refresh the UI here
      },
      error: function (xhr, status, error) {
        console.error('Error moving emails:', error);
      }
    });
  }
}

$(document).on('click', '.reply-button', function () {
  const fromId = $(this).data('from-id');
  const fromName = $(this).data('from-name');
  const subject = $(this).data('subject');
  const message = $(this).data('message');
  const date = $(this).data('date');

  replyToMail({
    from_user_id: fromId,
    from_name: fromName,
    subject: subject,
    message: message,
    date: date
  });
});

$(document).on('click', '.forward-button', function () {
  const fromId = $(this).data('from-id');
  const fromName = $(this).data('from-name');
  const subject = $(this).data('subject');
  const message = $(this).data('message');
  const date = $(this).data('date');

  forwardMail({
    from_user_id: fromId,
    from_name: fromName,
    subject: subject,
    message: message,
    date: date
  });
});


function moveToFolder(folder, id = null) {
  event.stopPropagation();
  var current_folder = $('#current_folder').val();

  let selectedIds;

  if (id) {
    // Single ID passed, use it
    selectedIds = [id];
  } else {
    // Get all checked checkboxes
    selectedIds = $('.email-list-item-input:checked').map(function () {
      return $(this).val();
    }).get(); // Convert jQuery object to array

    if (selectedIds.length === 0) {
      alert('No emails selected.');
      return;
    }
  }

  let url = "{{ route('mail-boxes.move-to-folder') }}";

  $.ajax({
    type: "POST",
    url: url,
    data: {
      _token: '{{ csrf_token() }}',
      mailIds: selectedIds,
      folder: folder
    },
    dataType: "json",
    success: function (response) {
      getMails(current_folder);
    },
    error: function (xhr, status, error) {
      console.error('Error moving emails:', error);
    }
  });
}


function markAsStarred(mailId){
  event.stopPropagation();
  var current_folder = $('#current_folder').val();

  let url = "{{ route('mail-boxes.mark-as-starred') }}";

  $.ajax({
    type: "POST",
    url: url,
    data: {
      '_token': '{{ csrf_token() }}',
      'mailId': mailId
    },
    dataType: "json",
    success: function (response) {
      if(response.is_starred){
        $('#bookmark_'+mailId).addClass(response.is_starred);
      }else{
        $('#bookmark_'+mailId).removeClass('text-warning');
      }
      getMails(current_folder);
    },
    error: function (xhr, status, error) {
      console.error('Error:', error);
    }
  });
}

function markAsRead(mailId = null){
  var current_folder = $('#current_folder').val();
  
  if(mailId){
    event.stopPropagation();
    let url = "{{ route('mail-boxes.mark-read') }}";
    $.ajax({
      type: "POST",
      url: url,
      data: {
        '_token': '{{ csrf_token() }}',
        'mailId': mailId
      },
      dataType: "json",
      success: function (response) {
        getMails(current_folder);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
      }
    });

  }else{
      // Get all checked checkboxes
      let selectedIds = $('.email-list-item-input:checked').map(function () {
        return $(this).val();
      }).get(); // Convert jQuery object to array
    
      if (selectedIds.length === 0) {
        alert('No emails selected.');
        return;
      }
    
      let url = "{{ route('mail-boxes.mark-as-read') }}"; // You will define this route
    
      $.ajax({
        type: "POST",
        url: url,
        data: {
          _token: '{{ csrf_token() }}',
          mailIds: selectedIds,
        },
        dataType: "json",
        success: function (response) {
          console.log('Moved to trash:', response);
          getMails(current_folder);
          // Optionally reload or refresh the UI here
        },
        error: function (xhr, status, error) {
          console.error('Error moving emails:', error);
        }
      });
  }

}

/* replay function */
function replyToMail(mail) {
  // Open modal
  const composeModal = new bootstrap.Modal(document.getElementById('emailComposeSidebar'));
  composeModal.show();

  setTimeout(() => {
    // Set recipient
    $('#emailContacts').val(mail.from_user_id).trigger('change');

    // Set subject
    let subject = mail.subject || '';
    if (!subject.toLowerCase().startsWith('re:')) {
      subject = 'Re: ' + subject;
    }
    $('#email-subject').val(subject);

    // Quill setup
    let quill = Quill.find($('.email-editor')[0]) || new Quill('.email-editor', { theme: 'snow' });

    // Clear and insert quoted message
    quill.setContents([]);
    const quotedMessage = `<p></p><p></p><p>________________________________________________________________________________________________________________</p>
      <p><strong>On ${mail.date}, ${mail.from_name} wrote:</strong></p>
      ${mail.message}
      `;
    quill.root.innerHTML = quotedMessage;

  }, 300);
}

function forwardMail(mail) {
  // Open modal
  const composeModal = new bootstrap.Modal(document.getElementById('emailComposeSidebar'));
  composeModal.show();

  setTimeout(() => {
    // Set recipient
    $('#emailContacts').val(mail.from_user_id).trigger('change');

    // Set subject
    let subject = mail.subject || '';
    if (!subject.toLowerCase().startsWith('fwd:')) {
      subject = 'Fwd: ' + subject;
    }
    $('#email-subject').val(subject);

    // Quill setup
    let quill = Quill.find($('.email-editor')[0]) || new Quill('.email-editor', { theme: 'snow' });

    // Clear and insert forwarded message
    quill.setContents([]);
    const forwardedMessage = `<p></p><p></p><p>________________________________________________________________________________________________________________</p>
      <p><strong>On ${mail.date}, ${mail.from_name} wrote:</strong></p>
      ${mail.message}
      `;
    quill.root.innerHTML = forwardedMessage;

  }, 300);
}

</script>
@endpush
