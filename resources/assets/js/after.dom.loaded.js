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

    let selectDefault = '.phpdebugbar-datasets-switcher';
    let allSelect =$('select');

    allSelect
        .filter(`:not(${selectDefault})`)
        .each(function () {
        const WAIT = 250;
        let s = $(this);
        let timer = null;

        // configuration of the observer:
        let config = {attributes: true, childList: true, characterData: true};

        // create an observer instance
        let observer = new MutationObserver(mutations => {
            if (timer) clearInterval(timer);
            
            timer = setTimeout(() => {
                observer.disconnect();
                s.material_select('destroy');
                s.material_select();
                observer.observe(this, config);
                timer = null;
            }, WAIT);
        });

        // pass in the target node, as well as the observer options
        s.material_select();
        observer.observe(this, config);
    });

    allSelect.filter(selectDefault).addClass('browser-default');
});