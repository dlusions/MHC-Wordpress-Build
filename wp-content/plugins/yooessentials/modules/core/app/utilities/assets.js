/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import memoize from 'lodash-es/memoize';

export const Asset = function (assets) {
    const promises = [];

    Object.keys(assets).forEach((type) => {
        if (!Asset[type]) {
            return;
        }

        for (const asset of Array.isArray(assets[type]) ? assets[type] : [assets[type]]) {
            if (asset) {
                promises.push(Asset[type](asset));
            }
        }
    });

    return Promise.all(promises);
};

Object.assign(Asset, {
    css: memoize((url) => {
        return new Promise((resolve, reject) => {
            const link = document.createElement('link');

            link.onload = () => resolve(url);
            link.onerror = () => reject(url);
            link.rel = 'stylesheet';
            link.href = url;

            document.head.appendChild(link);
        });
    }),

    js: memoize((url) => {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');

            script.onload = () => resolve(url);
            script.onerror = () => reject(url);
            script.src = url;

            document.head.appendChild(script);
        });
    }),

    image: memoize((url) => {
        return new Promise((resolve, reject) => {
            const img = new Image();

            img.onload = () => resolve(url);
            img.onerror = () => reject(url);
            img.src = url;
        });
    })
});
