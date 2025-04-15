'use strict';

(function () {
  // Full Toolbar Configuration
  $('.ql-toolbar').remove();
  const fullToolbar = [
    [{ font: [] }, { size: [] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ color: [] }, { background: [] }],
    [{ script: 'super' }, { script: 'sub' }],
    [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
    [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
    [{ direction: 'rtl' }],
    ['link', 'image', 'video', 'formula'],
    ['clean']
  ];



    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('leaveForm');
        const quillEditor = new Quill('#leave-editor', { theme: 'snow' });

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Always prevent default first

            // Get values
            const userId = document.getElementById('user_id').value.trim();
            const leaveFrom = document.getElementById('leave-from').value.trim();
            const leaveTo = document.getElementById('leave-to').value.trim();
            const reason = quillEditor.root.innerText.trim(); // Plain text
            const hiddenReason = document.getElementById('reason');
            hiddenReason.value = quillEditor.root.innerHTML.trim(); // Store HTML in hidden field

            let errors = [];

            // === Validation ===
            if (!userId) {
                errors.push("User is required.");
            }

            if (!leaveFrom) {
                errors.push("Leave From date is required.");
            } else if (isNaN(Date.parse(leaveFrom))) {
                errors.push("Leave From must be a valid date.");
            }

            if (!leaveTo) {
                errors.push("Leave To date is required.");
            } else if (isNaN(Date.parse(leaveTo))) {
                errors.push("Leave To must be a valid date.");
            }

            if (!isNaN(Date.parse(leaveFrom)) && !isNaN(Date.parse(leaveTo))) {
                let fromDate = new Date(leaveFrom);
                let toDate = new Date(leaveTo);
                if (fromDate > toDate) {
                    errors.push("Leave From must be before or equal to Leave To.");
                }
            }

            if (reason.length > 255) {
                errors.push("Leave reason must not exceed 255 characters.");
            }

            if (!reason) {
                errors.push("Leave reason is required");
            }

            const leaveTypeSelected = document.querySelector('input[name="leave_type"]:checked');
            if (!leaveTypeSelected) {
                errors.push("Please select a leave type.");
            }



            // === Show errors or submit ===
            let errorBox = document.getElementById('formErrors');
            if (!errorBox) {
                errorBox = document.createElement('div');
                errorBox.id = 'formErrors';
                errorBox.className = 'alert alert-danger mt-3';
                form.prepend(errorBox);
            }

            if (errors.length > 0) {
                errorBox.innerHTML = '<ul class="mb-0">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            } else {
                errorBox.innerHTML = ''; // Clear old errors
                form.submit(); // Submit manually only if no errors
            }
        });
    });

})();
