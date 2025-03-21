// $(document).ready(function () {
 
//     $.ajax({
//         url: '/user/roles', // Laravel API route
//         type: 'GET',
//         dataType: 'json',
//         success: function (data) {
//             let selectBox = $('#user-role');
//             selectBox.empty(); // Clear existing options
//             selectBox.append('<option value="">Select Role</option>');

//             $.each(data, function (key, role) {
//                 selectBox.append('<option value="' + role.name + '">' + role.name + '</option>');
//             });
//         },
//         error: function () {
//             alert('Failed to load roles.');
//         }
//     });
 
// });

$(function () {
    setInterval(updateClock, 1000);
    updateClock();
});

function updateClock() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();

    // Format hours, minutes, and seconds to always show two digits
    hours = (hours < 10 ? "0" : "") + hours;
    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    // Combine to form the time string
    var timeString = hours + ":" + minutes + ":" + seconds;

    // Display the time on the page
    $('#clock').html('<i class="fa fa-clock fis rounded-circle me-1"></i>' +timeString);
}
