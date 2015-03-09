$(function () {
    $('[data-load-form]').on('click', function(e) {
        e.preventDefault();
        $('#save-button').attr('disabled', false);
        $('#options-form').load($(this).attr('href'));
    });
});