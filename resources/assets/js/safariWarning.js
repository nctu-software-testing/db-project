if (!sessionStorage.getItem('safariShowed')) {
    $(function () {
        let ua = navigator.userAgent.toLowerCase();
        let isSafari = false;
        try {
            isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
                    return p.toString() === "[object SafariRemoteNotification]";
                })(!window['safari'] || safari.pushNotification);
        }
        catch (err) {
        }
        isSafari = (isSafari || ((ua.indexOf('safari') !== -1) && (!(ua.indexOf('chrome') !== -1))) || (ua.indexOf('iphone')!==-1 || ua.indexOf('ipad')!==-1 || ua.indexOf('ipod')!==-1));

        if (isSafari) {
            bAlert('你有更好的選擇', `
                <p>因為Safari快要成為下一個IE了，本站不保證使用Safari的功能會正常。</p>
                <p class="visible-lg-block visible-md-block"
                    >請<a href="https://www.google.com.tw/chrome/browser/" target="_blank">下載Google Chrome</a> 以獲得最佳體驗。</p>
                <p class="visible-sm-block"
                    >請購買Android 平板以獲得最佳體驗。</p>
                <p class="visible-xs-block"
                    >請購買Android 手機以獲得最佳體驗。</p>
                <p class="visible-xs-block visible-sm-block"
                    >如遇資金問題，請洽<a href="https://www.facebook.com/messages/t/en.chingchang/" target="_blank">許乾爹</a></p>
            `);
            sessionStorage.setItem('safariShowed', "true");
        }
    });

}