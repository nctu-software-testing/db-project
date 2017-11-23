/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./helper');
require('./extra/jquery.tmpl.js');
let main = require('./main');
require('./after.dom.loaded');

for (let k in main) {
    if (main.hasOwnProperty(k)) {
        window[k] = main[k];
    }
}
