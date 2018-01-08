const ApiPrefix = require('./ApiPrefix');
const AES = require('aes-js');
const JSEncrypt = require('JSEncrypt').JSEncrypt;
const PADDING_CHAR = 0;
const HAND_SHAKE = 'key/hand-shake';
const AES_KEY = 'aesKey';

function loadKey() {
    const KEY = 'public_key';
    let key = sessionStorage.getItem(KEY);
    if (key) {
        return new Promise((a, b) => a(key));
    } else {
        return ajax('POST', ApiPrefix + HAND_SHAKE)
            .then((r) => {
                if (r.success) {
                    key = r.result;
                    sessionStorage.setItem(KEY, r.result);
                    return new Promise((a, b) => a(key));
                }
            });
    }
}

function loadAesKey() {
    const AES_KEY = 'aesKey';
    let keyData = sessionStorage.getItem(AES_KEY);
    let ret;
    try {
        if (keyData) {
            ret = JSON.parse(keyData);
            if (ret && typeof ret.key === 'string' && typeof ret.iv === 'string') {
                return ret;
            }
        }
    } catch (e) {
    }

    {
        const KEY_SET = '0123456789ABCDEF';
        const KEY_LENGTH = 256 / 4;
        const IV_LENGTH = 16 * 8 / 4;
        let key = '', iv = '';
        while (key.length < KEY_LENGTH) {
            let i = (Math.random() * KEY_SET.length) | 0;
            key += KEY_SET[i];
        }
        while (iv.length < IV_LENGTH) {
            let i = (Math.random() * KEY_SET.length) | 0;
            iv += KEY_SET[i];
        }
        ret = {key, iv};
        sessionStorage.setItem(AES_KEY, JSON.stringify(ret));
        // console.debug(ret);

        return ret;
    }
}

function encrypt(data) {
    let key = loadAesKey();
    let keyBytes = AES.utils.hex.toBytes(key.key);
    let ivBytes = AES.utils.hex.toBytes(key.iv);
    let cbc = new AES.ModeOfOperation.cbc(keyBytes, ivBytes);
    let textBytes = [...AES.utils.utf8.toBytes(data)];
    while (textBytes.length % 16 !== 0 && textBytes.length > 0) {
        textBytes.push(PADDING_CHAR);
    }

    let encryptedBytes = cbc.encrypt(textBytes);
    return AES.utils.hex.fromBytes(encryptedBytes);
}

function decrypt(encryptedHex) {
    let key = loadAesKey();
    let encryptedBytes = AES.utils.hex.toBytes(encryptedHex);
    let keyBytes = AES.utils.hex.toBytes(key.key);
    let ivBytes = AES.utils.hex.toBytes(key.iv);
    let cbc = new AES.ModeOfOperation.cbc(keyBytes, ivBytes);
    let decryptedBytes = [...cbc.decrypt(encryptedBytes)];
    //Remove padding char
    let lastPaddingIndex = decryptedBytes.length - 1;

    while (lastPaddingIndex>=0 && decryptedBytes[lastPaddingIndex] === PADDING_CHAR)
        lastPaddingIndex--;

    decryptedBytes = decryptedBytes.slice(0, lastPaddingIndex + 1);

    let decryptedText = AES.utils.utf8.fromBytes(decryptedBytes);
    // console.log(decryptedText);

    return decryptedText;
}

function rsaEncrypt(data) {
    let encrypt = new JSEncrypt();
    return loadKey().then(key => {
        encrypt.setKey(key);
        let encrypted = encrypt.encrypt(data);
        // console.log(encrypted);
        return encrypted;
    });
}

sessionStorage.removeItem(AES_KEY);

module.exports = {
    JSEncrypt: JSEncrypt,
    AES: AES,
    loadRsaKey: loadKey,
    loadAesKey: loadAesKey,
    encrypt: encrypt,
    decrypt: decrypt,
    rsaEncrypt: rsaEncrypt,
};