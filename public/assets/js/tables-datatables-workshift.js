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
            targets: 0,
            data: null,
            title: 'S.No',
            render: function (data, type, row, meta) {
                return meta.row + 1;
            },
            orderable: false, // Optional: prevent sorting on this column
            searchable: false // Optional: exclude from search
        },
        {

           targets: 1,
           render: function (data, type, full, meta) {
             let $ShiftId = full['shift_id'];
             return "<span class='text-truncate d-flex align-items-center'>" + $ShiftId + '</span>';
           }
        },
        {
           // User Role
           targets: 2,
           render: function (data, type, full, meta) {
             let $shiftStartTime = full['shift_start_time'];
             return "<span class='text-truncate d-flex align-items-center'>" + $shiftStartTime + '</span>';
           }
        },
        {
           // User Role
           targets: 3,
           render: function (data, type, full, meta) {
             let $shiftEndTime = full['shift_end_time'];
             return "<span class='text-truncate d-flex align-items-center'>" + $shiftEndTime + '</span>';
           }
        },
        {
           // User Role
           targets: 4,
           render: function (data, type, full, meta) {
             let $minBreakTime = full['mini_break_time'];
             return "<span class='text-truncate d-flex align-items-center'>" + $minBreakTime + '</span>';
           }
        },
        {
            // User Role
            targets: 5,
            render: function (data, type, full, meta) {
              let $maxBreaktTime = full['max_break_time'];
              return "<span class='text-truncate d-flex align-items-center'>" + $maxBreaktTime + '</span>';
            }
         },
        {
          // Actions
          targets: 6,
          title: 'Actions',
            render: function (data, type, row, full) {
                return `
                     <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-success edit-workshift" data-id="${row.id}"><i class="ti ti-pencil"></i></a>
                      <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-danger delete-workshift" data-id="${row.id}"><i class="ti ti-trash"></i></a>`;
            }
        }
      ],
    //   order: [[2, 'desc']],
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




$(document).on('click', '.delete-workshift', function(e) {
    e.preventDefault();
    const workshiftId = $(this).data('id');

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

                    $.ajax({
                    url: `/workshift/delete/${workshiftId}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {

                        Swal.fire("Deleted!", "Workshift has been deleted.", "success").then(() => {
                            $('#datatables-workshift').DataTable().ajax.reload(); // Reload table
                        });

                    },
                    error: function(xhr) {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                    });

        }

});
});


function openWorkshiftOffcanvas(targetId = null) {
    $('#form-add-new-shift')[0].reset(); // Reset form
    $('#target_id').val(''); // Clear ID
    if (targetId) {
        $('#offcanvas-title-container').html(`<h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Edit Work Shift</h5><span class="text-white slogan">Edit New Work Shift</span>`);
        $.ajax({
            url: `/workshift/${targetId}/edit`,
            type: 'GET',
            success: function (data) {

                $('#shift_id').val(data.workshift.shift_id);
                $('#shift_start_time').val(data.workshift.shift_start_time);
                $('#shift_end_time').val(data.workshift.shift_end_time);
                $('#mini_break_time').val(data.workshift.mini_break_time);
                $('#max_break_time').val(data.workshift.max_break_time);
             }
        });
    }
    var offcanvasElement = $('#workshift_offcanvas');
    var offcanvas = new bootstrap.Offcanvas(offcanvasElement);
    offcanvas.show();
}
