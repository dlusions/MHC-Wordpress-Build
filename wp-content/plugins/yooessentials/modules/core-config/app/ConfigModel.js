/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import UIkit from 'uikit';
import api from '@yootheme/api';
import http from '@yootheme/http';
import merge from 'lodash-es/merge';
import essentials from 'yooessentials';
import { deepCopy, get, set } from 'yooessentials-util';

export default {
    name: 'EssentialsConfig',

    data: () => ({
        values: deepCopy(essentials?.customizer?.config || {}),
        dirty: false
    }),

    watch: {
        values: {
            handler(values) {
                const valuesMatchConfig =
                    JSON.stringify(values) === JSON.stringify(essentials?.customizer?.config || {});

                essentials.yoo.Config.dirty = this.dirty = !valuesMatchConfig;
            },

            deep: true
        }
    },

    created() {
        // a hint could be provided within a php script
        // that the config has been updated during runtime
        // in such a case we need to remove the updated flag
        // which will be persisted on save
        if (this.values.updated) {
            const values = deepCopy(this.values);
            delete values.updated;
            this.values = values;
        }

        essentials.helpers.Config = this;
    },

    events: {
        loadPreview: {
            handler(e, values) {
                const isJoomla = api.config.platform === 'joomla';

                // it is being cancelled?
                // joomla workaround to reset a config
                if (isJoomla && !essentials.yoo.Config.dirty) {
                    if (this.dirty) {
                        this.cancel();
                    }

                    return;
                }

                const config = deepCopy(this.values);

                // joomla
                if (e?.params[0]) {
                    e.params[0].yooessentials = config;
                }

                // wp
                if (values?.config) {
                    values.config.yooessentials = config;
                }
            },

            priority: 50
        },

        saveConfig() {
            this.persist();
            essentials.customizer.config = deepCopy(this.values);
            this.dirty = false;
        },

        'yooessentials-prerequest'(e) {
            if (!this.dirty) {
                return;
            }

            e.params[0].config = deepCopy(this.values);
        }
    },

    methods: {
        get(index, _default) {
            return get(this.values, index, _default);
        },

        set(index, value) {
            set(this.values, index, value);
        },

        add(_values) {
            merge(this.values, deepCopy(_values));
        },

        replace(_values) {
            this.values = deepCopy(_values);
        },

        cancel() {
            this.replace(essentials.customizer.config);
        },

        import(config) {
            http('yooessentials/config/import')
                .post({ config })
                .json((data) => {
                    this.replace(data);
                });
        },

        export() {
            const config = deepCopy(this.values);

            return http('yooessentials/config/export').post({ config }).json();
        },

        persist() {
            const config = deepCopy(this.values);
            this.$trigger('saveYooessentialsConfig', config);

            return http('yooessentials/config/save')
                .post({ config })
                .text()
                .catch((e) => {
                    UIkit.notification(e?.json, 'danger');
                });
        }
    }
};
