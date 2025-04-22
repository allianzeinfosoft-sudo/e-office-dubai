@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />
@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">    
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
                            <span class="align-middle ms-2">Inbox</span></a>
                            <div class="badge bg-label-primary rounded-pill badge-center">4</div>
                        </li>

                        <li class="d-flex" data-target="sent">
                            <a href="javascript:void(0);" onclick="getMails('sent');" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-send ti-xs"></i>
                                <span class="align-middle ms-2">Sent</span>
                            </a>
                        </li>

                        <li class="d-flex" data-target="draft">
                            <a href="javascript:void(0);" onclick="getMails('draft');" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-file"></i>
                                <span class="align-middle ms-2">Draft</span>
                            </a>
                        </li>

                        <li class="d-flex justify-content-between" data-target="starred">
                            <a href="javascript:void(0);" onclick="getMails('starred');" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-star"></i>
                                <span class="align-middle ms-2">Starred</span>
                            </a>
                            <div class="badge bg-label-warning rounded-pill badge-center">10</div>
                        </li>
                        
                        <li class="d-flex align-items-center" data-target="spam">
                          <a href="javascript:void(0);" onclick="getMails('spam');" class="d-flex flex-wrap align-items-center">
                            <i class="ti ti-info-circle"></i>
                            <span class="align-middle ms-2">Spam</span>
                          </a>
                        </li>

                        <li class="d-flex align-items-center" data-target="trash">
                          <a href="javascript:void(0);" onclick="getMails('trash');" class="d-flex flex-wrap align-items-center">
                            <i class="ti ti-trash"></i>
                            <span class="align-middle ms-2">Trash</span>
                          </a>
                        </li>
                      </ul>

                      <!-- Email Filters: Labels -->
                      <div class="email-filter-labels">
                        <small class="fw-normal text-uppercase text-muted m-4">Labels</small>
                        <ul class="list-unstyled mb-0 mt-2">
                          <li data-target="work">
                            <a href="javascript:void(0);">
                              <span class="badge badge-dot bg-success"></span>
                              <span class="align-middle ms-2">Work</span>
                            </a>
                          </li>

                          <li data-target="company">
                            <a href="javascript:void(0);">
                              <span class="badge badge-dot bg-primary"></span>
                              <span class="align-middle ms-2">Company</span>
                            </a>
                          </li>

                          <li data-target="important">
                            <a href="javascript:void(0);">
                              <span class="badge badge-dot bg-info"></span>
                              <span class="align-middle ms-2">Important</span>
                            </a>
                          </li>

                          <li data-target="private">
                            <a href="javascript:void(0);">
                              <span class="badge badge-dot bg-danger"></span>
                              <span class="align-middle ms-2">Private</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                      <!--/ Email Filters -->
                    </div>
                  </div>
                  <!--/ Email Sidebar -->

                  <!-- Emails List -->
                  <div class="col app-emails-list">
                    <div class="shadow-none border-0">
                      <div class="emails-list-header p-3 py-lg-3 py-2">

                        <!-- Email List: Search -->
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="d-flex align-items-center w-100">
                            <i class="ti ti-menu-2 ti-sm cursor-pointer d-block d-lg-none me-3" data-bs-toggle="sidebar" data-target="#app-email-sidebar" data-overlay></i>
                            <div class="mb-0 mb-lg-2 w-100">
                              <div class="input-group input-group-merge shadow-none">
                                <span class="input-group-text border-0 ps-0" id="email-search">
                                  <i class="ti ti-search"></i>
                                </span>
                                <input type="text" class="form-control email-search-input border-0" placeholder="Search mail" aria-label="Search mail" aria-describedby="email-search" />
                              </div>
                            </div>
                          </div>

                          <div class="d-flex align-items-center mb-0 mb-md-2">
                            <i class="ti ti-rotate-clockwise rotate-180 scaleX-n1-rtl cursor-pointer email-refresh me-2 mt-1"></i>
                            <div class="dropdown d-flex align-self-center">
                              <button class="btn p-0" type="button" id="emailsActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti ti-dots-vertical"></i></button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="emailsActions">
                                <a class="dropdown-item" href="javascript:void(0)">Mark as read</a>
                                <a class="dropdown-item" href="javascript:void(0)">Mark as unread</a>
                                <a class="dropdown-item" href="javascript:void(0)">Delete</a>
                                <a class="dropdown-item" href="javascript:void(0)">Archive</a>
                              </div>
                            </div>
                          </div>

                        </div>
                        <hr class="mx-n3 emails-list-header-hr" />

                        <!-- Email List: Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="d-flex align-items-center">
                            <div class="form-check mb-0 me-2">
                              <input class="form-check-input" type="checkbox" id="email-select-all" />
                              <label class="form-check-label" for="email-select-all"></label>
                            </div>
                            <i class="ti ti-trash email-list-delete cursor-pointer me-2"></i>
                            <i class="ti ti-mail-opened email-list-read cursor-pointer me-2"></i>

                            <div class="dropdown me-2">
                              <button class="btn p-0" type="button" id="dropdownMenuFolderOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti ti-folder"></i></button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuFolderOne">
                                <a class="dropdown-item" href="javascript:void(0)"> <i class="ti ti-info-circle ti-xs me-1"></i> <span class="align-middle">Spam</span> </a>
                                <a class="dropdown-item" href="javascript:void(0)"> <i class="ti ti-file ti-xs me-1"></i> <span class="align-middle">Draft</span> </a>
                                <a class="dropdown-item" href="javascript:void(0)"> <i class="ti ti-trash ti-xs me-1"></i> <span class="align-middle">Trash</span></a>
                              </div>
                            </div>

                            <div class="dropdown">
                              <button class="btn p-0" type="button" id="dropdownLabelOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-tag"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLabelOne">
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="badge badge-dot bg-success me-1"></i>
                                  <span class="align-middle">Workshop</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="badge badge-dot bg-primary me-1"></i>
                                  <span class="align-middle">Company</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="badge badge-dot bg-info me-1"></i>
                                  <span class="align-middle">Important</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="badge badge-dot bg-danger me-1"></i>
                                  <span class="align-middle">Private</span>
                                </a>
                              </div>
                            </div>

                          </div>
                          <div
                            class="email-pagination d-sm-flex d-none align-items-center flex-wrap justify-content-between justify-sm-content-end">
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
                  <div class="col app-email-view flex-grow-0 bg-body" id="app-email-view">
                    <div class="card shadow-none border-0 rounded-0 app-email-view-header p-3 py-md-3 py-2">
                      <!-- Email View : Title  bar-->
                      <div class="d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center overflow-hidden">
                          <i class="ti ti-chevron-left ti-sm cursor-pointer me-2" data-bs-toggle="sidebar" data-target="#app-email-view"></i>
                          <h6 class="text-truncate mb-0 me-2">Focused impactful open issues</h6>
                          <span class="badge bg-label-danger rounded-pill">Private</span>
                        </div>
                        <!-- Email View : Action  bar-->
                        <div class="d-flex align-items-center">
                          <i class="ti ti-printer mt-1 cursor-pointer d-sm-block d-none"></i>
                          <div class="dropdown ms-3">
                            <button class="btn p-0" type="button" id="dropdownMoreOptions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMoreOptions">
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-mail ti-xs me-1"></i>
                                <span class="align-middle">Mark as unread</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-mail-opened ti-xs me-1"></i>
                                <span class="align-middle">Mark as unread</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-star ti-xs me-1"></i>
                                <span class="align-middle">Add star</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-calendar ti-xs me-1"></i>
                                <span class="align-middle">Create Event</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-volume-off ti-xs me-1"></i>
                                <span class="align-middle">Mute</span>
                              </a>
                              <a class="dropdown-item d-sm-none d-block" href="javascript:void(0)">
                                <i class="ti ti-printer ti-xs me-1"></i>
                                <span class="align-middle">Print</span>
                              </a>
                            </div>
                          </div>
                        </div>

                      </div>
                      <hr class="app-email-view-hr mx-n3 mb-2" />

                      <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                          <i class="ti ti-trash cursor-pointer me-3" data-bs-toggle="sidebar" data-target="#app-email-view"></i>
                          <i class="ti ti-mail-opened cursor-pointer me-3"></i>

                          <div class="dropdown me-3">
                            <button class="btn p-0" type="button" id="dropdownMenuFolderTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="ti ti-folder"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuFolderTwo">
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-info-circle ti-xs me-1"></i>
                                <span class="align-middle">Spam</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-pencil ti-xs me-1"></i>
                                <span class="align-middle">Draft</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="ti ti-trash ti-xs me-1"></i>
                                <span class="align-middle">Trash</span>
                              </a>
                            </div>
                          </div>

                          <div class="dropdown me-3">
                            <button class="btn p-0" type="button" id="dropdownLabelTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="ti ti-tag"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownLabelTwo">
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="badge badge-dot bg-success me-1"></i>
                                <span class="align-middle">Workshop</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="badge badge-dot bg-primary me-1"></i>
                                <span class="align-middle">Company</span>
                              </a>
                              <a class="dropdown-item" href="javascript:void(0)">
                                <i class="badge badge-dot bg-info me-1"></i>
                                <span class="align-middle">Important</span>
                              </a>
                            </div>
                          </div>

                        </div>

                        <div class="d-flex align-items-center flex-wrap justify-content-end">
                          <span class="d-sm-block d-none mx-3 text-muted">1-10 of 653</span>
                          <i class="ti ti-chevron-left scaleX-n1-rtl cursor-pointer text-muted me-2"></i>
                          <i class="ti ti-chevron-right scaleX-n1-rtl cursor-pointer"></i>
                        </div>
                      </div>
                    </div>
                    <hr class="m-0" />

                    <!-- Email View : Content-->
                    <div class="app-email-view-content py-4">
                      <p class="email-earlier-msgs text-center text-muted cursor-pointer mb-5">1 Earlier Message</p>
                      <!-- Email View : Previous mails-->
                      <div class="card email-card-prev mx-sm-4 mx-3">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                          <div class="d-flex align-items-center mb-sm-0 mb-3">
                            <img src="../../assets/img/avatars/2.png" alt="user-avatar" class="flex-shrink-0 rounded-circle me-3" height="40" width="40" />
                            <div class="flex-grow-1 ms-1">
                              <h6 class="m-0">Ross Geller</h6>
                              <small class="text-muted">rossGeller@email.com</small>
                            </div>
                          </div>

                          <div class="d-flex align-items-center">
                            <p class="mb-0 me-3 text-muted">June 20th 2020, 08:30 AM</p>
                            <i class="ti ti-paperclip cursor-pointer me-2"></i>
                            <i class="email-list-item-bookmark ti ti-star ti-xs cursor-pointer me-2"></i>
                            <div class="dropdown me-3 d-flex align-self-center">
                              <button class="btn p-0" type="button" id="dropdownEmailOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownEmailOne">
                                <a class="dropdown-item scroll-to-reply" href="javascript:void(0)">
                                  <i class="ti ti-corner-up-left me-1"></i>
                                  <span class="align-middle">Reply</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="ti ti-corner-up-right me-1"></i>
                                  <span class="align-middle">Forward</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="ti ti-alert-octagon me-1"></i>
                                  <span class="align-middle">Report</span>
                                </a>
                              </div>
                            </div>
                          </div>

                        </div>

                        <div class="card-body">
                          <p class="fw-bold">Greetings!</p>
                          <p> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here',making it look like readable English.</p>
                          <p> There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
                          <p class="mb-0">Sincerely yours,</p>
                          <p class="fw-bold mb-0">Envato Design Team</p>
                          <hr />
                          <p class="email-attachment-title mb-2">Attachments</p>
                          <div class="cursor-pointer">
                            <i class="ti ti-file"></i> <span class="align-middle ms-1">report.xlsx</span>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Email View : Last mail-->
                      <div class="card email-card-last mx-sm-4 mx-3 mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                          <div class="d-flex align-items-center mb-sm-0 mb-3">
                            <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="flex-shrink-0 rounded-circle me-3" height="40" width="40" />
                            <div class="flex-grow-1 ms-1">
                              <h6 class="m-0">Chandler Bing</h6>
                              <small class="text-muted">iAmAhoot@email.com</small>
                            </div>
                          </div>
                          <div class="d-flex align-items-center">
                            <p class="mb-0 me-3 text-muted">June 20th 2020, 08:10 AM</p>
                            <i class="ti ti-paperclip cursor-pointer me-2"></i>
                            <i class="email-list-item-bookmark ti ti-star ti-xs cursor-pointer me-2"></i>
                            <div class="dropdown me-3 d-flex align-self-center">
                              <button class="btn p-0" type="button" id="dropdownEmailTwo" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownEmailTwo">
                                <a class="dropdown-item scroll-to-reply" href="javascript:void(0)">
                                  <i class="ti ti-corner-up-left me-1"></i>
                                  <span class="align-middle">Reply</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="ti ti-corner-up-right me-1"></i>
                                  <span class="align-middle">Forward</span>
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                  <i class="ti ti-alert-octagon me-1"></i>
                                  <span class="align-middle">Report</span>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <p class="fw-bold">Greetings!</p>
                          <p> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here',making it look like readable English.</p>
                          <p> There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
                          <p class="mb-0">Sincerely yours,</p>
                          <p class="fw-bold mb-0">Envato Design Team</p>
                          <hr />
                          <p class="email-attachment-title mb-2">Attachments</p>
                          <div class="cursor-pointer">
                            <i class="ti ti-file"></i>
                            <span class="align-middle ms-1">report.xlsx</span>
                          </div>
                        </div>
                      </div>
                      <!-- Email View : Reply mail-->
                      <div class="email-reply card mt-4 mx-sm-4 mx-3">
                        <h6 class="card-header border-0">Reply to Ross Geller</h6>
                        <div class="card-body pt-0 px-3">
                          <div class="d-flex justify-content-start">
                            <div class="email-reply-toolbar border-0 w-100 ps-0">
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
                          <div class="email-reply-editor"></div>
                          <div class="d-flex justify-content-end align-items-center">
                            <div class="me-3">
                              <label class="cursor-pointer" for="attach-file-1"><i class="ti ti-paperclip me-2"></i><span class="align-middle">Attachments</span></label>
                              <input type="file" name="file-input" class="d-none" id="attach-file-1" />
                            </div>
                            <button class="btn btn-primary">
                              <i class="ti ti-send ti-xs me-1"></i>
                              <span class="align-middle">Send</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
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
@stop


