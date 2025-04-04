'use strict';

$(function () {
  $('.ql-toolbar').remove();
  const fullToolbar = [
    [{ header: [1, 2, false] }],  // Corrected header format
    [{ font: [] }, { size: [] }],  // Font & size dropdowns
    ['bold', 'italic', 'underline', 'strike'],  // Basic formatting
    [{ color: [] }, { background: [] }],  // Text & background colors
    [{ script: 'super' }, { script: 'sub' }],  // Superscript & subscript
    [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],  // Lists & indents
    [{ direction: 'rtl' }],  // Right-to-left text support
    ['blockquote', 'code-block'],  // Block formatting
    ['link', 'image', 'video', 'formula'],  // Media & formulas
    ['clean']  // Remove formatting button
  ];

    var quill = new Quill('#leave-editor', {
        theme: 'snow',
        placeholder: 'Type your reason here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

        // Capture Quill Content before Form Submission
        document.getElementById('leaveForm').addEventListener('submit', function () {
        document.getElementById('reason').value = quill.root.innerHTML;
    });

})();
