/**
 * DataTables Basic
 */

'use strict';

let fv, offCanvasEl, offCanvasEl1, offCanvasEl2;

document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAddNewRecord = document.getElementById('form-add-new-branch'); 

    setTimeout(() => {
      const newRecord = document.querySelector('.create-new'),
        offCanvasElement = document.querySelector('#add-new-branch');

      // To open offCanvas, to add new branch
      if (newRecord) {
        newRecord.addEventListener('click', function () {
          offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
          // Empty fields on offCanvas open
          (offCanvasElement.querySelector('.dt-branch-name').value = ''),
            (offCanvasElement.querySelector('.dt-location').value = '')
          // Open offCanvas with form
          offCanvasEl.show();
        });
      }

    }, 200);

    // Form validation for Add new branch
    fv = FormValidation.formValidation(formAddNewRecord, {
      fields: {
        branch: {
          validators: {
            notEmpty: {
              message: 'The branch name is required'
            }
          }
        },
        location: {
          validators: {
            notEmpty: {
              message: 'Location field is required'
            }
          }
        }, 
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


document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
     
    const formAddNewDepartment = document.getElementById('form-add-new-department');
     

    setTimeout(() => { 
      // To open offCanvas, to add new department
      const newDepartment = document.querySelector('.create-department'),
      offCanvasElement1 = document.querySelector('#add-new-department');
      if(newDepartment){

        newDepartment.addEventListener('click', function () {
          offCanvasEl1 = new bootstrap.Offcanvas(offCanvasElement1);
          // Empty fields on offCanvas open
          // (offCanvasElement1.querySelector('.dt-department-name').value = ''),
          // (offCanvasElement1.querySelector('.dt-branch-name1').value = '')
          // Open offCanvas with form
          offCanvasEl1.show();
        });

      } 

    }, 200);



    // Form validation for Add new department
    fv = FormValidation.formValidation(formAddNewDepartment, {
      fields: {
        branch: {
          validators: {
            notEmpty: {
              message: 'The branch name is required'
            }
          }
        },
        department: {
          validators: {
            notEmpty: {
              message: 'Department field is required'
            }
          }
        }, 
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





document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
     
    const formAddNewDesignation = document.getElementById('form-add-new-designation');

    setTimeout(() => {
        
      // To open offCanvas, to add new designation
      const newDesignation = document.querySelector('.create-designation'),
      offCanvasElement2 = document.querySelector('#add-new-designation');
      if(newDesignation){

        newDesignation.addEventListener('click', function () {
          offCanvasEl2 = new bootstrap.Offcanvas(offCanvasElement2);
          // Empty fields on offCanvas open
          // (offCanvasElement1.querySelector('.dt-department-name').value = ''),
          // (offCanvasElement1.querySelector('.dt-branch-name1').value = '')
          // Open offCanvas with form
          offCanvasEl2.show();
        });

      }


    }, 200);

      
    // Form validation for Add new designation
    fv = FormValidation.formValidation(formAddNewDesignation, {
      fields: {
        branch_id: {
          validators: {
            notEmpty: {
              message: 'The branch name is required'
            }
          }
        },
        department_id: {
          validators: {
            notEmpty: {
              message: 'Department field is required'
            }
          }
        }, 
        designation: {
          validators: {
            notEmpty: {
              message: 'Designation field is required'
            }
          }
        }, 
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
  
  let dt_basic_table = $('.datatables-basic'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    dt_basic;

  // DataTable with buttons
  // --------------------------------------------------------------------
   
  if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({
       
      columnDefs: [
        
        {
          // For Checkboxes
          targets: 0,
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
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Branch</span>',
          className: 'create-new btn btn-primary mr-5'
        },
        {
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Department</span>',
          className: 'create-department btn btn-primary mr-5'
        },
        {
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Designation</span>',
          className: 'create-designation btn btn-primary'
        }

      ],
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
      }
    });
    $('div.head-label').html('<h5 class="card-title mb-0">Branch & Department List</h5>');
  }
 

  
  // Delete Record
  $('.datatables-basic tbody').on('click', '.delete-record', function () {
    dt_basic.row($(this).parents('tr')).remove().draw();
  }); 

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

 

$(document).ready(function() {
  $('#branchName3').select2();
});

 
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
                      $('#department1').empty().append('<option value="">Select</option>');

                      // Populate the department select box with the fetched data
                      if (response.length > 0) {
                          $.each(response, function(index, department) {
                              $('#department1').append('<option value="' + department.id + '">' + department.department + '</option>');
                          });
                      } else {
                          $('#department1').append('<option value="">No departments found</option>');
                      }
                  },
                  error: function(xhr) {
                      console.error('Error fetching departments:', xhr.responseText);
                  }
              });
          } else {
              // If no branch is selected, clear the department select box
              $('#department1').empty().append('<option value="">Select</option>');
          }
      });
  }); 