@push('js')
<script src="{{ asset('assets/js/app-email.js') }}"></script>
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

    });

    function getMails(folder = 'inbox') {
      $.ajax({
          url: '/mail-boxes/folder/' + folder,
          type: 'GET',
          success: function(response) {
              let emailList = '';

              if (response.status && response.data.length > 0) {
                  response.data.forEach(function(mail) {
                      emailList += `
                      <li class="email-list-item" data-starred="${mail.is_starred == 1 ? 'true' : 'false'}" data-bs-toggle="sidebar" data-target="#app-email-view">
                          <div class="d-flex align-items-center">
                              <div class="form-check mb-0">
                                  <input class="email-list-item-input form-check-input" type="checkbox" id="email-${mail.id}" />
                                  <label class="form-check-label" for="email-${mail.id}"></label>
                              </div>
                              <i class="email-list-item-bookmark ti ti-star ti-xs d-sm-inline-block d-none cursor-pointer ms-2 me-3 ${mail.is_starred ? 'text-warning' : ''}"></i>
                              <img src="../../assets/img/avatars/1.png" alt="user-avatar" class="d-block flex-shrink-0 rounded-circle me-sm-3 me-2" height="32" width="32" />
                              <div class="email-list-item-content ms-2 ms-sm-0 me-2">
                                  <span class="h6 email-list-item-username me-2">${mail.from_user ? mail.from_user.full_name : 'Unknown Sender'}</span>
                                  <span class="email-list-item-subject d-xl-inline-block d-block">${mail.subject.substring(0, 60)}...</span>
                              </div>
                              <div class="email-list-item-meta ms-auto d-flex align-items-center">
                                  <span class="email-list-item-label badge badge-dot bg-danger d-none d-md-inline-block me-2" data-label="private"></span>
                                  <small class="email-list-item-time text-muted">${new Date(mail.created_at).toLocaleTimeString()}</small>
                                  <ul class="list-inline email-list-item-actions text-nowrap">
                                      <li class="list-inline-item email-read"><i class="ti ti-mail-opened"></i></li>
                                      <li class="list-inline-item email-delete"><i class="ti ti-trash"></i></li>
                                      <li class="list-inline-item"><i class="ti ti-archive"></i></li>
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
                quill.root.innerHTML = '';
                $('#emailComposeSidebar').modal('hide');

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
</script>
@endpush