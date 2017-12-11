class KeyDetector {
    constructor(keys) {
        this._keys = Object.assign(keys);
        this._keyCounter = 0;
        this._callback = () => {
        };
    }

    onKeyPress(keyCode) {
        if (keyCode === this._keys[this._keyCounter]) {
            this._keyCounter++;
        } else {
            this._keyCounter = 0;
        }

        if (this._keyCounter === this._keys.length) {
            this._keyCounter = 0;
            this._callback();
        }
    }

    setOnSeqDetected(callback) {
        this._callback = callback;
    }
}

module.exports = KeyDetector;