<div class="card mb-4">
    <div class="card-body">

      <div class="user-avatar-section">
        <div class="d-flex align-items-center flex-column">
            <img
                class="img-fluid rounded mb-3 pt-1 mt-4"
                src="{{ asset('storage/' . $user->employee->profile_image ?? '' ) }}"
                height="100"
                width="100"
                alt="User avatar"
            />
          <div class="user-info text-center">
            <h4 class="mb-2">{{ $user->employee->full_name ?? 'N/A'}}</h4>
            <span class="badge bg-label-secondary mt-1"> {{ $user->employee->designation->designation ?? 'N/A' }}</span>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
        <div class="d-flex align-items-start me-4 mt-3 gap-2">
          <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-checkbox ti-sm"></i></span>
          <div>
            <p class="mb-0 fw-semibold">1.23k</p>
            <small>Tasks Done</small>
          </div>
        </div>
        <div class="d-flex align-items-start mt-3 gap-2">
          <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-briefcase ti-sm"></i></span>
          <div>
            <p class="mb-0 fw-semibold">568</p>
            <small>Projects Done</small>
          </div>
        </div>
      </div>

      <small class="card-text text-uppercase">About</small>
      <ul class="list-unstyled mb-4 mt-3">
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-user"></i><span class="fw-bold mx-2">UserName:</span> <span>{{ $user->username ?? 'N/A'}}</span>
        </li>
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-check"></i><span class="fw-bold mx-2">Status:</span>
          @if ($user->employee->status == 1)
          <span class="badge bg-label-success">New User </span>
          @elseif ($user->employee->status == 2)
              <span class="badge bg-label-success">Active</span>
          @elseif ($user->employee->status == 3)
              <span class="badge bg-label-danger">Inactive</span>
          @elseif ($user->employee->status == 4)
              <span class="badge bg-label-danger">Resigned</span>
          @elseif ($user->employee->status == 5)
              <span class="badge bg-label-primary">Admin</span>
          @endif

        </li>
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-crown"></i><span class="fw-bold mx-2">Role:</span> <span>{{ $user->role ?? 'N/A' }}</span>
        </li>
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-flag"></i><span class="fw-bold mx-2">Reporting To:</span> <span>{{ $user->employee->reportingToEmployee->full_name ?? 'N/A' }}</span>
        </li>
       {{-- <li class="d-flex align-items-center mb-3">
          <i class="ti ti-file-description"></i><span class="fw-bold mx-2">Languages:</span>
          <span>English</span>
        </li> --}}
      </ul>
      <small class="card-text text-uppercase">Contacts</small>
      <ul class="list-unstyled mb-4 mt-3">
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-phone-call"></i><span class="fw-bold mx-2">Contact:</span>
          <span>{{ $user->employee->phonenumber ?? 'N/A' }}</span>
        </li>
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-mail"></i><span class="fw-bold mx-2">Email:</span>
          <span>{{ $user->email ?? 'N/A' }}</span>
        </li>
          <li class="d-flex align-items-center mb-3">
          <i class="ti ti-brand-skype"></i><span class="fw-bold mx-2">Address: </span>
          <span>{{ $user->employee->address ?? 'N/A' }}</span>
        </li>
      </ul>
      {{-- <small class="card-text text-uppercase">Teams</small>
      <ul class="list-unstyled mb-0 mt-3">
        <li class="d-flex align-items-center mb-3">
          <i class="ti ti-brand-angular text-danger me-2"></i>
          <div class="d-flex flex-wrap">
            <span class="fw-bold me-2">Backend Developer</span><span>(126 Members)</span>
          </div>
        </li>
        <li class="d-flex align-items-center">
          <i class="ti ti-brand-react-native text-info me-2"></i>
          <div class="d-flex flex-wrap">
            <span class="fw-bold me-2">React Developer</span><span>(98 Members)</span>
          </div>
        </li>
      </ul> --}}

      <div class="d-flex justify-content-center">
        <a
          href="javascript:;"
          class="btn btn-primary me-3"
          data-bs-target="#editUser"
          data-bs-toggle="modal"
          >Edit</a
        >
        <a href="javascript:;" class="btn btn-label-danger suspend-user">Suspended</a>
      </div>

    </div>
  </div>
