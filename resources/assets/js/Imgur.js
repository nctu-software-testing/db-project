const
    CLIENT_ID = 'da6bbb36aaea9eb',
    API_URL = 'https://api.imgur.com/3/image.json';

let Imgur = window.Imgur || {};

Imgur.UploadImage = function (file) {
    let header = new Headers();
    header.append("Authorization", "Client-ID " + CLIENT_ID);

    return fetch(API_URL, {
        method: 'POST',
        headers: header,
        // mode: 'cors',
        body: file
    })
        .then(resp => resp.json());
};

module.exports = Imgur;
