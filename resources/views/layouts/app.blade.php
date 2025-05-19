<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" lang="en" class="light-style customizer-hide layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template-no-customizer" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"  >
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon_1.ico') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

      <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-semi-dark.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-user-view.css') }}" />
     <!-- Page CSS -->
     <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <style>
         .layout-page{
            background-color: #00000075;
        }
    </style>
    @yield('css')
</head>
<body>
    <div id="app">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <main class="py-4"> --}}
            @yield('content')
        {{-- </main> --}}
    </div>
    <!-- Core JS -->

    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js') }}"></script> --}}

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <!-- <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script> -->

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

   <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
   <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
   <script src="{{ asset('assets/vendor/libs/bloodhound/bloodhound.js') }}"></script>


    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>


    <script src="{{ asset('assets/js/extended-ui-timeline.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/animate-on-scroll/animate-on-scroll.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('assets/js/pages-auth.js') }}"></script>
    {{-- @if(request()->is('home')) --}}
        <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    {{-- @endif --}}
    @if(request()->is('roles'))
        <script src="{{ asset('assets/js/app-access-roles.js') }}"></script>
        <script src="{{ asset('assets/js/modal-add-role.js') }}"></script>
    @endif
    @if(request()->is('permissions'))
        <script src="{{ asset('assets/js/app-access-permission.js') }}"></script>
        <script src="{{ asset('assets/js/modal-add-permission.js') }}"></script>
        <script src="{{ asset('assets/js/modal-edit-permission.js') }}"></script>
    @endif

    @if(Route::is('users.*') || Route::is('user.edit'))
        <script src="{{ asset('assets/js/app-user-list.js') }}"></script>
        <script src="{{ asset('assets/js/app-user-add.js')}}"></script>
        <script src="{{ asset('assets/js/app-user-view.js') }}"></script>
        <script src="{{ asset('assets/js/app-user-view-account.js') }}"></script>
    @endif
    @if(request()->is('branchs'))
        <script src="{{ asset('assets/js/tables-datatables-branch.js') }}"></script>
    @endif

    @if(request()->is('settings/workshift'))
        <script src="{{ asset('assets/js/tables-datatables-workshift.js') }}"></script>
    @endif

    <script src="{{ asset('assets/js/general.js') }}"></script>

    @if (!request()->is('attendance'))
        <script src="{{ asset('assets/js/forms-editors.js') }}"></script>
        <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="{{ asset('assets/js/form-wizard-numbered.js') }}"></script>
        <script src="{{ asset('assets/js/form-wizard-validation.js') }}"></script>
    @endif

    @if(Route::is('leaves.*'))
        {{-- <script src="{{ asset('assets/js/wizard-ex-property-listing.js') }}"></script> --}}
    @endif

    <script src="{{ asset('assets/js/forms-typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/forms-file-upload.js') }}"></script>

    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
    <script src="{{ asset('assets/js/forms-tagify.js') }}"></script>
     <!-- Vendors JS -->

     <script>
    function loadNotifications() {
        fetch("/notifications")
            .then(response => response.json())
            .then(data => {

                console.log(data);

                let notifContainer = document.getElementById("notification-dropdown");
                let notifCount = document.getElementById("notif-count");

                notifCount.textContent = data.count;

                console.log(data.message);
                let html = "";
                data.notifications.forEach(notif => {
                    html += '<li class="list-group-item list-group-item-action dropdown-notifications-item" data-id="'+ notif.id +'">'+ notif.message +'</li>';
                });

                // notifContainer.innerHTML = html;
            });
    }

    // Load initially
    loadNotifications();

    // Refresh every 30 seconds
    setInterval(loadNotifications, 30000);


    // mark read

    document.querySelectorAll('.dropdown-notifications-item').forEach(item => {
    item.addEventListener('click', function () {
        const notificationId = this.getAttribute('data-id');

        fetch(`/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (response.ok) {
                this.classList.add('text-muted'); // Optional visual cue
            }
        });
    });
});



    </script>


    @stack('js')
    @yield('js')

</body>
</html>
