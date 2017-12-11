const KeyDetector = require('./KeyDetector');
window.StartE = function () {
    const eDiv = $("#ediv"), eContainer = eDiv.find(".e-container"), eBg = eContainer.find(".ebg", eContainer);
    eDiv.addClass('start');
    const scale = Math.max(eDiv.offsetWidth / eBg.offsetWidth, eDiv.offsetHeight / eBg.offsetHeight);
    const stTag = `translate(-50%, -50%)\x20scale(${scale})`;


    eContainer[0].style.transform = stTag;
    setTimeout(() => location.href = atob('aHR0cHM6Ly93ZWItcHJvZ3JhbW1pbmctczE3dS5zOTExNDE1LnRrLw=='), 7500);
};

window.StartK = function () {
    const kDiv = $("#touch-keyboard");
    kDiv.addClass('show');
    setTimeout(() => kDiv.removeClass('show'), 3000);
};

const _key = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65];
const _keyShow = _key.slice(0, _key.length - 2);
let eggDetector = new KeyDetector(_key);
eggDetector.setOnSeqDetected(()=>window.StartE());

let eggHintDetector = new KeyDetector(_keyShow);
eggHintDetector.setOnSeqDetected(()=>window.StartK());

const checkEgg = (keyCode) => {
    eggDetector.onKeyPress(keyCode);
    eggHintDetector.onKeyPress(keyCode);
};

window.addEventListener('keydown', function (e) {
    checkEgg(e.keyCode);
});

$(".touch-key").click(function () {
    const code = this.getAttribute('data-keycode') * 1;
    checkEgg(code);
});

const Swipedetect = (el, callback) => {

    let touchsurface = el,
        swipedir,
        startX,
        startY,
        dist,
        distX,
        distY,
        threshold = 50, //required min distance traveled to be considered swipe
        restraint = 150, // maximum distance allowed at the same time in perpendicular direction
        allowedTime = 500, // maximum time allowed to travel that distance
        elapsedTime,
        startTime,
        handleswipe = callback || function (swipedir) {
            };

    touchsurface.addEventListener('touchstart', function (e) {
        let touchobj = e.changedTouches[0];
        swipedir = 'none';
        dist = 0;
        startX = touchobj.clientX;
        startY = touchobj.clientY;
        // console.log('s', startX, startY);
        startTime = new Date().getTime(); // record time when finger first makes contact with surface
        // e.preventDefault()
    }, false);

    touchsurface.addEventListener('touchmove', function (e) {
        // e.preventDefault() // prevent scrolling when inside DIV
    }, false);

    touchsurface.addEventListener('touchend', function (e) {
        let touchobj = e.changedTouches[0];
        distX = touchobj.clientX - startX; // get horizontal dist traveled by finger while in contact with surface
        distY = touchobj.clientY - startY; // get vertical dist traveled by finger while in contact with surface

        // console.log(distX, distY);
        elapsedTime = new Date().getTime() - startTime; // get time elapsed
        if (elapsedTime <= allowedTime) { // first condition for awipe met
            if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint) { // 2nd condition for horizontal swipe met
                swipedir = (distX < 0) ? 'left' : 'right'; // if dist traveled is negative, it indicates left swipe
            }
            else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint) { // 2nd condition for vertical swipe met
                swipedir = (distY < 0) ? 'up' : 'down'; // if dist traveled is negative, it indicates up swipe
            }
        }
        handleswipe(swipedir);
    }, false);
};

Swipedetect(document.body, (dir) => {
    switch (dir) {
        case 'left':
            checkEgg(37);
            break;
        case 'up':
            checkEgg(38);
            break;
        case 'right':
            checkEgg(39);
            break;
        case 'down':
            checkEgg(40);
            break;
    }
});