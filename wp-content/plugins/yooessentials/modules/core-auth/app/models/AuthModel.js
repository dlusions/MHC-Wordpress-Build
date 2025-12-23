/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import UIkit from 'uikit';
import http from '@yootheme/http';
import essentials from 'yooessentials';
import { uuid } from 'yooessentials-util';
import { includes } from 'uikit-util';
import { oAuth, oAuthWithEndpoll } from './oAuth';

export default {
    data: () => ({
        drivers: essentials.customizer?.auth_drivers || {}
    }),

    computed: {
        auths: {
            get() {
                return essentials.helpers.Config.get('auth.auths', []);
            },

            set(values) {
                essentials.helpers.Config.set('auth.auths', values);
            }
        }
    },

    methods: {
        find(id) {
            return this.auths.find((auth) => auth.id === id);
        },

        save(auth) {
            const auths = [...this.auths];
            const index = auths.findIndex((a) => a.id === auth.id);

            if (index >= 0) {
                // update
                auths[index] = auth;
            } else {
                // an auth could already have an id
                // TODO find a better way to deal with it
                Object.assign(auth, { id: auth.id || uuid(), timestamp: Date.now() });
                auths.push(auth);
            }

            this.auths = auths;

            return auth;
        },

        remove(values, predeleteEndpoint) {
            return new Promise((resolve, reject) => {
                if (!predeleteEndpoint) {
                    resolve({});
                    return;
                }

                return http(predeleteEndpoint)
                    .post({})
                    .json()
                    .catch((e) => reject(e?.json));
            })
                .then(() => {
                    const auths = [...this.auths];
                    const index = auths.findIndex((a) => a.id === values.id);

                    auths.splice(index, 1);
                    this.auths = auths;
                })
                .catch((e) => {
                    UIkit.notification(e || 'An error occurred.', 'danger');
                });
        },

        oauth(driver, scopes) {
            return new Promise((resolve, reject) => {
                try {
                    driver = this.getDriver(driver);
                } catch (e) {
                    reject(`Unknown Driver: ${driver}`);
                    return;
                }

                if (!scopes) {
                    reject('Scopes must be specified.');
                    return;
                }

                const endpoint = driver?.endpoints?.oauth;

                if (!endpoint) {
                    reject('Driver is missing oauth endpoint.');
                    return;
                }

                if (driver.poll) {
                    oAuthWithEndpoll({ driver, scopes, resolve, reject });
                } else {
                    oAuth({ endpoint, scopes, resolve, reject });
                }
            }).then((data) => {
                // if unknown granted scopes, assume all granted
                if (!data.scopes?.length) {
                    data.scopes = scopes;
                }

                return data;
            });
        },

        validateScopes(auth, scopes) {
            return scopes?.every((scope) => includes(auth.scopes, scope));
        },

        getDriver(driver) {
            if (!this.drivers[driver]) {
                throw Error(`Unknown Driver: ${driver}`);
            }

            return this.drivers[driver];
        }
    }
};
