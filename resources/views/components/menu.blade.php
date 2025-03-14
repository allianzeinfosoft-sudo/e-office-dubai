<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="index.html" class="app-brand-link">
        <span class="app-brand-logo demo">
          <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
              fill="#7367F0" />
            <path
              opacity="0.06"
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
              fill="#161616" />
            <path
              opacity="0.06"
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
              fill="#161616" />
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
              fill="#7367F0" />
          </svg>
        </span>
        <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 text-uppercase">
      <!-- Dashboards -->
      <li class="menu-item active open text-uppercase">
        <a href="{{ route('home') }}" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-smart-home"></i>
          <div data-i18n="Dashboards">Dashboards</div>
          <div class="badge bg-label-primary rounded-pill ms-auto">3</div>
        </a>
        {{-- <ul class="menu-sub">
          <li class="menu-item active">
            <a href="index.html" class="menu-link">
              <div data-i18n="Analytics">Analytics</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="dashboards-crm.html" class="menu-link">
              <div data-i18n="CRM">CRM</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="dashboards-ecommerce.html" class="menu-link">
              <div data-i18n="eCommerce">eCommerce</div>
            </a>
          </li>
        </ul> --}}
      </li>

      <!-- Layouts -->
      <li class="menu-item">
        {{-- <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
          <div data-i18n="Layouts">Layouts</div>
        </a> --}}

        {{-- <ul class="menu-sub">
          <li class="menu-item">
            <a href="layouts-collapsed-menu.html" class="menu-link">
              <div data-i18n="Collapsed menu">Collapsed menu</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-content-navbar.html" class="menu-link">
              <div data-i18n="Content navbar">Content navbar</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-content-navbar-with-sidebar.html" class="menu-link">
              <div data-i18n="Content nav + Sidebar">Content nav + Sidebar</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="../horizontal-menu-template" class="menu-link" target="_blank">
              <div data-i18n="Horizontal">Horizontal</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-without-menu.html" class="menu-link">
              <div data-i18n="Without menu">Without menu</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-without-navbar.html" class="menu-link">
              <div data-i18n="Without navbar">Without navbar</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-fluid.html" class="menu-link">
              <div data-i18n="Fluid">Fluid</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-container.html" class="menu-link">
              <div data-i18n="Container">Container</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-blank.html" class="menu-link">
              <div data-i18n="Blank">Blank</div>
            </a>
          </li>
        </ul> --}}
      </li>

      <!-- Apps & Pages -->
      {{-- <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Apps &amp; Pages</span>
      </li> --}}
      <li class="menu-item">
        <a href="app-email.html" class="menu-link">
          <i class="menu-icon tf-icons ti ti-mail"></i>
          <div data-i18n="Attendance">Attendance</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
          <div data-i18n="Work">Work</div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="layouts-collapsed-menu.html" class="menu-link">
              <div data-i18n="Work Status">Work Status</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-content-navbar.html" class="menu-link">
              <div data-i18n="SDU Project Status">SDU Project Status</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-content-navbar-with-sidebar.html" class="menu-link">
              <div data-i18n="Temporary Status">Temporary Status</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="../horizontal-menu-template" class="menu-link" target="_blank">
              <div data-i18n="Entry Open Markin">Entry Open Markin</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="app-email.html" class="menu-link">
          <i class="menu-icon tf-icons ti ti-mail"></i>
          <div data-i18n="Gallery">Gallery</div>
        </a>
      </li>

      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
          <div data-i18n="Report">Report</div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item">
            <a href="layouts-collapsed-menu.html" class="menu-link">
              <div data-i18n="My Overview">My Overview</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-content-navbar.html" class="menu-link">
              <div data-i18n="My Attendance">My Attendance</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="layouts-content-navbar-with-sidebar.html" class="menu-link">
              <div data-i18n="My Work Report">My Work Report</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="../horizontal-menu-template" class="menu-link" target="_blank">
              <div data-i18n="My Emergency Report">My Emergency Report</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="../horizontal-menu-template" class="menu-link" target="_blank">
              <div data-i18n="Salary Slip">Salary Slip</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-file-dollar"></i>
          <div data-i18n="Survey">Survey</div>
          {{-- <div class="badge bg-label-danger rounded-pill ms-auto">4</div> --}}
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="app-invoice-list.html" class="menu-link">
              <div data-i18n="View Survey">View Survey</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-file-dollar"></i>
          <div data-i18n="PAR">PAR</div>
          {{-- <div class="badge bg-label-danger rounded-pill ms-auto">4</div> --}}
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="app-invoice-list.html" class="menu-link">
              <div data-i18n="View PAR">View PAR</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-file-dollar"></i>
          <div data-i18n="Leave">Leave</div>
          {{-- <div class="badge bg-label-danger rounded-pill ms-auto">4</div> --}}
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{ route('leaves.create') }}" class="menu-link">
              <div data-i18n="Apply Leave">Apply Leave</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-invoice-preview.html" class="menu-link">
              <div data-i18n="Leave Status">Leave Status</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('leaves.index') }}" class="menu-link">
              <div data-i18n="Leave Summary">Leave Summary</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-users"></i>
          <div data-i18n="Users">Users</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{ route('users.index') }}" class="menu-link">
              <div data-i18n="List">List</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('users.create') }}" class="menu-link">
              <div data-i18n="Add User">Add User</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="View">View</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="app-user-view-account.html" class="menu-link">
                  <div data-i18n="Account">Account</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-user-view-security.html" class="menu-link">
                  <div data-i18n="Security">Security</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-user-view-billing.html" class="menu-link">
                  <div data-i18n="Billing & Plans">Billing & Plans</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-user-view-notifications.html" class="menu-link">
                  <div data-i18n="Notifications">Notifications</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-user-view-connections.html" class="menu-link">
                  <div data-i18n="Connections">Connections</div>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-settings"></i>
          <div data-i18n="Roles & Permissions">Roles & Permissions</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="{{ route('roles.index')  }}" class="menu-link">
              <div data-i18n="Roles">Roles</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('permissions.index') }}" class="menu-link">
              <div data-i18n="Permission">Permission</div>
            </a>
          </li>
        </ul>
      </li>


      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-file"></i>
          <div data-i18n="View">View</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Thought of the Day">Thought of the Day</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Appreciation">Appreciation</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="pages-faq.html" class="menu-link">
              <div data-i18n="Birthdays">Birthdays</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Announcement">Announcement</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="pages-pricing.html" class="menu-link">
              <div data-i18n="Company Policies">Company Policies</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Events">Events</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Holidays">Holidays</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Holidays">User Reminder List</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-lock"></i>
          <div data-i18n="Others">Others</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link">
              <div data-i18n="Seen Status Report">Seen Status Report</div>
            </a>

          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="Thoughts">Thoughts</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="auth-register-basic.html" class="menu-link" target="_blank">
                  <div data-i18n="Add Thought of the day">Add Thought of the day</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-register-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="View Thoughts">View Thoughts</div>
                </a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="Appreciation">Appreciation</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="auth-verify-email-basic.html" class="menu-link" target="_blank">
                  <div data-i18n="Add Appreciation">Add Appreciation</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-verify-email-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="View Appreciation">View Appreciation</div>
                </a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="Policies">Policies</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="auth-reset-password-basic.html" class="menu-link" target="_blank">
                  <div data-i18n="Add Policy">Add Policy</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-reset-password-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="View Policy">View Policy</div>
                </a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="Announcement">Announcement</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="auth-forgot-password-basic.html" class="menu-link" target="_blank">
                  <div data-i18n="Add Announcement">Add Announcement</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-forgot-password-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="Add Banner">Add Banner</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-forgot-password-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="View Announcement">View Announcement</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-forgot-password-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="View Banner">View Banner</div>
                </a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="Events">Events</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="auth-two-steps-basic.html" class="menu-link" target="_blank">
                  <div data-i18n="Add Events">Add Events</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="auth-two-steps-cover.html" class="menu-link" target="_blank">
                  <div data-i18n="View Events">View Events</div>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </li>

      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-forms"></i>
          <div data-i18n="Conference Hall">Conference Hall</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="wizard-ex-checkout.html" class="menu-link">
              <div data-i18n="Booking">Booking</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="wizard-ex-property-listing.html" class="menu-link">
              <div data-i18n="View Bookings">View Bookings</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="wizard-ex-create-deal.html" class="menu-link">
              <div data-i18n="My Booking">My Booking</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="wizard-ex-create-deal.html" class="menu-link">
              <div data-i18n="Assigned Bookings">Assigned Bookings</div>
            </a>
          </li>
        </ul>
      </li>


      <!-- Components -->
      {{-- <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Components</span>
      </li>   --}}
      <!-- Cards -->
     <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-id"></i>
          <div data-i18n="Feedback">Feedback</div>
          <div class="badge bg-label-primary rounded-pill ms-auto">6</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="cards-basic.html" class="menu-link">
              <div data-i18n="Feedback Form">Feedback Form</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="cards-advance.html" class="menu-link">
              <div data-i18n="Feedback Reviews">Feedback Reviews</div>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-item">
        <a href="app-email.html" class="menu-link">
          <i class="menu-icon tf-icons ti ti-mail"></i>
          <div data-i18n="My Projects">My Projects</div>
        </a>
      </li>

      <!-- User interface -->
      <li class="menu-item">
        <a href="javascript:void(0)" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-color-swatch"></i>
          <div data-i18n="My Account">My Account</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="ui-accordion.html" class="menu-link">
              <div data-i18n="My Profile">My Profile</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="ui-alerts.html" class="menu-link">
              <div data-i18n="Change Password">Change Password</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="ui-badges.html" class="menu-link">
              <div data-i18n="Edit Profile">Edit Profile</div>
            </a>
          </li>
        </ul>
      </li>

      <!-- Extended components -->
      <li class="menu-item">
        <a href="javascript:void(0)" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-components"></i>
          <div data-i18n="Tools">Tools</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="extended-ui-avatar.html" class="menu-link">
              <div data-i18n="Quick Notes">Quick Notes</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="extended-ui-blockui.html" class="menu-link">
              <div data-i18n="Event Calendar">Event Calendar</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="extended-ui-drag-and-drop.html" class="menu-link">
              <div data-i18n="KSP">KSP</div>
            </a>
          </li>

        </ul>
      </li>

      <!-- Icons -->
      <li class="menu-item">
        <a href="javascript:void(0)" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-brand-tabler"></i>
          <div data-i18n="Jobs">Jobs</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="icons-tabler.html" class="menu-link">
              <div data-i18n="My Jobs">My Jobs</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="icons-font-awesome.html" class="menu-link">
              <div data-i18n="Assign a Job">Assign a Job</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="icons-font-awesome.html" class="menu-link">
              <div data-i18n="Jobs Assigned By You">Jobs Assigned By You</div>
            </a>
          </li>
        </ul>
      </li>

      <!-- Forms & Tables -->
      {{-- <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Forms &amp; Tables</span>
      </li> --}}
      <!-- Forms -->
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-toggle-left"></i>
          <div data-i18n="Email">Email</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="forms-basic-inputs.html" class="menu-link">
              <div data-i18n="Inbox">Inbox</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="forms-input-groups.html" class="menu-link">
              <div data-i18n="Starred">Starred</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="forms-custom-options.html" class="menu-link">
              <div data-i18n="Sent Email">Sent Email</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="forms-editors.html" class="menu-link">
              <div data-i18n="Trash">Trash</div>
            </a>
          </li>
        </ul>
      </li>

      {{-- <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-layout-navbar"></i>
          <div data-i18n="Form Layouts">Form Layouts</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="form-layouts-vertical.html" class="menu-link">
              <div data-i18n="Vertical Form">Vertical Form</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="form-layouts-horizontal.html" class="menu-link">
              <div data-i18n="Horizontal Form">Horizontal Form</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="form-layouts-sticky.html" class="menu-link">
              <div data-i18n="Sticky Actions">Sticky Actions</div>
            </a>
          </li>
        </ul>
      </li> --}}
      {{-- <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-text-wrap-disabled"></i>
          <div data-i18n="Form Wizard">Form Wizard</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="form-wizard-numbered.html" class="menu-link">
              <div data-i18n="Numbered">Numbered</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="form-wizard-icons.html" class="menu-link">
              <div data-i18n="Icons">Icons</div>
            </a>
          </li>
        </ul>
      </li> --}}
      {{-- <li class="menu-item">
        <a href="form-validation.html" class="menu-link">
          <i class="menu-icon tf-icons ti ti-checkbox"></i>
          <div data-i18n="Form Validation">Form Validation</div>
        </a>
      </li> --}}
      <!-- Tables -->
      {{-- <li class="menu-item">
        <a href="tables-basic.html" class="menu-link">
          <i class="menu-icon tf-icons ti ti-table"></i>
          <div data-i18n="Tables">Tables</div>
        </a>
      </li> --}}

      {{-- <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-layout-grid"></i>
          <div data-i18n="Datatables">Datatables</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="tables-datatables-basic.html" class="menu-link">
              <div data-i18n="Basic">Basic</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="tables-datatables-advanced.html" class="menu-link">
              <div data-i18n="Advanced">Advanced</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="tables-datatables-extensions.html" class="menu-link">
              <div data-i18n="Extensions">Extensions</div>
            </a>
          </li>
        </ul>
      </li> --}}

      <!-- Charts & Maps -->
      {{-- <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Charts &amp; Maps</span>
      </li> --}}
      {{-- <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-chart-pie"></i>
          <div data-i18n="Charts">Charts</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="charts-apex.html" class="menu-link">
              <div data-i18n="Apex Charts">Apex Charts</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="charts-chartjs.html" class="menu-link">
              <div data-i18n="ChartJS">ChartJS</div>
            </a>
          </li>
        </ul>
      </li> --}}
      {{-- <li class="menu-item">
        <a href="maps-leaflet.html" class="menu-link">
          <i class="menu-icon tf-icons ti ti-map"></i>
          <div data-i18n="Leaflet Maps">Leaflet Maps</div>
        </a>
      </li> --}}

      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons ti ti-settings"></i>
          <div data-i18n="Settings">Settings</div>
        </a>
        <ul class="menu-sub">

          <li class="menu-item">
            <a href="{{ route('branchs.index') }}" class="menu-link">
              <div data-i18n="Branch & Department">Branch & Department</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="{{ route('workshift') }}" class="menu-link">
              <div data-i18n="Work Shift">Work Shift</div>
            </a>
          </li>


        </ul>
      </li>
      <li class="menu-item">
        &nbsp;
      </li>
      <!-- Misc -->
    </ul>
  </aside>
  <!-- / Menu -->
