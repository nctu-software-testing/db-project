"use strict";
$(function () {
    const DELAY_TIME = 500;
    $('[data-form]').on('click', function (e) {
        let form = $(this.getAttribute('data-form'));
        let oldOverlay = $('.login_overlay');
        if (oldOverlay.length > 0) {
            oldOverlay.data('form').hide();
        }

        if (form.length > 0) {
            let overlay = oldOverlay.length > 0 ? oldOverlay : $('<div class="login_overlay"></div>');
            overlay.data('form', form);
            $('body').prepend(overlay);
            form.fadeIn(DELAY_TIME);
        }

        e.preventDefault();
    });
    $(document).on('click', '.login_overlay, .close', function (e) {
        let overlay = $('.login_overlay');
        overlay.data('form').fadeOut(DELAY_TIME, function () {
            overlay.remove();

        });
        e.preventDefault();
    });
});