const Bezier = require('./Bezier');
const ApiPrefix = require('./ApiPrefix');
const Encryption = require('./Encryption');
const sha256 = require('sha256');
let CSRF_TOKEN_ELEMENT = document.querySelector('meta[name="csrf-token"]') || {};
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': CSRF_TOKEN_ELEMENT.content}
});

function encryptAjax(method, url, inputData) {
    method = method.toUpperCase();
    let head = {};
    let data = data || {};
    if (inputData instanceof HTMLFormElement) {
        let fd = new FormData(inputData);
        fd.forEach((v, k) => {
            if (typeof v !== 'string' || typeof k !== 'string')
                throw 'Unsupported Data';
            data[k] = v;
        });
    } else {
        data = Object.assign({}, inputData);
    }

    head['X-CSRF-TOKEN'] = CSRF_TOKEN_ELEMENT.content;
    let aesKeyStr = JSON.stringify(Encryption.loadAesKey());
    let param = new URLSearchParams();

    for (let k in data) {
        if (data.hasOwnProperty(k))
            param.append(k, data[k]);
    }

    return Encryption.rsaEncrypt(aesKeyStr)
        .catch(e=>toastr.error('憑證失效'))
        .then(encKey => {
            let paramStr = param.toString();
            head['X-Friends-Sugoi'] = Encryption.encrypt(sha256(paramStr));
            head['X-Friends-Tanoshii'] = encKey;
            let conf = {
                data: Encryption.encrypt(paramStr),
                type: method,
                cache: false,
                processData: false,
                contentType: 'application/any-buy',
                beforeSend: function (request) {
                    for (let k in head) {
                        if (head.hasOwnProperty(k)) {
                            request.setRequestHeader(k, head[k]);
                        }
                    }
                },
            };

            if (method === 'HEAD') {
                delete conf.data;
            }

            return new Promise((a, b) => {
                jQuery.ajax(url, conf).done(a).fail(b);
            })
        });
}

function ajax(method, url, data) {
    method = method.toUpperCase();
    let head = {};
    data = data || {};
    head['X-CSRF-TOKEN'] = CSRF_TOKEN_ELEMENT.content;

    if (data instanceof HTMLFormElement) {
        data = new FormData(data);
    }

    let conf = {
        data: data,
        type: method,
        cache: false,
        beforeSend: function (request) {
            for (let k in head) {
                if (head.hasOwnProperty(k)) {
                    request.setRequestHeader(k, head[k]);
                }
            }
        },
        dataType: 'json'
    };

    if (data instanceof FormData) {
        conf.processData = false;
        conf.contentType = false;
    }

    if (method === 'HEAD') {
        delete conf.data;
    }

    return new Promise((a, b) => {
        jQuery.ajax(url, conf).done(a).fail(b);
    })
}

/**
 *
 * @param title
 * @param [body]
 */
function bAlert(title, body, closeBtn = '關閉') {
    let m = $(`<div class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">${body ? title : ''}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ${body ? body : title}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-amber" data-dismiss="modal">${closeBtn}</button>
      </div>
    </div>
  </div>
</div>`);
    m.modal();

    return m;
}

require('./safariWarning');

$(function () {
    require('./Egg');

    function ScrollTo(b) {
        let s = 500;
        let oy = scrollY;
        if (typeof b === "string") {
            b = $(b);
            if (!b.length) return;
            b = b[0].getBoundingClientRect();
            b = b.top - document.body.getBoundingClientRect().top;
        }

        let d = b - oy;
        if (b === oy) return;

        const StartTime = Date.now();
        let timer;
        const loop = function () {
            const now = Date.now();
            let t = now - StartTime;
            let p = t / s;
            if (p > 1) p = 1;
            let _y = oy + Bezier([0.215, 0.61, 0.355, 1], p) * d;

            scrollTo(scrollX, _y);

            if (p === 1) {
                cancelAnimationFrame(timer);
            } else {
                timer = requestAnimationFrame(loop);
            }
        };
        timer = requestAnimationFrame(loop);
    }

    window.ScrollTo = ScrollTo;

    $("#back-to-top").on('click', function () {
        ScrollTo(0);
    });

    $('textarea.auto-height').on('input', function () {
        this.initHeight = this.initHeight || this.scrollHeight;
        let adjustedHeight = Math.max(this.scrollHeight, this.initHeight);
        this.style.height = adjustedHeight + 'px';
    });

    $('a.jump[href^="#"]').click(function (e) {
        if (this.hash !== '#') {
            ScrollTo(this.hash);
        }

        e.preventDefault();
    });
});

Date.prototype.format = require('./Date');

module.exports = {
    ajax: ajax,
    encryptAjax: encryptAjax,
    bAlert: bAlert,
    Bezier: Bezier,
    Imgur: require('./Imgur'),
    ShowImg: require('./ImgModal').ShowImg,
    Modules: {},
    Pages: {
        Index: require('./pages/index'),
    },
    palette: require('./palette'),
    Captcha: require('./Captcha'),
    GetPublicKey: Encryption.loadRsaKey,
    Encryption: Encryption,
    JSEncrypt: Encryption.JSEncrypt,
    AES: Encryption.AES,
};