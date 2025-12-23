/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import essentials from 'yooessentials';
import { uuid } from 'yooessentials-util';

export default {
    name: 'StorageModel',

    data: () => ({
        adapters: essentials.customizer?.storage_adapters || {},
    }),

    computed: {
        storages: {
            get() {
                return essentials.helpers.Config.get('storages', []);
            },

            set(values) {
                essentials.helpers.Config.set('storages', values);
            },
        },
    },

    methods: {
        save(values) {
            const storages = [...this.storages];
            const adapter = this.adapters[values.adapter];

            if (!adapter) {
                throw Error(`Unknown Adapter: ${values.adapter}`);
            }

            if (values.id) {
                storages[storages.findIndex(({ id }) => id === values.id)] = values;
            } else {
                values = {
                    ...values,
                    id: uuid(),
                    _meta: {
                        created_at: Date.now(),
                        created_on: essentials.version,
                    },
                };

                storages.push(values);
            }

            this.storages = storages;

            return values;
        },

        remove(storage) {
            const storages = [...this.storages];
            storages.splice(storages.indexOf(storage), 1);

            this.storages = storages;
        },
    },
};
