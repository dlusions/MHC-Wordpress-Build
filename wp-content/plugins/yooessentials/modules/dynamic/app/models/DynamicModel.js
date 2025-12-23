/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import essentials from 'yooessentials';
import { uuid } from '@yooessentials/util';

import * as dynamicOptions from '../options';

export default {
    name: 'Dynamic',

    dynamicOptions: Object.values(dynamicOptions),

    computed: {
        queries: {
            get() {
                return essentials.helpers.Config.get('dynamic.queries', []);
            },

            set(values) {
                essentials.helpers.Config.set('dynamic.queries', values);
            },

            cache: false
        },

        globalSources() {
            const isGlobalSource = (field) => field?.metadata?.group === this.$t('Global');

            return api.builder.helpers.Schema.rootQueryFields.filter(isGlobalSource);
        }
    },

    methods: {
        getOption(option) {
            return this.$options.dynamicOptions.find((opt) => opt.name === option);
        },

        matchOptions(node, source, field) {
            let options = this.$options.dynamicOptions.sort(
                (a, b) => (b.priority || 0) - (a.priority || 0)
            );

            if (source) {
                return options.find((o) => o.match && o.match(source));
            }

            if (node) {
                options = options.filter((o) => o?.matchNode && o.matchNode(node));

                // allow to filter out options
                options = this.$trigger('essentialsDynamicOptionMatchNode', [options, field]) || [];
            }

            return options;
        },

        isGlobalQuery(id) {
            return Boolean(this.getGlobalQuery(id));
        },

        getGlobalQuery(id) {
            return this.queries.find((q) => q.id === id);
        },

        saveGlobalQuery(values) {
            const queries = [...this.queries];

            if (values.id) {
                queries[queries.findIndex(({ id }) => id === values.id)] = values;
            } else {
                values = {
                    ...values,
                    id: uuid(),
                    created_at: Date.now(),
                    created_on: essentials.version
                };

                queries.push(values);
            }

            this.queries = queries;

            return values;
        },

        removeGlobalQuery(query) {
            const queries = [...this.queries];
            queries.splice(queries.indexOf(query), 1);

            this.queries = queries;
        }
    }
};
