/**
 * DataTables Basic
 */

'use strict';
let fv;
document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const formAddNewRecord = document.getElementById('userFormId');

    // Form validation for Add new User
    fv = FormValidation.formValidation(formAddNewRecord, {
      fields: {
        employeeID: {
          validators: {
            notEmpty: {
              message: 'The employeeid is required'
            }
          }
        },
        username: {
          validators: {
            notEmpty: {
              message: 'Username field is required'
            }
          }
        },
        email: {
            validators: {
              notEmpty: {
                message: 'Email field is required'
              },
              emailAddress: {
                message: 'Please enter a valid email address'
              },
            //   remote: {
            //     message: 'This email is already taken',
            //     url: '/check_email',
            //     type: 'GET',
            //     delay: 500,
            //     data: function () {
            //       const emailInput = document.querySelector('[name="email"]');
            //       const userId = document.querySelector('[name="user_id"]');
            //       const csrfToken = document.querySelector('meta[name="csrf-token"]');
            //       return {
            //         email: emailInput ? emailInput.value : '',
            //         user_id: userId ? userId.value : '',
            //         _token: csrfToken ? csrfToken.getAttribute('content') : ''
            //       };
            //     }
            //   }
            }
        },

        full_name: {
            validators: {
                notEmpty: {
                message: 'Full Name field is required'
                },
                regexp: {
                regexp: /^[A-Za-z\s]+$/,
                message: 'Full Name must contain only letters and spaces'
                }
            }
        },
        aadhaar: {
          validators: {
              stringLength: {
                  min: 12,
                  max: 12,
                  message: 'Aadhaar must be exactly 12 digits'
              },
              regexp: {
                  regexp: /^[0-9]{12}$/,
                  message: 'Aadhaar must contain only numbers'
              }
          }
        },
        esi_no: {
          validators: {
            regexp: {
                regexp: /^[0-9]{10}$/,
                message: 'ESI Number must contain only numbers'
            }
          }
        },
        dob: {
            validators: {
              notEmpty: {
                message: 'Date of Birth is required'
              },
              callback: {
                message: 'You must be at least 18 years old',
                callback: function(input) {
                  var dob = new Date(input.value);
                  if (isNaN(dob.getTime())) return false; // Invalid date

                  var today = new Date();
                  var minDate = new Date(
                    today.getFullYear() - 18,
                    today.getMonth(),
                    today.getDate()
                  );

                  return dob <= minDate;
                }
              }
            }
          },
        mobile_number: {
            validators: {

                regexp: {
                    regexp: /^[6-9]\d{9}$/,
                    message: 'Enter a valid 10-digit mobile number'
                }
            }
        },
        phonenumber: {
            validators: {
                notEmpty: {
                    message: 'Phone number is required'
                },
                regexp: {
                    regexp: /^[6-9]\d{9}$/,
                    message: 'Enter a valid 10-digit phone number'
                }
            }
          },
        landline: {
          validators: {
            regexp: {
                regexp: /^[0-9]{3,5}-[0-9]{6,8}$/,
                message: 'Enter a valid landline number (e.g., 0484-12345678)'
            }
          }
        },
        department_id: {
          validators: {
            notEmpty: {
              message: 'Department is required'
            }
          }
        },
        designation_id: {
          validators: {
            notEmpty: {
              message: 'Designation is required'
            }
          }
        },
        join_date: {
          validators: {
            notEmpty: {
              message: 'Join date is required'
            },
            callback: {
                message: 'Join date cannot be in the future',
                callback: function(input) {
                    var joindate = new Date(input.value);
                    var today = new Date();
                    // today.setHours(0, 0, 0, 0); // Remove time portion
                    return joindate <= today;
                }
            }
          }
        },
        status: {
          validators: {
            notEmpty: {
              message: 'Status is required'
            }
          }
        },
        appointment_status: {
          validators: {
            notEmpty: {
              message: 'Appointment_status is required'
            }
          }
        },

        profile_image:{
          validators: {
            file: {
                extension: 'jpeg,jpg,png,gif',
                type: 'image/jpeg,image/png,image/gif',
                maxSize: 2048 * 1024, // 2MB in bytes
                message: 'Only JPEG, JPG, PNG, and GIF files are allowed. Max size: 2MB.'
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
          // rowSelector: '.col-md-4'
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


$(document).ready(function () {

  $('#department_id').on('change', function () {
      let departmentId = $(this).val();

      if (departmentId) {
        // AJAX request to fetch departments
        $.ajax({
          url: `/departments/${departmentId}/designations`,
            type: 'GET',
            success: function(response) {
                // Clear the department select box
                $('#designation_id').empty().append('<option value="">Select Designation</option>');

                // Populate the department select box with the fetched data
                if (response.length > 0) {
                    $.each(response, function(index, designation) {
                        $('#designation_id').append('<option value="' + designation.id + '">' + designation.designation + '</option>');
                    });
                } else {
                    $('#designation_id').append('<option value="">No designations found</option>');
                }
            },
            error: function(xhr) {
                console.error('Error fetching designations:', xhr.responseText);
            }
        });
    } else {
        // If no branch is selected, clear the department select box
        $('#designation_id').empty().append('<option value="">Select Designation</option>');
    }

  });
});
