/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import http from '@yootheme/http';
import { isEmpty, isObject } from 'uikit-util';
import essentials from 'yooessentials';

export function oAuth({ endpoint, scopes, resolve, reject }) {
    const endpointUrl = getOauthEndpoint(endpoint, scopes);

    const win = window.open(endpointUrl, '');

    window.addEventListener(
        'message',
        (e) => {
            if (!endpointUrl.match(new RegExp(e.origin))) {
                return;
            }

            const data = isObject(e.data) ? e.data : {};

            if (!data.id) {
                reject('Something went wrong, please try again.');
                return;
            }

            resolve(data);
        },
        false
    );

    onWinClose(win, () => {
        reject('Sorry, something went wrong.');
    });
}

export function oAuthWithEndpoll({ driver, scopes, reject, resolve }) {
    const oauthEndpoint = driver?.endpoints?.oauth;
    const pollEndpoint = driver?.endpoints?.poll;
    const idEndpoint = 'yooessentials/auth/generate-id';

    http(idEndpoint.toString())
        .post({})
        .json((response) => {
            const id = response.id;

            if (!id) {
                reject('Driver id endpoint not resolved.');
                return;
            }

            const oauthEndpointUrl = getOauthEndpoint(oauthEndpoint, scopes, id);
            const pollEndpointUrl = getOauthEndpoint(pollEndpoint, scopes, id);

            const win = window.open(oauthEndpointUrl, '');

            onWinClose(win, () => {
                let count = 0;

                const interval = setInterval(() => {
                    if (count > 10) {
                        clearInterval(interval);
                        reject('Sorry, something went wrong.');
                    }

                    http(pollEndpointUrl)
                        .get()
                        .json((response) => {
                            if (!isEmpty(response)) {
                                clearInterval(interval);
                                resolve(response);
                            }
                        });

                    count++;
                }, 1500);
            });
        });
}

function getOauthEndpoint(endpoint, scopes, id) {
    const url = new URL(endpoint);
    const params = url.searchParams;

    params.set('scopes', scopes);
    params.set('yooessentialsVersion', essentials.version);

    id && params.set('id', id);

    url.search = '?' + params.toString();

    return url.toString();
}

function onWinClose(win, cb) {
    const interval = setInterval(() => {
        if (win?.closed) {
            clearInterval(interval);
            cb();
        }
    }, 400);
}
