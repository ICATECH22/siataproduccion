$(document).ready(function() {
    // hide the alert
    $(".alert").first().hide().slideDown(200).delay(2000).slideUp(500, function() {
        $(this).remove();
    });
});