$(document).on('pageinit', function () {
    //Add the proper list ID when clicking the add "button"
    $('#add-to-list').on('click', null, null, function(event) {
        $('#add-to-list').attr('href', $('#add-to-list').attr('baseurl') + $('#select-list').val());
    });
});
