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