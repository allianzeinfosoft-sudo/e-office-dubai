'use strict';

$(function () {
  $('.ql-toolbar').remove();
  // Full Toolbar Configuration
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

  // Initialize Quill Editor
  new Quill('#leave-editor', {
    bounds: '#leave-editor',
    placeholder: 'Type reason...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });

  $('#leaveForm').on('submit', function() {

    var quillContent = quill.root.innerHTML; // Get HTML content
    var quillPlainText = quill.getText().trim(); // Get plain text content (without HTML)

    console.log("Quill HTML:", quillContent); // Debugging output
    console.log("Quill Text:", quillPlainText); // Debugging output

    $('#reason').val(quillContent); // Store HTML content in hidden input
});

});