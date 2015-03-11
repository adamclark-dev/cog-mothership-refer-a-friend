$(function () {
    $('[data-load-form]').on('click', function(e) {
        e.preventDefault();
        $('#options-form').load($(this).attr('href'), function() {
	        $('#save-button').attr('disabled', false);
        });
        $('.select-reward-type').prop('checked', false);

        var typeName = $(this).attr('href').split('/').slice(-1).pop();
        $('#radio-' + typeName).prop('checked', true);
    });
});