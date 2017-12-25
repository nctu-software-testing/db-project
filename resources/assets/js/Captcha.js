class Captcha {
    constructor(selector) {
        this._ele = document.querySelector(selector);
        this._basePath = this._ele.getAttribute('data-base');
        this._maskPath = this._ele.getAttribute('data-mask');
        this._slicePath = this._ele.getAttribute('data-slice');
        this._verifyPath = this._ele.getAttribute('data-verify');
        this._input = document.createElement('input');
        this._sliceImage = new Image();
        this._initialized = false;

        this._imageContainer = null;
        this._inputContainer = null;
    }

    Initialize() {
        if (this._initialized) return;

        this._ele.innerHTML = '';
        this._ele.classList.add('captcha');

        this._input.type = 'range';
        this._input.min = 0;
        this._input.step = 0.25;
        this._input.value = 0;
        this._input.addEventListener('input', () => this.onInputChanged());
        this._input.addEventListener('change', () => this.onInputEntered());
        this._sliceImage.classList.add('slice');

        this._imageContainer = document.createElement('div');
        this._imageContainer.classList.add('image-container');
        this._inputContainer = document.createElement('div');
        this._inputContainer.classList.add('input-container');


        this._inputContainer.appendChild(this._input);
        this._ele.appendChild(this._imageContainer);
        this._ele.appendChild(this._inputContainer);
        this._fetchImage();

        this._initialized = true;
    }

    Reset() {
        this._imageContainer.innerHTML = '';
        this._input.value = 0;
        this._input.disabled = false;

        this._fetchImage()
            .then(() => this.updateImagePosition());
    }

    _fetchImage() {
        return fetch(this.getRandomUrl(this._basePath), {credentials: 'include'})
            .then(b => b.arrayBuffer())
            .then(buf => {
                let bufferData = new Uint8Array(buf);
                let getInt = (upper, lower) => (upper << 8) | lower;
                let getData = (start) => getInt(bufferData[start], bufferData[start + 1]);
                const COL_COUNT = getData(bufferData.length - 2);
                const ROW_COUNT = getData(bufferData.length - 4);
                const GRID_SIZE = getData(bufferData.length - 6);
                const IMG_LEN = getData(bufferData.length - 8);
                const imageArray = new Array(IMG_LEN);
                for (let i = 0, start = bufferData.length - 8 - IMG_LEN * 2; i < IMG_LEN; i++, start += 2) {
                    let index = getData(start);
                    imageArray[index] = i;
                }

                let blob = new Blob([bufferData]);
                let blobUrl = URL.createObjectURL(blob);

                this._imageContainer.style.width = `${COL_COUNT * GRID_SIZE}px`;
                this._imageContainer.style.height = `${ROW_COUNT * GRID_SIZE}px`;

                this._input.max = (COL_COUNT - 1) * GRID_SIZE;

                let appendImageToElement = (url, ele, callback) => {
                    for (let r = 0, i = 0; r < ROW_COUNT; r++) {
                        for (let c = 0; c < COL_COUNT; c++, i++) {
                            let grid = document.createElement('div');
                            grid.classList.add('grid');
                            grid.style.backgroundImage = `url(${url})`;
                            let posR = (imageArray[i] / COL_COUNT) | 0, posC = imageArray[i] % COL_COUNT;
                            posR *= GRID_SIZE;
                            posC *= GRID_SIZE;
                            grid.style.backgroundPosition = `-${posC}px -${posR}px`;
                            grid.style.width = grid.style.height = `${GRID_SIZE}px`;
                            grid.style.left = `${c * GRID_SIZE}px`;
                            grid.style.top = `${r * GRID_SIZE}px`;

                            ele.appendChild(grid);
                            if (typeof callback === "function") {
                                callback(grid);
                            }
                        }
                    }
                };

                appendImageToElement(blobUrl, this._imageContainer);
                appendImageToElement(this.getRandomUrl(this._maskPath), this._imageContainer, (grid) => {
                    grid.classList.add('mask');
                });

                this._ele.style.width = this._imageContainer.style.width;

                this._sliceImage.src = this.getRandomUrl(this._slicePath);

                this._imageContainer.appendChild(this._sliceImage);
            });
    }

    onInputChanged() {
        this.updateImagePosition();
        console.log('changed');
    }

    onInputEntered() {
        this.updateImagePosition();
        console.log('entered');
        this._input.disabled = true;
        ajax('POST', this._verifyPath, {value: this._input.value})
            .then((d) => {
                if (d.success) {
                    toastr.success(d.result, '', {
                        positionClass: 'toast-center',
                        timeOut: 3e3,
                    });

                } else {
                    toastr.error(d.result, '', {
                        positionClass: 'toast-center',
                        timeOut: 3e3,
                    });
                    this.Reset();
                }
            });
    }

    updateImagePosition() {
        this._sliceImage.style.transform = `translateX(${this._input.value}px)`;
    }

    getRandomUrl(url) {
        let randomStr = new Date().getTime();
        if (url.indexOf('?') === -1) url += '?_=';
        else url += '&_=';

        return url + randomStr.toString();
    }
}

module.exports = Captcha;