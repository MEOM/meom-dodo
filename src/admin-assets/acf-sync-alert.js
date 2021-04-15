(function ($) {
    $(function () {
        $('.acf-admin-field-groups #the-list tr').each(function() {
            if($(this).find( '.sync' ).length > 0) {
                $(this).css('background', 'red');
            }
        });
    });
})(jQuery);
