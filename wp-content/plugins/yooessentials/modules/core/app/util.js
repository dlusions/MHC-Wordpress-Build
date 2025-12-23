/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { isArray, isObject, isUndefined } from 'uikit-util';

let _set;
let _storage;

export default function ({ set }) {
    _set = set;
}

/**
 * Simple array reindexing
 */
export function reindex(array, from, to) {
    const node = array[from];

    if (to < from && to >= 0) {
        array.splice(from, 1);
        array.splice(to, 0, node);
    } else if (to > from && to < array.length) {
        array.splice(to + 1, 0, node);
        array.splice(from, 1);
    }
}

/**
 * Flatten node children recursively
 */
export function flattenChildren(node, includeFragments = true) {
    return (node?.children || []).reduce((carry, child) => {
        if (child.type === 'fragment' && !includeFragments) {
            return carry;
        }

        return [...carry, child, ...flattenChildren(child, includeFragments)];
    }, []);
}

/**
 * Finds Vue closeset parent instance with a custom filter
 */
export function vueClosestParent(instance, filter) {
    do {
        instance = instance?.$parent;
    } while (instance && !filter(instance));

    return instance;
}

// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
export function debounce(func, wait, immediate) {
    let timeout;

    return function () {
        const context = this;
        const args = arguments;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;

        clearTimeout(timeout);
        timeout = setTimeout(later, wait);

        if (callNow) func.apply(context, args);
    };
}

/**
 * Get a value from object with deep path
 */
export function get(obj, key, def) {
    const parts = isArray(key) ? key : key.split('.');

    for (let i = 0; i < parts.length; i++) {
        if (isObject(obj) && !isUndefined(obj[parts[i]])) {
            obj = obj[parts[i]];
        } else {
            return def;
        }
    }

    return obj;
}

/**
 * Set a value into an object with deep path
 */
export function set(obj, key, val) {
    const parts = isArray(key) ? key : key.split('.');

    while (parts.length > 1) {
        const part = parts.shift();

        if (!isObject(obj[part])) {
            _set(obj, part, {});
        }

        obj = obj[part];
    }

    return _set(obj, parts.shift(), val);
}

/**
 * Key an array of objects
 */
export function keyBy(obj, key) {
    return obj.reduce((carry, item) => ({ ...carry, [get(item, key)]: item }), {});
}

/**
 * Unkey
 */
export function unkeyWith(obj, key) {
    return Object.keys(obj).map((k) => ({ [key]: k, ...obj[k] }));
}

/**
 * Group an array of objects
 */
export function groupBy(obj, key) {
    return obj.reduce(
        (carry, item) => ({ ...carry, [get(item, key)]: [...(carry[get(item, key)] || []), item] }),
        {}
    );
}

/**
 * UIIG generator adapted from Math.uuid.js (v1.4)
 * http://www.broofa.com
 * Copyright (c) 2010 Robert Kieffer
 * Dual licensed under the MIT and GPL licenses.
 */
export function uuid(len = 6) {
    const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
    const uuid = new Array(len);
    let rnd = 0;
    let r;

    for (let i = 0; i < len; i++) {
        if (i === 8 || i === 13 || i === 18 || i === 23) {
            uuid[i] = '-';
        } else if (i === 14) {
            uuid[i] = '4';
        } else {
            if (rnd <= 0x02) rnd = (0x2000000 + Math.random() * 0x1000000) | 0;
            r = rnd & 0xf;
            rnd = rnd >> 4;
            uuid[i] = chars[i === 19 ? (r & 0x3) | 0x8 : r];
        }
    }

    return uuid.join('');
}

/*
 * Session storage, with workaround for Safari's private
 * browsing mode and accessing sessionStorage in Blink
 */
try {
    const key = '__test__';

    _storage = window.sessionStorage || {};
    _storage[key] = 1;
    delete _storage[key];
} catch (e) {
    _storage = {};
}

export const storage = _storage;

/**
 * Semversion comparison
 * @returns -1 (a < b), 0 (a = 0), 1 (a > b)
 */
export function semverCompare(a, b) {
    if (!a || !b) {
        return 0;
    }

    const regExStrip0 = /(\.0+)+$/;
    const segmentsA = a.replace(regExStrip0, '').split('.');
    const segmentsB = b.replace(regExStrip0, '').split('.');
    const l = Math.min(segmentsA.length, segmentsB.length);

    let diff;
    for (let i = 0; i < l; i++) {
        diff = parseInt(segmentsA[i], 10) - parseInt(segmentsB[i], 10);
        if (diff) {
            return diff < 0 ? -1 : 1;
        }
    }

    return segmentsA.length - segmentsB.length;
}

/**
 * Deep copy of an object
 */
export function deepCopy(obj) {
    return JSON.parse(JSON.stringify(obj));
}

/**
 * Get unique values of array
 */
export function unique(arr) {
    return arr.filter((value, index, self) => self.indexOf(value) === index);
}

/**
 * Download file
 */
export function download(filename, text) {
    const url = URL.createObjectURL(
        new Blob([new TextEncoder().encode(text).buffer], { type: 'application/octet-stream' })
    );

    const element = document.createElement('a');

    element.setAttribute('href', url);
    element.setAttribute('download', filename);
    element.click();

    URL.revokeObjectURL(url);
}

export function downloadUrl(filename, url) {
    const element = document.createElement('a');

    element.setAttribute('href', url);
    element.setAttribute('download', filename);
    element.click();

    URL.revokeObjectURL(url);
}

/**
 * Read files contents
 */
export function readFile(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = resolve;
        reader.onerror = reject;

        reader.readAsText(file);
    });
}

/**
 * Make sure obj is an array
 */
export function arrify(obj) {
    return isArray(obj) ? obj : [obj];
}

/**
 * Normalize a string for search comparison.
 *
 * @param {string} string
 * @return {string}
 */
export function normalizeString(string) {
    return string
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

/**
 * @param {string} string
 * @param {string} value
 */
export function stringIncludes(string, value) {
    return normalizeString(string).includes(normalizeString(value ?? ''));
}
