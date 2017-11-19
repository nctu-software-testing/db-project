const Bezier = require('./Bezier');
const ApiPrefix = require('./ApiPrefix');

let CSRF_TOKEN_ELEMENT = document.querySelector('meta[name="csrf-token"]') || {};
function ajax(method, url, data) {
    method = method.toUpperCase();
    let head = {};
    data = data || {};
    head['X-CSRF-TOKEN'] = CSRF_TOKEN_ELEMENT.content;

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
function bAlert(title, body) {
    let m = $(`<div class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">${body ? title : ''}</h4>
      </div>
      <div class="modal-body">
        ${body ? body : title}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">關閉</button>
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
    bAlert: bAlert,
    Bezier: Bezier,
    Imgur: require('./Imgur'),
    ShowImg: require('./ImgModal').ShowImg,
    Modules: {
    }
};