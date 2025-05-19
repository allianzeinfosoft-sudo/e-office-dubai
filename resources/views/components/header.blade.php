<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)"> <i class="ti ti-menu-2 ti-sm"></i> </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item navbar-search-wrapper mb-0">
            <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);"> <i class="ti ti-search ti-md me-2"></i> <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span> </a>
          </div>
        </div>
        <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- Digital Clock -->
        <li class="nav-item me-1 me-xl-0">
          <a class="nav-link dropdown-toggle hide-arrow fs-4" href="javascript:void(0);" style="width: 150px">
            <span id="clock" class="text-primary d-flex align-items-center gap-2">
              <i class="ti ti-clock fis rounded-circle fs-4"></i> 00:00:00 </span>
            </a>
        </li>
        <!-- / Digital Clock -->

        <!-- Language -->
        <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"><i class="fi fi-us fis rounded-circle me-1 fs-3"></i></a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="javascript:void(0);" data-language="en"><i class="fi fi-us fis rounded-circle me-1 fs-3"></i><span class="align-middle">English</span></a></li>
          </ul>
        </li>
              <!--/ Language -->

              <!-- Style Switcher -->
              <li class="nav-item me-2 me-xl-0">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                  <i class="ti ti-md"></i>
                </a>
              </li>
              <!--/ Style Switcher -->

              <!-- Quick links  -->
              <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a
                  class="nav-link dropdown-toggle hide-arrow"
                  href="javascript:void(0);"
                  data-bs-toggle="dropdown"
                  data-bs-auto-close="outside"
                  aria-expanded="false">
                  <i class="ti ti-layout-grid-add ti-md"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0">
                  <div class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                      <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                      <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Add shortcuts"><i class="ti ti-sm ti-apps"></i></a>
                    </div>
                  </div>
                  <div class="dropdown-shortcuts-list scrollable-container">
                    <div class="row row-bordered overflow-visible g-0">
                      
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-clock fs-4"></i>
                        </span>
                        <a href="{{ route('custom-attendance.index') }}" class="stretched-link">Approvel</a>
                        <small class="text-muted mb-0">Custom Attendance</small>
                      </div>

                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-unlink fs-4"></i>
                        </span>
                        <a href="{{ route('attendance.incomplete-working-hours') }}" class="stretched-link">Incomplete</a>
                        <small class="text-muted mb-0">Working Hours</small>
                      </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">

                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-users fs-4"></i>
                        </span>
                        <a href="{{ route('recruitments.rrf-approvals') }}" class="stretched-link">RRF Appoval</a>
                        <small class="text-muted mb-0">Recuritment Approvel</small>
                      </div>

                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-lock fs-4"></i>
                        </span>
                        <a href="app-access-roles.html" class="stretched-link">Role Management</a>
                        <small class="text-muted mb-0">Permission</small>
                      </div>

                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-chart-bar fs-4"></i>
                        </span>
                        <a href="index.html" class="stretched-link">Dashboard</a>
                        <small class="text-muted mb-0">User Profile</small>
                      </div>
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-settings fs-4"></i>
                        </span>
                        <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
                        <small class="text-muted mb-0">Account Settings</small>
                      </div>
                    </div>
                    <div class="row row-bordered overflow-visible g-0">
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-help fs-4"></i>
                        </span>
                        <a href="pages-help-center-landing.html" class="stretched-link">Help Center</a>
                        <small class="text-muted mb-0">FAQs & Articles</small>
                      </div>
                      <div class="dropdown-shortcuts-item col">
                        <span class="dropdown-shortcuts-icon rounded-circle mb-2">
                          <i class="ti ti-square fs-4"></i>
                        </span>
                        <a href="modal-examples.html" class="stretched-link">Modals</a>
                        <small class="text-muted mb-0">Useful Popups</small>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <!-- Quick links -->

              <!-- Notification -->
              <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                <a
                  class="nav-link dropdown-toggle hide-arrow"
                  href="javascript:void(0);"
                  data-bs-toggle="dropdown"
                  data-bs-auto-close="outside"
                  aria-expanded="false">
                  <i class="ti ti-bell ti-md"></i>

                  <span class="badge bg-danger rounded-pill badge-notifications" id="notif-count">
                        0
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0">
                  {{-- <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                      <h5 class="text-body mb-0 me-auto">Notification</h5>
                      <a
                        href="javascript:void(0)"
                        class="dropdown-notifications-all text-body"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Mark all as read"
                        ><i class="ti ti-mail-opened fs-4"></i
                      ></a>
                    </div>
                  </li> --}}
                  <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush" id="notification-dropdown">


                        {{-- @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                    <img src="../../assets/img/avatars/1.png" alt class="h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                                        <a href="#" class="dropdown-item mark-as-read" data-id="{{ $notification->id }}">
                                            {{ $notification->data['message'] }}
                                        </a>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                    <a href="javascript:void(0)" class="dropdown-notifications-read"
                                    ><span class="badge badge-dot"></span
                                    ></a>
                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                    ><span class="ti ti-x"></span
                                    ></a>
                                </div>
                                </div>
                            </li>
                      @endforeach --}}

                      {{-- <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">Charles Franklin</h6>
                            <p class="mb-0">Accepted your connection</p>
                            <small class="text-muted">12hr ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>

                      <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <img src="../../assets/img/avatars/2.png" alt class="h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">New Message ✉️</h6>
                            <p class="mb-0">You have new message from Natalie</p>
                            <small class="text-muted">1h ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <span class="avatar-initial rounded-circle bg-label-success"
                                ><i class="ti ti-shopping-cart"></i
                              ></span>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">Whoo! You have new order 🛒</h6>
                            <p class="mb-0">ACME Inc. made new order $1,154</p>
                            <small class="text-muted">1 day ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <img src="../../assets/img/avatars/9.png" alt class="h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">Application has been approved 🚀</h6>
                            <p class="mb-0">Your ABC project application has been approved.</p>
                            <small class="text-muted">2 days ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <span class="avatar-initial rounded-circle bg-label-success"
                                ><i class="ti ti-chart-pie"></i
                              ></span>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">Monthly report is generated</h6>
                            <p class="mb-0">July monthly financial report is generated</p>
                            <small class="text-muted">3 days ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <img src="../../assets/img/avatars/5.png" alt class="h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">Send connection request</h6>
                            <p class="mb-0">Peter sent you connection request</p>
                            <small class="text-muted">4 days ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item list-group-item-action dropdown-notifications-item">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <img src="../../assets/img/avatars/6.png" alt class="h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">New message from Jane</h6>
                            <p class="mb-0">Your have new message from Jane</p>
                            <small class="text-muted">5 days ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                              <span class="avatar-initial rounded-circle bg-label-warning"
                                ><i class="ti ti-alert-triangle"></i
                              ></span>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-1">CPU is running high</h6>
                            <p class="mb-0">CPU Utilization Percent is currently at 88.63%,</p>
                            <small class="text-muted">5 days ago</small>
                          </div>
                          <div class="flex-shrink-0 dropdown-notifications-actions">
                            <a href="javascript:void(0)" class="dropdown-notifications-read"
                              ><span class="badge badge-dot"></span
                            ></a>
                            <a href="javascript:void(0)" class="dropdown-notifications-archive"
                              ><span class="ti ti-x"></span
                            ></a>
                          </div>
                        </div>
                      </li> --}}
                    </ul>
                  </li>
                  {{-- <li class="dropdown-menu-footer border-top">
                    <a
                      href="javascript:void(0);"
                      class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                      View all notifications
                    </a>
                  </li> --}}
                </ul>
              </li>
              <!--/ Notification -->

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ optional(Auth::user()->employee)->profile_image ? asset('storage/' . Auth::user()->employee->profile_image) : asset('assets/img/avatars/1.png') }}" alt class="h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="pages-account-settings-account.html">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                          <img src="{{ optional(Auth::user()->employee)->profile_image ? asset('storage/' . Auth::user()->employee->profile_image) : asset('assets/img/avatars/1.png') }}"
                          alt="Profile Image"
                          class="h-auto rounded-circle" />
                          </div>
                        </div>

                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">{{ ucfirst(Auth::user()->employee->full_name ?? Auth::user()->username) }}</span>
                          <small class="text-muted">{{ ucfirst(Auth::user()->employee->role ?? Auth::user()->role) }} </small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li><a class="dropdown-item" href="{{ route('user.profile', Auth::user()->id); }}"><i class="ti ti-user-check me-2 ti-sm"></i><span class="align-middle">My Profile </span></a></li>
                  <li><a class="dropdown-item" href="{{ route('users.edit', Auth::user()->id); }}"> <i class="ti ti-edit me-2 ti-sm"></i> <span class="align-middle">Edit Profile</span></a></li>

                  <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#changePasswordModal" > <i class="ti ti-lock me-2 ti-sm"></i> <span class="align-middle">Change Password</span></a></li>

                  <li>
                    <div class="dropdown-divider"></div>
                  </li>

                  <li><a class="dropdown-item" href="{{ route('user.lock_profile', Auth::user()->id); }}">
                    <i class="ti ti-user-check me-2 ti-sm"></i><span class="align-middle">Lock Profile </span></a>
                </li>

                  <li>
                    <a class="dropdown-item"  href="{{ route('logout') }}" onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();" target="_blank">
                      <i class="ti ti-logout me-2 ti-sm"></i>
                      <span class="align-middle"> {{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>

          <!-- Search Small Screens -->
          <div class="navbar-search-wrapper search-input-wrapper d-none">
            <input
              type="text"
              class="form-control search-input container-xxl border-0"
              placeholder="Search..."
              aria-label="Search..." />
            <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
          </div>
        </nav>





        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
              <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  <div class="text-center mb-4">
                    <h4 class="mb-2">Change Password</h4>

                    <div class="avatar avatar-online mx-auto d-flex">
                        <img  src="{{ optional(Auth::user()->employee)->profile_image ? asset('storage/' . Auth::user()->employee->profile_image) : asset('assets/img/avatars/1.png') }}"
                             alt
                             class=" rounded-circle" />
                    </div>

                    <h4 class="text-muted">{{ Auth::user()->employee->full_name ?? ''  }}</h4>
                    <p class="text-muted">{{ Auth::user()->employee->designation->designation ?? '' }}</p>
                  </div>
                  <form id="changepassword-form" action="{{ route('change_password') }}" method="post" class="row g-3" >
                    @csrf
                    <div class="col-12">
                      <label class="form-label w-100" for="old_password">Old Password</label>
                      <div class="input-group input-group-merge">
                        <input id="old_password" name="old_password" class="form-control" type="password" placeholder="Current Password"
                          aria-describedby="old_password" />
                      </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label w-100" for="modalAddCard">New Password</label>
                        <div class="input-group input-group-merge">
                          <input id="new_password" name="new_password" class="form-control" type="password" placeholder="New Password" aria-describedby="new_password" />
                        </div>
                      </div>


                      <div class="col-12">
                        <label class="form-label w-100" for="confirm_password">Retype New Password</label>
                        <div class="input-group input-group-merge">
                          <input id="confirm_password" name="confirm_password" class="form-control" type="password" placeholder="Confirm Password"
                            aria-describedby="confirm_password" />
                        </div>
                      </div>



                    <div class="col-12 text-center">
                      <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                      <button  type="reset" class="btn btn-label-secondary btn-reset"  data-bs-dismiss="modal" aria-label="Close">
                        Cancel
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>




        @push('js')
        <script>

         $(function () {
            // Using jQuery example
            $(document).ready(function() {
                $('.mark-as-read').click(function(e) {
                    e.preventDefault();
                    var notificationId = $(this).data('id');

                    $.ajax({
                        url: '/notifications/mark-as-read',
                        type: 'POST',
                        data: {
                            id: notificationId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            // Update UI
                            $(this).removeClass('unread');
                            // Update badge count
                            var count = parseInt($('.badge').text());
                            $('.badge').text(count - 1);
                            if (count - 1 === 0) {
                                $('.badge').hide();
                            }
                        }
                    });
                });
            });

         });

// change pasword validation

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('changepassword-form');

    form.addEventListener('submit', async function (e) {
        e.preventDefault(); // Always prevent default first

        // Get values
        const oldpassword = document.getElementById('old_password').value.trim();
        const newpassword = document.getElementById('new_password').value.trim();
        const confirm_password = document.getElementById('confirm_password').value.trim();

        let errors = [];

        // === Validation ===
        if (!oldpassword) {
            errors.push("Old password is required.");
        }

        if (!newpassword) {
            errors.push("New password is required.");
        }

        if (!confirm_password) {
            errors.push("Confirm password is required.");
        }

        if (newpassword !== confirm_password) {
            errors.push("Confirm password is not matching.");
        }

        // === Show errors or proceed ===
        let errorBox = document.getElementById('formErrors');
        if (!errorBox) {
            errorBox = document.createElement('div');
            errorBox.id = 'formErrors';
            errorBox.className = 'alert alert-danger mt-3';
            form.prepend(errorBox);
        }

        if (errors.length > 0) {
            errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            return; // Stop execution if there are validation errors
        }

        try {
            // === Check old password with server ===
            const response = await fetch('{{ route('check_old_password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ old_password: oldpassword })
            });

            const result = await response.json();

            if (!result.success) {
                errorBox.innerHTML = '<ul class="mb-0"><li>Old password is incorrect.</li></ul>';
                return; // Stop execution if old password doesn't match
            }

            // No errors, submit the form
            errorBox.innerHTML = ''; // Clear errors
            form.submit();
        } catch (error) {
            console.error('Error checking old password:', error);
            errorBox.innerHTML = '<ul class="mb-0"><li>Something went wrong. Please try again.</li></ul>';
        }
    });
});





    </script>
@endpush
