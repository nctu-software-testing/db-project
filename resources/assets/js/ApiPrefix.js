/**
 * Created by s911415 on 2017/06/08.
 */

let PUBLIC_ELEMENT = document.querySelector('meta[name="public-path"]') || {};
const API_PREFIX = PUBLIC_ELEMENT.content || './';
module.exports = API_PREFIX;