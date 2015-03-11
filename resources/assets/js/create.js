$(function () {

    var currentType = $('#options-form').attr('data-current-type');
    $('#radio_' + currentType).prop('checked', true);

    $('.select-reward-type').on('click', function(e) {
        e.preventDefault();
    });
    $('[data-load-form]').on('click', function(e) {
        e.preventDefault();
        $('#options-form').load($(this).attr('href'), function() {
	        $('#save-button').attr('disabled', false);
        });
        $('.select-reward-type').prop('checked', false);

        var typeName = $(this).attr('href').split('/').slice(-1).pop();
        $('#radio_' + typeName).prop('checked', true);
    });
});