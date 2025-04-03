/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
  let borderColor, bodyBg, headingColor, userView;

  if (isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    select2 = $('.select2'),

    statusObj = {
      1: { title: 'New User', class: 'bg-label-warning' },
      2: { title: 'Active', class: 'bg-label-success' },
      3: { title: 'Inactive', class: 'bg-label-secondary' },
      4: { title: 'Resigned', class: 'bg-label-danger'},
      5: { title: 'Admin', class: 'bg-label-primary'}
    };

  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
      dropdownParent: $this.parent()
    });
  }

  // Users datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      // ajax: assetsPath + 'json/user-list.json', // JSON file to add data

      ajax: {
        url: "/user-list",  // Fetch from Laravel API
        type: "GET",
        dataType: "json",
        dataSrc: "data"
    },

      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'full_name' },
        { data: 'employeeID' },
        { data: 'email' },
        { data: 'phonenumber' },
        { data: 'status' },
        { data: 'role' }
      ],
      columnDefs: [
        {
            targets: 0,
            orderable: false, // Prevent sorting on Sl No column
            searchable: false,
            render: function (data, type, row, meta){
                return meta.row+1;
            }
        },
        {
          // User full name and email
          targets: 1,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            let userView = "/user/profile/"+full['id'];
            var $name = full['full_name'],
              $email = full['email'],
              $image = full['profile_image'];

            if ($image) {

              // For Avatar image
              var $output = '<img src="/storage/' + $image + '" alt="Avatar" class="rounded-circle">';
            } else {
              // For Avatar badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
              var $state = states[stateNum],
                $name = full['full_name'],
                $initials = $name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
              $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
            }
            // Creates full output for row
            var $row_output =
              '<div class="d-flex justify-content-start align-items-center user-name">' +
              '<div class="avatar-wrapper">' +
              '<div class="avatar avatar-sm me-3">' +
              $output +
              '</div>' +
              '</div>' +
              '<div class="d-flex flex-column">' +
              '<a href="' +
              userView +
              '" class="text-body text-truncate"><span class="fw-semibold">' +
              $name +
              '</span></a>' +
              '<small class="text-muted">' +
              $email +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // User Role
          targets: 5,
          render: function (data, type, full, meta) {
            let $role = full['role'];
            return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
          }
        },

        {
          // User Status
          targets: 6,
          render: function (data, type, full, meta) {
            var $status = full['status'];

            return (
              '<span class="badge ' +
              statusObj[$status].class +
              '" text-capitalized>' +
              statusObj[$status].title +
              '</span>'
            );
          }
        },
        {
          // Actions
          targets: 7,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            const user_id =  full['id'];
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="javascript:;" class="text-body edit-user" data-edit-user-id="' + user_id + '"><i class="ti ti-edit ti-sm me-2"></i></a>' +
              '<a href="javascript:;" class="text-body delete-user" data-user-id="' + user_id + '"><i class="ti ti-trash ti-sm mx-2"></i></a>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="' +
              userView +
              '" class="dropdown-item">View</a>' +
              '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[1, 'desc']],


    });
  }

  // Delete Record
  $('.datatables-users tbody').on('click', '.delete-record', function () {
        dt_user.row($(this).parents('tr')).remove().draw();
  });

  window.onload = function () {
    console.log("Window is fully loaded!");
};


   // destroy user
   window.onload = function () {

    document.querySelectorAll(".delete-user").forEach((element) => {
        element.addEventListener("click", function () {
            let userId = this.getAttribute("data-user-id"); // Corrected

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/user-delete/${userId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Deleted!", "User has been deleted.", "success").then(() => {
                                location.reload(); // Reload page after deletion
                            });
                        } else {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire("Error!", "Could not delete user.", "error");
                    });
                }
            });
        });
    });

    document.querySelectorAll(".edit-user").forEach((element) => {
        element.addEventListener("click", function () {
            let userId = this.getAttribute("data-edit-user-id"); // Corrected
            // Redirect to the edit page
            window.location.href = `/users/${userId}/edit`;
        });
    });
}




  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

// Validation & Phone mask
(function () {
  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    addNewUserForm = document.getElementById('addNewUserForm');

  // Phone Number
  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
  // Add New User Form Validation
  const fv = FormValidation.formValidation(addNewUserForm, {
    fields: {
      userFullname: {
        validators: {
          notEmpty: {
            message: 'Please enter full name '
          }
        }
      },
      userEmail: {
        validators: {
          notEmpty: {
            message: 'Please enter your email'
          },
          emailAddress: {
            message: 'The value is not a valid email address'
          }
        }
      },
      userRole: {
        validators: {
          notEmpty: {
            message: 'Please enter full name '
          }
        }
      },
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      // autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });
})();
