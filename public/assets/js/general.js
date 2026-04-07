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

function updateClock(element) {
    var currentTime = new Date();

    // Fallback to 'UTC' if the config isn't found
    var tz = window.AppConfig ? window.AppConfig.timezone : 'UTC';

    // Use toLocaleString to get the time in Dubai (Asia/Dubai)
    var dubaiTimeStr = currentTime.toLocaleString("en-US", {
       // timeZone: "Asia/Dubai",
        timeZone: tz,
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });

    // Display the time on the page
    $('#clock').html('<i class="ti ti-clock fis rounded-circle fs-4"></i> ' + dubaiTimeStr);
    $('#attendance_clock').html(dubaiTimeStr);
}
