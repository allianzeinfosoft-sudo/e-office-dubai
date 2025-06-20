@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css')}}" />
<style>
     
.w-35 {
    width: 35% !important;
}
.w-45 {
    width: 45% !important;
}
.offcanvas-close{
    position: absolute;
    top: 0px;
    left: -32px;  /* Moves the button outside the offcanvas */
    z-index: 1055; /* Ensures it stays on top */
    padding: 28px 10px;
    border-radius: 0px;
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
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light">Tools /</span> {{ $meta_title }}</h4>
                    
                    <div class="card app-calendar-wrapper">
                      <div class="row g-0">

                        <!-- Calendar Sidebar -->
                        <div class="col-3 app-calendar-sidebar" id="app-calendar-sidebar">

                          <div class="border-bottom p-4 my-sm-0 mb-3">
                            <div class="d-grid">
                              <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                <i class="ti ti-plus me-1"></i> <span class="align-middle">Add Event</span>
                              </button>
                            </div>
                          </div>

                          <div class="p-3">
                            <!-- inline calendar (flatpicker) -->
                            <div class="inline-calendar"></div>
                          
                            <hr class="container-m-nx mb-4 mt-3" style="margin-left: -1rem !important;" />
                          
                            <!-- Filter -->
                            <div class="mb-3 ms-3">
                              <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                            </div>
                          
                            <div class="form-check mb-2 ms-3">
                              <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked />
                              <label class="form-check-label" for="selectAll">View All</label>
                            </div>
                          
                            <div class="app-calendar-events-filter ms-3">

                            <div class="form-check form-check-success mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-birthdays" data-value="birthdays" checked />
                                <label class="form-check-label" for="select-birthdays">Birthdays</label>
                              </div>
                            
                              <div class="form-check form-check-warning mb-2">
                                 <input class="form-check-input input-filter" type="checkbox" id="select-events" data-value="events" checked />
                                 <label class="form-check-label" for="select-events">Events</label>
                              </div>

                              <div class="form-check form-check-primary mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-holiday" data-value="appreciation" checked />
                                <label class="form-check-label" for="select-holiday">Appreciations</label>
                              </div>

                              <div class="form-check form-check-danger mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-holiday" data-value="holiday" checked />
                                <label class="form-check-label" for="select-holiday">Holiday</label>
                              </div>
                              
                              <div class="form-check form-check-info mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-personal" data-value="personal" checked />
                                <label class="form-check-label" for="select-personal">Personal</label>
                              </div>
                            
                              <div class="form-check form-check-primary mb-2">
                                 <input class="form-check-input input-filter" type="checkbox" id="select-business" data-value="business" checked />
                                 <label class="form-check-label" for="select-business">Business</label>
                              </div>

                              <div class="form-check form-check-warning mb-2">
                                <input class="form-check-input input-filter" type="checkbox" id="select-family" data-value="family" checked />
                                <label class="form-check-label" for="select-family">Family</label>
                              </div>

                              <div class="form-check form-check-info">
                                <input class="form-check-input input-filter" type="checkbox" id="select-etc"  data-value="etc" checked />
                                <label class="form-check-label" for="select-etc">ETC</label>
                              </div>
                              <!-- Default -->
                               
                            </div>
                          </div>
                        </div>
                      <!-- /Calendar Sidebar -->

                      <!-- Calendar & Modal -->
                      <div class="col app-calendar-content">
                        <div class="card shadow-none border-0">
                          <div class="card-body pb-0">
                            <!-- FullCalendar -->
                            <div id="calendar"></div>
                          </div>
                        </div>
                        <div class="app-overlay"></div>

                        <!-- FullCalendar Offcanvas -->
                        <div class="offcanvas offcanvas-end event-sidebar w-35" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">

                          <div class="offcanvas-header bg-primary p-3">
                            <span class="d-flex justify-content-between align-items-center gap-2">
                              <i class="ti ti-file-plus fs-2 text-white"></i>
                              <span id="offcanvas-title-container">
                                  <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Event</h5>
                                  <span class="text-white slogan">Create New Quick Note</span>
                              </span>
                            </span>
                            <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
                          </div>
                            

                          <div class="offcanvas-body pt-0">

                            <form method="POST" class="event-form pt-0" id="eventFormNew" onsubmit="return false">
                              <div class="mb-3 pt-2">
                                <label class="form-label" for="eventTitle">Title</label>
                                <input type="text" class="form-control" id="eventTitle" name="eventTitle" placeholder="Event Title" />
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="eventLabel">Label</label>
                                <select class="select2 select-event-label form-select" id="eventLabel" name="eventLabel">
                                  <option data-label="success" value="birthdays" selected>Birthdays</option>
                                  <option data-label="warning" value="events" selected>Events</option>
                                  <option data-label="primary" value="appreciations" selected>Appreciations</option>
                                  <option data-label="danger" value="Holiday">Holiday</option>
                                  <option data-label="info" value="Personal">Personal</option>
                                  <option data-label="primary" value="Business" selected>Business</option>
                                  <option data-label="warning" value="Family">Family</option>
                                  <option data-label="info" value="ETC">ETC</option>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="eventStartDate">Start Date</label>
                                <input type="text" class="form-control" id="eventStartDate" name="eventStartDate" placeholder="Start Date" />
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="eventEndDate">End Date</label>
                                <input type="text" class="form-control" id="eventEndDate" name="eventEndDate" placeholder="End Date" />
                              </div>
                              
                              <div class="mb-3">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input allDay-switch" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                  </span>
                                  <span class="switch-label">All Day</span>
                                </label>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="eventURL">Event URL</label>
                                <input type="url" class="form-control" id="eventURL" name="eventURL" placeholder="https://www.google.com" />
                              </div>

                              <div class="mb-3 select2-primary">
                                <label class="form-label" for="eventGuests">Add Guests</label>
                                <select class="select2 select-event-guests form-select" id="eventGuests" name="eventGuests" multiple>
                                  @if ($employees->isNotEmpty())
                                    @foreach($employees as $employee)
                                      <option value="{{ $employee->full_name }}">{{ $employee->full_name }}</option>
                                    @endforeach
                                  @endif
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label" for="eventLocation">Location</label>
                                <input type="text" class="form-control" id="eventLocation" name="eventLocation" placeholder="Enter Location" />
                              </div>
                              
                              <div class="mb-3">
                                <label class="form-label" for="eventDescription">Description</label>
                                <textarea class="form-control" name="eventDescription" id="eventDescription"></textarea>
                              </div>

                              <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4">
                                <div>
                                  <input type="hidden" name="event_id" id="event_id">
                                  <button type="submit" class="btn btn-primary btn-add-event me-sm-3 me-1">Add</button>
                                  <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas"> Cancel</button>
                                </div>

                                <div>
                                  <button class="btn btn-label-danger btn-delete-event d-none">Delete</button>
                                </div>
                              </div>

                            </form>
                          </div>

                        </div>
                      </div>
                      <!-- /Calendar & Modal -->
                    </div>
                  </div>

                </div>
                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->
            </div>
        </div>
    </div>
</div>


@stop


@push('js')
  <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>
  <script>
    let date = new Date();
    let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    // prettier-ignore
    let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
    // prettier-ignore
    let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);
    let events = [];
    // Merge Laravel-generated birthday events
    let birthdayEvents = @json($events_birthdays);
    let officeEvents = @json($office_events);
    let appreciationEvents = @json($appr_events);
    let events_posted = @json($events_posted);
    let holidays = @json($holiday_events);
    // Add to main events list
    events.push(...birthdayEvents);
    events.push(...officeEvents);
    events.push(...appreciationEvents);
    events.push(...events_posted);
    events.push(...holidays);

    $(function (){
      
      $('#eventFormNew').on('submit', function () {
          let formData = {
              eventTitle: $('#eventTitle').val(),
              eventLabel: $('#eventLabel').val(),
              eventStartDate: $('#eventStartDate').val(),
              eventEndDate: $('#eventEndDate').val(),
              allDay: $('.allDay-switch').is(':checked') ? 1 : 0,
              eventURL: $('#eventURL').val(),
              eventGuests: $('#eventGuests').val(),
              eventLocation: $('#eventLocation').val(),
              eventDescription: $('#eventDescription').val(),
              _token: '{{ csrf_token() }}'
          };
  
          $.ajax({
              url: "{{ route('tools.event-calendar.store') }}",
              method: "POST",
              data: formData,
              success: function (response) {
                  if (response.success) {
                      alert(response.message);
                      $('#eventFormNew')[0].reset();
                      $('#eventGuests').val("").trigger('change');
                        window.location.reload();
                      // refresh calendar or data
                  }
              }
          });
      });

      $('.btn-delete-event').on('click', function () {
          const eventId = $('#event_id').val();
          const calendarType = $('#eventLabel').val(); // e.g., "appreciation", "Holiday", etc.

          if (!eventId || !calendarType) {
              alert('Cannot delete. Missing event ID or type.');
              return;
          }

          if (confirm('Are you sure you want to delete this event?')) {
              $.ajax({
                  url: `/tools/event-calendar/delete/${calendarType}/${eventId}`, // or use route() if passing via blade
                  method: 'DELETE',
                  data: {
                      _token: $('meta[name="csrf-token"]').attr('content'),
                  },
                  success: function (response) {
                      alert('Event deleted successfully!');
                      // Optionally refresh your calendar here
                      $('#eventFormNew')[0].reset();
                      $('.btn-delete-event').addClass('d-none');
                      $('#eventFormNew').removeData('event-id');
                      $('#calendar').fullCalendar('refetchEvents'); // or however you're loading events
                      window.location.reload();
                  },
                  error: function (xhr) {
                      alert('Failed to delete event.');
                  }
              });
          }
      });

    });

  </script>

  <script src="{{ asset('assets/js/app-calendar.js') }} "></script>
@endpush
