@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form class="add-new-department pt-0 row g-2" method="post" action="{{ route('create.designation') }}" id="department-form" onsubmit="return false">
    @csrf

    <input type="hidden" name="id" id="target_id">
    <div class="col-sm-12">
      <label class="form-label" for="basicBranchname3">Branch Name

        <button type="button" class="btn btn-icon btn-xs btn-outline-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addNewBranchModal">
            <span class="ti ti-plus " style="font-size: 12px;"></span>
        </button>


      </label>


        {{-- <span id="basicBranchname2" class="input-group-text"><i class="ti ti-user"></i></span> --}}
        <select id="basicBranchname3" class="select2 form-select  dt-branch-name2" name="branch_id">
          <option value="">Select</option>
          @foreach ($branches as $branch)
            <option value="{{ $branch->id }}">{{ $branch->branch ?? '-'}}</option>
          @endforeach
        </select>
    </div>
    <div class="col-sm-12">
      <label class="form-label" for="department">Department
        <button type="button" class="btn btn-icon btn-xs btn-outline-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addNewDepartmentModal">
            <span class="ti ti-plus text-xs" style="font-size: 12px;"></span>
          </button>
      </label>
        {{-- <span class="input-group-text"><i class="ti ti-department"></i></span> --}}
        <select id="department" class="select2 form-select  dt-department-name1" name="department_id">
          <option value="">Select</option>
        </select>

    </div>
    <div class="col-sm-12">
      <label class="form-label" for="designation">Designation</label>
      <div class="input-group input-group-merge">
        {{-- <span class="input-group-text"><i class="ti ti-department"></i></span> --}}
        <input type="text" id="designation" name="designation" class="form-control dt-designation-name1"/>
      </div>
    </div>
    <div class="col-sm-12">
      <button type="submit" class="btn btn-primary data-submit me-sm-3 me-1">Submit</button>
      <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
    </div>
  </form>





  <!-- Add New Branch Modal -->
  <div class="modal fade" id="addNewBranchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Add New Branch</h3>
          </div>
          <form id="add-branch-form" action="{{ route('branchs.store') }}" method="POST" class="row g-3" >
            @csrf
            <div class="col-12">
              <label class="form-label w-100" for="modalAddCard">Branch Name</label>
              <div class="input-group input-group-merge">
                <input id="branch_name" name="branch_name" class="form-control" type="text" placeholder="Enter Branch Name" aria-describedby="branch_name" />
                <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"><span class="card-type"></span></span>
              </div>
            </div>

            <div class="col-12">
                <label class="form-label w-100" for="modalAddCard">Branch Location</label>
                <div class="input-group input-group-merge">
                  <input id="branch_location" name="branch_location" class="form-control" type="text" placeholder="Enter Branch Location" aria-describedby="branch_location" />
                  <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"><span class="card-type"></span></span>
                </div>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                Cancel
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>



  <!-- Add New Department Modal -->
  <div class="modal fade" id="addNewDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2">Add New Department</h3>
          </div>
          <form id="add-branch-form" action="{{ route('departments.store') }}" method="POST" class="row g-3" >
            @csrf
            <div class="col-12">
              <label class="form-label w-100" for="modalAddCard">Branch Name</label>

                <select id="branch_select" class="select2 form-select  dt-branch-name2" name="branch_select">
                    <option value="">Select</option>
                    @foreach ($branches as $branch)
                      <option value="{{ $branch->id }}">{{ $branch->branch ?? '-'}}</option>
                    @endforeach
                  </select>
            </div>

            <div class="col-12">
                <label class="form-label w-100" for="modalAddCard">Department Name</label>
                <div class="input-group input-group-merge">
                  <input id="department_name" name="department_name" class="form-control" type="text" placeholder="Enter Department" aria-describedby="department_name" />
                  <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"><span class="card-type"></span></span>
                </div>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
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

$(document).ready(function() {
      // Onchange event for the branch select box
      $('#basicBranchname3').on('change', function() {
          var branchId = $(this).val(); // Get the selected branch ID

          if (branchId) {
              // AJAX request to fetch departments
              $.ajax({
                url: `/branches/${branchId}/departments`,
                  type: 'GET',
                  success: function(response) {
                      // Clear the department select box
                      $('#department').empty().append('<option value="">Select</option>');

                      // Populate the department select box with the fetched data
                      if (response.length > 0) {
                          $.each(response, function(index, department) {
                              $('#department').append('<option value="' + department.id + '">' + department.department + '</option>');
                          });
                      } else {
                          $('#department').append('<option value="">No departments found</option>');
                      }
                  },
                  error: function(xhr) {
                      console.error('Error fetching departments:', xhr.responseText);
                  }
              });
          } else {
              // If no branch is selected, clear the department select box
              $('#department').empty().append('<option value="">Select</option>');
          }
      });
  });

</script>
@endpush
