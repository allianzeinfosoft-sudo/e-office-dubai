$(document).ready(function () {
    $.ajax({
        url: '/user/roles', // Laravel API route
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            let selectBox = $('#user-role');
            selectBox.empty(); // Clear existing options
            selectBox.append('<option value="">Select Role</option>');
            $.each(data, function (key, role) {
                selectBox.append('<option value="' + role.name + '">' + role.name + '</option>');
            });
        },
        error: function () {
            alert('Failed to load roles.');
        }
    });
});


