/**
 * Show/hide password on click on button
 */
let showPassword = false
$('#showPassword').on("click", function (event) {
    showPassword = !showPassword;
    $('#inputPassword').attr("type", showPassword ? "text" : "password");
    $(this).toggleClass("btn-outline-secondary");
    $(this).toggleClass("btn-secondary");
});

/**
 * Show/hide password on hover over button
 */
$('#showPassword').on("mouseenter", function () {
    if (!showPassword) { $('#inputPassword').attr("type", "text") }
});
$('#showPassword').on("mouseleave", function () {
    if (!showPassword) {
        $('#inputPassword').attr("type", "password")
    }
});