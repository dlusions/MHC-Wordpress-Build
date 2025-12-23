/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import http from '@yootheme/http';
import essentials from 'yooessentials';
import { uuid } from '@yooessentials/util';

export default {
    name: 'SourcesModel',

    data: () => ({
        providers: essentials.customizer?.source_providers || {}
    }),

    computed: {
        sources: {
            get() {
                return essentials.helpers.Config.get('source.sources', []);
            },

            set(values) {
                essentials.helpers.Config.$watch(
                    'values',
                    () => {
                        // must be executed using the event,
                        // is too early otherwise
                        this.rebuildSourcesSchema();
                    },
                    { deep: true }
                );

                essentials.helpers.Config.set('source.sources', values);
            }
        }
    },

    methods: {
        save(values) {
            const sources = [...this.sources];
            const provider = this.providers[values.provider];

            if (!provider) {
                throw Error(`Unknown Provider: ${values.provider}`);
            }

            if (values.id) {
                sources[sources.findIndex(({ id }) => id === values.id)] = values;
            } else {
                sources.push({
                    ...values,
                    id: uuid(),
                    _meta: {
                        ...(values?._meta || {}),
                        created_at: Date.now(),
                        created_on: essentials.version
                    }
                });
            }

            this.sources = sources;

            return values;
        },

        remove(source) {
            const sources = [...this.sources];
            sources.splice(sources.indexOf(source), 1);

            this.sources = sources;
        },

        rebuildSourcesSchema() {
            Vue.events.trigger('clearCache', [], true).then(() => {
                return http('yooessentials/source/rebuild-schema')
                    .post({})
                    .json(({ schema }) => {
                        Vue.set(api.builder, 'schema', schema);
                    });
            });
        }
    }
};
