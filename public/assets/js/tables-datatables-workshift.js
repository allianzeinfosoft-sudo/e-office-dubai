/**
 * DataTables Basic
 */

'use strict';

let fv, offCanvasEl;
document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAddNewRecord = document.getElementById('form-add-new-shift');

    setTimeout(() => {
      const newRecord = document.querySelector('.create-new'),
        offCanvasElement = document.querySelector('#add-new-shift');

      // To open offCanvas, to add new record
      if (newRecord) {
        newRecord.addEventListener('click', function () {
          offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
          // Empty fields on offCanvas open

          (offCanvasElement.querySelector('.dt-shift_id').value = ''),
          (offCanvasElement.querySelector('.dt-shift-start').value = ''),
            (offCanvasElement.querySelector('.dt-shift-end').value = ''),
            (offCanvasElement.querySelector('.dt-min-break').value = ''),
            (offCanvasElement.querySelector('.dt-max-break').value = '') ;
          // Open offCanvas with form
          offCanvasEl.show();
        });
      }
    }, 200);

    // Form validation for Add new record
    fv = FormValidation.formValidation(formAddNewRecord, {
      fields: {
        shift_id: {
            validators: {
              notEmpty: {
                message: 'Shift ID is required'
              }
            }
          },
        shift_start_time: {
          validators: {
            notEmpty: {
              message: 'Shift start time is required'
            }
          }
        },
        shift_end_time: {
          validators: {
            notEmpty: {
              message: 'Shift end time is required'
            }
          }
        },
        mini_break_time: {
          validators: {
            notEmpty: {
              message: 'Minimum break time is required'
            }
          }
        },
        max_break_time: {
          validators: {
            notEmpty: {
              message: 'Maximum break time is required'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-12'
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        // autoFocus: new FormValidation.plugins.AutoFocus()
      },
      init: instance => {
        instance.on('plugins.message.placed', function (e) {
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    });


  })();
});

// datatable (jquery)
$(function () {
  var dt_basic_table = $('.datatables-basic'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    dt_basic;

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({

    ajax: {
        url: "/workshift/list",  // Fetch from Laravel API
        type: "GET",
        dataType: "json",
        dataSrc: "data"
    },
      columns: [
        { data: '' },
        { data: 'id' },
        { data: 'shift_id'},
        { data: 'shift_start_time' },
        { data: 'shift_end_time' },
        { data: 'mini_break_time' },
        { data: 'max_break_time' },
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
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
           // User Role
           targets: 2,
           render: function (data, type, full, meta) {
             let $role = full['shift_id'];
             return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
           }
        },
        {
           // User Role
           targets: 3,
           render: function (data, type, full, meta) {
             let $role = full['shift_start_time'];
             return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
           }
        },
        {
           // User Role
           targets: 4,
           render: function (data, type, full, meta) {
             let $role = full['shift_end_time'];
             return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
           }
        },
        {
           // User Role
           targets: 5,
           render: function (data, type, full, meta) {
             let $role = full['min_break_time'];
             return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
           }
        },
        {
            // User Role
            targets: 5,
            render: function (data, type, full, meta) {
              let $role = full['max_break_time'];
              return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
            }
         },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          orderable: false,
          searchable: false,
          render: function (data, type, full, meta) {
            return (
              '<div class="d-inline-block">' +
              '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-primary ti ti-dots-vertical"></i></a>' +
              '<ul class="dropdown-menu dropdown-menu-end m-0">' +
              '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
              '<li><a href="javascript:;" class="dropdown-item">Archive</a></li>' +
              '<div class="dropdown-divider"></div>' +
              '<li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></li>' +
              '</ul>' +
              '</div>' +
              '<a href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="text-primary ti ti-pencil"></i></a>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [

        {
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
          className: 'create-new btn btn-primary'
        }
      ]
    });
    $('div.head-label').html('<h5 class="card-title mb-0">Work Shift</h5>');
  }




  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
