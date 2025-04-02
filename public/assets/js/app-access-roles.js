/**
 * App user list
 */

'use strict';

// Datatable (jquery)
$(function () {
  var dtUserTable = $('.datatables-users'),
    statusObj = {
      1: { title: 'Pending', class: 'bg-label-warning' },
      2: { title: 'Active', class: 'bg-label-success' },
      3: { title: 'Inactive', class: 'bg-label-secondary' }
    };

  var userView = 'app-user-view-account.html';

  // Users List datatable
  if (dtUserTable.length) {
    var dtUser = dtUserTable.DataTable({
      ajax: assetsPath + 'json/user-list.json', // JSON file to add data
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'full_name' },
        { data: 'role' },
        { data: 'current_plan' },
        { data: 'billing' },
        { data: 'status' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // User full name and email
          targets: 1,
          responsivePriority: 4,
          render: function (data, type, full, meta) {
            var $name = full['full_name'],
              $email = full['email'],
              $image = full['avatar'];
            if ($image) {
              // For Avatar image
              var $output =
                '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
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
              '<div class="d-flex justify-content-left align-items-center">' +
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
              '<small class="text-muted">@' +
              $email +
              '</small>' +
              '</div>' +
              '</div>';
            return $row_output;
          }
        },
        {
          // User Role
          targets: 2,
          render: function (data, type, full, meta) {
            var $role = full['role'];
            var roleBadgeObj = {
              Subscriber:
                '<span class="badge badge-center rounded-pill bg-label-warning me-3 w-px-30 h-px-30"><i class="ti ti-user ti-sm"></i></span>',
              Author:
                '<span class="badge badge-center rounded-pill bg-label-success me-3 w-px-30 h-px-30"><i class="ti ti-settings ti-sm"></i></span>',
              Maintainer:
                '<span class="badge badge-center rounded-pill bg-label-primary me-3 w-px-30 h-px-30"><i class="ti ti-chart-pie-2 ti-sm"></i></span>',
              Editor:
                '<span class="badge badge-center rounded-pill bg-label-info me-3 w-px-30 h-px-30"><i class="ti ti-edit ti-sm"></i></span>',
              Admin:
                '<span class="badge badge-center rounded-pill bg-label-secondary me-3 w-px-30 h-px-30"><i class="ti ti-device-laptop ti-sm"></i></span>'
            };
            return "<span class='text-truncate d-flex align-items-center'>" + roleBadgeObj[$role] + $role + '</span>';
          }
        },
        {
          // Plans
          targets: 3,
          render: function (data, type, full, meta) {
            var $plan = full['current_plan'];

            return '<span class="fw-semibold">' + $plan + '</span>';
          }
        },
        {
          // User Status
          targets: 5,
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
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-flex align-items-center">' +
              '<a href="' +
              userView +
              '" class="btn btn-sm btn-icon"><i class="ti ti-eye"></i></a>' +
              '<a href="javascript:;" class="text-body delete-record"><i class="ti ti-trash ti-sm mx-2"></i></a>' +
              '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
              '<div class="dropdown-menu dropdown-menu-end m-0">' +
              '<a href="javascript:;"" class="dropdown-item">Edit</a>' +
              '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
              '</div>' +
              '</div>'
            );
          }
        }
      ],
      order: [[1, 'desc']],
      dom:
        '<"row mx-2"' +
        '<"col-sm-12 col-md-4 col-lg-6" l>' +
        '<"col-sm-12 col-md-8 col-lg-6"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center align-items-center flex-sm-nowrap flex-wrap me-1"<"me-3"f><"user_role w-px-200 pb-3 pb-sm-0">>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: 'Show _MENU_',
        search: 'Search',
        searchPlaceholder: 'Search..'
      },
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },
      initComplete: function () {
        // Adding role filter once table initialized
        this.api()
          .columns(2)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
            )
              .appendTo('.user_role')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
              });
          });
      }
    });
  }
  // Delete Record
  $('.datatables-users tbody').on('click', '.delete-record', function () {
    dtUser.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

(function () {
  // On edit role click, update text
  var roleEditList = document.querySelectorAll('.role-edit-modal'),
    roleAdd = document.querySelector('.add-new-role'),
    roleTitle = document.querySelector('.role-title');

  roleAdd.onclick = function () {
    roleTitle.innerHTML = 'Add New Role'; // reset text
  };
  if (roleEditList) {
    roleEditList.forEach(function (roleEditEl) {
      roleEditEl.onclick = function () {
        roleTitle.innerHTML = 'Edit Role'; // reset text
      };
    });
  }
})();


$(document).ready(function () {

    $(".add-role-model").on("click", function () {
        $("#permissionsTable").html("");
        $.ajax({
          url: '/permissions-list', // Replace with your actual API endpoint
          method: 'GET',
          dataType: 'json',
          success: function(response) {
            console.log(response);

            let categories = {};

            // Group permissions by category
            response.data.forEach(function (permission) {
              if (!categories[permission.category_id]) {
                categories[permission.category_id] = {
                  name: permission.category_name, // Assuming API returns category_name
                  permissions: []
                };
              }
              categories[permission.category_id].permissions.push(permission);
            });

            let permissionsHtml = `
              <div class="table-responsive">
                <table class="table table-flush-spacing">
                  <tbody>
                    <tr>
                      <td>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="selectAll" />
                          <label class="text-nowrap fw-semibold">Administrator Access
                            <i class="ti ti-info-circle"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Allows full access to the system"></i>
                          </label>
                        </div>
                      </td>
                    </tr>`;

            // Loop through each category
            for (let categoryId in categories) {
              let category = categories[categoryId];

              // Display Category Name
              permissionsHtml += `
                <tr>
                  <td>
                    <strong>${category.name}</strong>
                  </td>
                </tr>`;

              // Create a flex container for permissions
              permissionsHtml += `<tr><td><div class="permission-container">`;

              // Loop through permissions under this category
              category.permissions.forEach(function (permission) {
                permissionsHtml += `
                  <div class="permission-item">
                    <div class="form-check">
                      <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" id="perm_r_${permission.id}" value="${permission.name}" />
                      <label class="form-check-label" for="perm_r_${permission.id}">${permission.name}</label>
                    </div>
                  </div>`;
              });

              permissionsHtml += `</div></td></tr>`; // Close flex container
            }

            permissionsHtml += `</tbody></table></div>`;

            // Insert the generated table into the element with id 'permissionsTable'
            $("#permissionsTable").html(permissionsHtml);

            // Select All Checkbox Functionality
            $("#selectAll").on("change", function () {
              $(".perm-checkbox").prop("checked", this.checked);
            });

          },
          error: function(error) {
            console.error('Error fetching data:', error);
          }
        });
      });




      $(".role-edit-modal").on("click", function () {
        var roleId = $(this).data("role-id");

        // Clear previous data
        $("#permissionsTable").html("");
        $("#modalRoleName").val("");

        $.ajax({
            url: "/roles/" + roleId + "/permissions",
            type: "GET",
            success: function (response) {
                $("#modalRoleName").val(response.role.name); // Set Role Name

                let categories = {};

                // Group permissions by category
                response.permissions.forEach(function (permission) {
                    if (!categories[permission.category_id]) {
                        categories[permission.category_id] = {
                            name: permission.category_name,
                            permissions: []
                        };
                    }
                    categories[permission.category_id].permissions.push(permission);
                });

                let permissionsHtml = `
                    <div class="table-responsive">
                        <table class="table table-flush-spacing">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll" />
                                            <label class="text-nowrap fw-semibold">Administrator Access
                                                <i class="ti ti-info-circle"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Allows full access to the system"></i>
                                            </label>
                                        </div>
                                    </td>
                                </tr>`;

                // Loop through each category
                for (let categoryId in categories) {
                    let category = categories[categoryId];

                    // Display Category Name
                    permissionsHtml += `
                        <tr>
                            <td><strong>${category.name}</strong></td>
                        </tr>`;

                    // Create a flex container for permissions
                    permissionsHtml += `<tr><td><div class="permission-container">`;

                    // Loop through permissions under this category
                    category.permissions.forEach(function (permission) {
                        permissionsHtml += `
                            <div class="permission-item">
                                <div class="form-check">
                                    <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" id="perm_r_${permission.id}" value="${permission.name}" ${permission.assigned ? "checked" : ""} />
                                    <label class="form-check-label" for="perm_r_${permission.id}">${permission.name}</label>
                                </div>
                            </div>`;
                    });

                    permissionsHtml += `</div></td></tr>`; // Close flex container
                }

                permissionsHtml += `</tbody></table></div>`;

                $("#permissionsTable").html(permissionsHtml);

                // Select All Checkbox Functionality
                $("#selectAll").on("change", function () {
                    $(".perm-checkbox").prop("checked", this.checked);
                });
            },
            error: function (error) {
                console.error("Error fetching data:", error);
            }
        });
    });

});




 // destroy role

 document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-role").forEach((element) => {
        element.addEventListener("click", function () {
            let roleId = this.getAttribute("data-role-id");

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
                    // Send DELETE request
                    fetch(`/roles/${roleId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Deleted!", "Role has been deleted.", "success").then(() => {
                                location.reload(); // Reload page after deletion
                            });
                        } else {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire("Error!", "Could not delete role.", "error");
                    });
                }
            });
        });
    });
});
