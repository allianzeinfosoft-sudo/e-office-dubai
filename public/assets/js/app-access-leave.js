/**
 * App leave summary list
 */
'use strict';
$(function () {
  var dtLeaveTable = $('.datatables-leave-summary')

  // Leave List datatable
  if (dtLeaveTable.length) {
    var dtLeave = dtLeaveTable.DataTable({
        ajax: {
            url: "/leave-list",  // Fetch from Laravel API
            type: "GET",
            dataType: "json",
            dataSrc: "data"
        },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'leave_from' },
        { data: 'leave_to' },
        { data: 'leave_count' },
        { data: 'leave_type' },
        { data: 'leave_reason' },
        { data: 'apply_date' },
        { data: 'approved_cancel_date'},
        { data: 'status' }
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
            //leave from
          targets: 1,
          render: function (data, type, full, meta) {
            var $leave_from = full['leave_from'];

            return '<span class="fw-semibold">' + $leave_from + '</span>';
          }
        },
        {
          // leave to
          targets: 2,
          render: function (data, type, full, meta) {

            var $leave_to = full['leave_to'];
            return '<span class="fw-semibold">' + $leave_to + '</span>';
          }
        },
        {
          // leave count
          targets: 3,
          render: function (data, type, full, meta) {

            var $leave_day_count = full['leave_count'];
            if($leave_day_count > 1)
            {
                return '<button class="btn btn-sm btn-success">' + $leave_day_count + ' days</button>';

            }else{
                return '<button class="btn btn-sm btn-secondary">' + $leave_day_count + ' day</button>';
            }
          }
        },
        {
          // leave type
          targets: 4,
          render: function (data, type, full, meta) {

            var $leave_type = full['leave_type'];
            if($leave_type == 'off_day')
            {
                return '<button class="btn btn-sm btn-danger">Off</button>';
            }

            if($leave_type == 'full_day')
            {
                return '<button class="btn btn-sm btn-primary">Full</button>';
            }

            if($leave_type == 'half_day')
            {
                return '<button class="btn btn-sm btn-success">Half</button>';
            }
          }
        },
        {
            // leave reason
            targets: 5,
            render: function (data, type, full, meta) {

              var $leave_reason = full['leave_reason'];
              return '<span class="fw-semibold">' + $leave_reason + '</span>';

            }
          },
          {
            // leave apply date
            targets: 6,
            render: function (data, type, full, meta) {

              var $apply_date = full['apply_date'];
              return '<span class="fw-semibold">' + $apply_date + '</span>';

            }
          },
          {
            // Leave approved / cancel date
            targets: 7,
            render: function (data, type, full, meta) {

              var $approved_cancel_date = full['approved_cancel_date'];
              return '<span class="fw-semibold">' + $approved_cancel_date + '</span>';

            }
          },
          {
            // User Status
            targets: 8,
            render: function (data, type, full, meta) {

              var $status = full['status'];
              if($status == 1)
              {
                return '<button class="btn btn-sm btn-warning">Pending</button>';
              }
              if($status == 2)
              {
                return '<button class="btn btn-sm btn-success">Accept</button>';
              }

              if($status == 3)
              {
                return '<button class="btn btn-sm btn-danger">Reject</button>';
              }

              if($status == 4)
              {
                return '<button class="btn btn-sm btn-primary">Cancelled by user</button>';
              }

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
              return 'Details of ' + full['full_name'];
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
  $('.datatables-leave-summary tbody').on('click', '.delete-record', function () {
    dtLeave.row($(this).parents('tr')).remove().draw();
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});




/** leave pending table  **/

$(function () {
    var dtLeavePendingTable = $('.datatables-leave-pending');
    // Users List datatable
    if (dtLeavePendingTable.length) {
      var dtLeavePending = dtLeavePendingTable.DataTable({
          ajax: {
              url: "/leave-pending",
              type: "GET",
              dataType: "json",
              dataSrc: "data"
          },
        columns: [
          // columns according to JSON
          { data: '' },
          { data: 'leave_from' },
          { data: 'leave_to' },
          { data: 'leave_count' },
          { data: 'leave_type' },
          { data: 'leave_reason' },
          { data: 'apply_date' },
          { data: 'approved_cancel_date'},
          { data: 'status' }
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
              //leave from
            targets: 1,
            render: function (data, type, full, meta) {
              var $leave_from = full['leave_from'];

              return '<span class="fw-semibold">' + $leave_from + '</span>';
            }
          },
          {
            // leave to
            targets: 2,
            render: function (data, type, full, meta) {

              var $leave_to = full['leave_to'];
              return '<span class="fw-semibold">' + $leave_to + '</span>';
            }
          },
          {
            // leave count
            targets: 3,
            render: function (data, type, full, meta) {

              var $leave_day_count = full['leave_count'];
              if($leave_day_count > 1)
              {
                  return '<button class="btn btn-sm btn-success">' + $leave_day_count + ' days</button>';

              }else{
                  return '<button class="btn btn-sm btn-secondary">' + $leave_day_count + ' day</button>';
              }
            }
          },
          {
            // leave type
            targets: 4,
            render: function (data, type, full, meta) {

              var $leave_type = full['leave_type'];
              if($leave_type == 'off_day')
              {
                  return '<button class="btn btn-sm btn-danger">Off</button>';
              }

              if($leave_type == 'full_day')
              {
                  return '<button class="btn btn-sm btn-primary">Full</button>';
              }

              if($leave_type == 'half_day')
              {
                  return '<button class="btn btn-sm btn-success">Half</button>';
              }
            }
          },
          {
              // leave reason
              targets: 5,
              render: function (data, type, full, meta) {

                var $leave_reason = full['leave_reason'];
                return '<span class="fw-semibold">' + $leave_reason + '</span>';

              }
            },
            {
              // leave apply date
              targets: 6,
              render: function (data, type, full, meta) {

                var $apply_date = full['apply_date'];
                return '<span class="fw-semibold">' + $apply_date + '</span>';

              }
            },
            {
              // Leave approved / cancel date
              targets: 7,
              render: function (data, type, full, meta) {

                var $approved_cancel_date = full['approved_cancel_date'];
                return '<span class="fw-semibold">' + $approved_cancel_date + '</span>';

              }
            },
            {
              // User Status
              targets: 8,
              render: function (data, type, full, meta) {

                var $status = full['status'];
                if($status == 1)
                {
                  return '<button class="btn btn-sm btn-warning">Pending</button>';
                }
                if($status == 2)
                {
                  return '<button class="btn btn-sm btn-success">Accept</button>';
                }

                if($status == 3)
                {
                  return '<button class="btn btn-sm btn-danger">Reject</button>';
                }

                if($status == 4)
                {
                  return '<button class="btn btn-sm btn-primary">Cancelled by user</button>';
                }

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
                return 'Details of ' + full['full_name'];
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
    $('.datatables-leave-pending tbody').on('click', '.delete-record', function () {
      dtLeavePending.row($(this).parents('tr')).remove().draw();
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
      $('.dataTables_filter .form-control').removeClass('form-control-sm');
      $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
  });





/** leave status table  **/

$(function () {
    var dtLeaveStatusTable = $('.datatables-leave-status');
    var userId = document.querySelector('meta[name="auth-user-id"]').getAttribute('content');
    // Users List datatable
    if (dtLeaveStatusTable.length) {
      var dtLeaveStatus = dtLeaveStatusTable.DataTable({
          ajax: {
              url: "/leave-status/" + userId,
              type: "GET",
              dataType: "json",
              dataSrc: "data"
          },
        columns: [
          // columns according to JSON
          { data: '' },
          { data: 'leave_from' },
          { data: 'leave_to' },
          { data: 'leave_count' },
          { data: 'leave_type' },
          { data: 'leave_reason' },
          { data: 'apply_date' },
          { data: 'approved_cancel_date'},
          { data: 'status' }
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
              //leave from
            targets: 1,
            render: function (data, type, full, meta) {
              var $leave_from = full['leave_from'];

              return '<span class="fw-semibold">' + $leave_from + '</span>';
            }
          },
          {
            // leave to
            targets: 2,
            render: function (data, type, full, meta) {

              var $leave_to = full['leave_to'];
              return '<span class="fw-semibold">' + $leave_to + '</span>';
            }
          },
          {
            // leave count
            targets: 3,
            render: function (data, type, full, meta) {

              var $leave_day_count = full['leave_count'];
              if($leave_day_count > 1)
              {
                  return '<button class="btn btn-sm btn-success">' + $leave_day_count + ' days</button>';

              }else{
                  return '<button class="btn btn-sm btn-secondary">' + $leave_day_count + ' day</button>';
              }
            }
          },
          {
            // leave type
            targets: 4,
            render: function (data, type, full, meta) {

              var $leave_type = full['leave_type'];
              if($leave_type == 'off_day')
              {
                  return '<button class="btn btn-sm btn-danger">Off</button>';
              }

              if($leave_type == 'full_day')
              {
                  return '<button class="btn btn-sm btn-primary">Full</button>';
              }

              if($leave_type == 'half_day')
              {
                  return '<button class="btn btn-sm btn-success">Half</button>';
              }
            }
          },
          {
              // leave reason
              targets: 5,
              render: function (data, type, full, meta) {

                var $leave_reason = full['leave_reason'];
                return '<span class="fw-semibold">' + $leave_reason + '</span>';

              }
            },
            {
              // leave apply date
              targets: 6,
              render: function (data, type, full, meta) {

                var $apply_date = full['apply_date'];
                return '<span class="fw-semibold">' + $apply_date + '</span>';

              }
            },
            {
              // Leave approved / cancel date
              targets: 7,
              render: function (data, type, full, meta) {

                var $approved_cancel_date = full['approved_cancel_date'];
                return '<span class="fw-semibold">' + $approved_cancel_date + '</span>';

              }
            },
            {
              // User Status
              targets: 8,
              render: function (data, type, full, meta) {

                var $status = full['status'];
                if($status == 1)
                {
                  return '<button class="btn btn-sm btn-warning">Pending</button>';
                }
                if($status == 2)
                {
                  return '<button class="btn btn-sm btn-success">Accept</button>';
                }

                if($status == 3)
                {
                  return '<button class="btn btn-sm btn-danger">Reject</button>';
                }

                if($status == 4)
                {
                  return '<button class="btn btn-sm btn-primary">Cancelled by user</button>';
                }

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
                return 'Details of ' + full['full_name'];
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
    $('.datatables-leave-status tbody').on('click', '.delete-record', function () {
      dtLeaveStatus.row($(this).parents('tr')).remove().draw();
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
      $('.dataTables_filter .form-control').removeClass('form-control-sm');
      $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
  });
