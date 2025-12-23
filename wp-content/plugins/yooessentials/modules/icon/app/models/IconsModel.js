/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import UIkit from 'uikit';
import http from '@yootheme/http';
import essentials from 'yooessentials';

const $cache = {};

export default {
    name: 'IconsModel',

    data: () => ({
        collections: Object.values(essentials.customizer?.icon_collections ?? {}) || []
    }),

    methods: {
        add(icons) {
            UIkit.icon.add(icons);
            return Object.keys(icons);
        },

        addCollection(collection) {
            return http('yooessentials/icon/collection/add')
                .post({ name: collection.name })
                .json((collections) => {
                    this.collections = Object.values(collections);
                })
                .catch(() => {
                    UIkit.modal.alert(`
                        <div class="uk-text-lead">Ups! The collection installation has failed.</div>
                        <p>It could be the server blocking the download, in which case you could try <a href="${collection.package}" target="_blank">downloading</a> the collection and installing it manually following <a href="https://zoolanders.com/docs/essentials-for-yootheme-pro/icons/custom-collections" target="_blank">this guide</a>.</p>
                    `);
                });
        },

        removeCollection(collection) {
            return http('yooessentials/icon/collection/remove')
                .post({ name: collection.name })
                .json((collections) => {
                    this.collections = Object.values(collections);
                })
                .catch((e) => {
                    UIkit.notification(e?.json, 'danger');
                });
        },

        fetch(params = {}, force = false) {
            const requestId = JSON.stringify(params);
            const cached = $cache[requestId];

            return new Promise((resolve, reject) => {
                if (!force && cached) {
                    resolve(cached);
                    return;
                }

                http('yooessentials/icon/list')
                    .post(params)
                    .json((response) => {
                        response.data = this.add(response.data);
                        $cache[requestId] = response;

                        resolve(response);
                    })
                    .catch(reject);
            });
        }
    }
};
