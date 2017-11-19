/**
 * Try convert an object to array
 * @param obj
 * @returns {*}
 */
Array.prototype.toArray = (obj) => {
    return Array.prototype.slice.call(obj);
};

/**
 * Get context between a string
 * @param {String} startString
 * @param {String} endString
 * @param {Number} [fromIndex] default = 0
 *
 * @returns {String} context
 */
String.prototype.getStringBetween = function (startString, endString, fromIndex) {
    if (fromIndex === undefined) fromIndex = 0;

    let startIndex = this.indexOf(startString, fromIndex);
    let endIndex = this.indexOf(endString, startIndex);

    if (startIndex === -1) return "";

    if (endIndex === -1) return this.substr(startIndex);

    //Remove Starting String
    startIndex += startString.length;

    return this.substring(startIndex, endIndex);
};

/**
 * Get context between a string
 * @param {String} startString
 * @param {String} endString
 * @param {Number} [fromIndex] default = 0
 *
 * @returns {String[]} context
 */
String.prototype.getStringBetweenMulti = function (startString, endString, fromIndex) {
    if (fromIndex === undefined) fromIndex = 0;

    let startIndex = this.indexOf(startString, fromIndex);
    let endIndex = this.lastIndexOf(endString);

    if (startIndex === -1) return [""];

    if (endIndex === -1) return [this.substr(startIndex)];

    let ret = [];

    while (startIndex !== -1 && startIndex < endIndex) {
        let eIndex = this.indexOf(endString, startIndex);
        let stringBetween = this.substring(startIndex + startString.length, eIndex);
        ret.push(stringBetween);

        startIndex = this.indexOf(startString, eIndex + endString.length);
    }


    return ret;
};

/**
 * Check whatever number in range
 *
 * @param {Number} minValue
 * @param {Number} maxValue
 * @returns {boolean}
 */
Number.prototype.inRange = function (minValue, maxValue) {
    return this >= minValue && this <= maxValue;
};

/**
 * Split string into lines
 * @returns {String[]} lines
 */
String.prototype.lines = function () {
    return this.split(/\r?\n/);
};

/**
 * The padStart() method pads the current string with another string (repeated, if needed)
 * so that the resulting string reaches the given length.
 *
 * The padding is applied from the start (left) of the current string.
 *
 * @param {Number} targetLength The length of the resulting string once the current string has been padded.
 *                  If the value is lower than the current string's length,
 *                  the current string will be returned as is.
 *
 * @param {String} [padString] The string to pad the current string with.
 *                              If this string is too long to stay within the target length,
 *                              it will be truncated and the left-most part will be applied.
 *                              The default value for this parameter is " " (U+0020).
 *
 * @returns {String} A String of the specified length with the pad string applied from the start.
 */
String.prototype.padStart = String.prototype.padStart || function (targetLength, padString) {
        if (padString === undefined) padString = ' ';
        let str = this.toString();
        while (str.length < targetLength) {
            str = padString + str;
        }

        return str;
    };

/**
 * Firsts this object.
 *
 * @return  .
 */
Array.prototype.first = function () {
    return this[0];
};

/**
 * Lasts this object.
 *
 * @return  .
 */
Array.prototype.last = function () {
    return this[this.length - 1];
};

/**
 * Binary search.
 *
 * @param   target          Target for the.
 * @param   [compareFunction] The compare function.
 *
 * @return  .
 */
Array.prototype.binarySearch = function (target, compareFunction) {
    if (compareFunction === undefined) compareFunction = (x, y) => x - y;

    let low = 0;
    let high = this.length - 1;
    let mid;
    let element;
    while (low <= high) {
        mid = ((low + high) / 2) | 0;
        element = this[mid];
        let compareResult = compareFunction(element, target);

        if (compareResult === 0) {
            return mid;
        } else if (compareResult < 0) {
            low = mid + 1;
        } else if (compareResult > 0) {
            high = mid - 1;
        }
    }
    return -1;
};

/**
 * Convert Object to integer
 * @param obj
 * @returns {Number}
 */
window.intval = function (obj) {
    return parseInt(obj, 10);
};

/**
 * Convert Object to float
 * @param obj
 * @returns {Number}
 */
window.floatval = function(obj) {
    return parseFloat(obj);
};

/**
 * Determines the maximum value.
 *
 * @return  The maximum value.
 */
Array.prototype.max = function () {
    return Math.max.apply(null, this);
};

/**
 * Determines the minimum value.
 *
 * @return  The minimum value.
 */
Array.prototype.min = function () {
    return Math.min.apply(null, this);
};

/**
 *
 * @param {Number} deg degree
 */
Math.toRad = function (deg) {
    return deg / 180 * Math.PI;
};