/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { isEmpty } from 'uikit-util';
import { FieldsPanel } from '@yootheme/sidebar';
import Source from '../Source';

export default {
    name: 'YooessentialsSourceQueryArgs',

    inject: {
        $source: {
            default: null
        }
    },

    props: {
        field: Object,
        values: Object
    },

    render(h) {
        const { node } = this.$parent;
        const source = new Source(this.$source ?? node.source, node);
        const fields = source.querySchema?.metadata?.fields;

        if (source.isInheriting || isEmpty(fields)) {
            return null;
        }

        // a hint for the picker
        source.isNodeSource = true;

        // defaults set by the fields expect the namespace to exist
        if (source.values.query && !source.values.query?.arguments) {
            Vue.set(source.values.query, 'arguments', {});
        }

        return h(
            FieldsPanel,
            {
                source,
                key: source.query,
                props: {
                    panel: {
                        name: 'yooessentials-source-query-args',
                        fields
                    },
                    values: source.queryArguments
                },
                on: {
                    // update to onChange in vue 3
                    change(value, { name }) {
                        source.setQueryArgument(name, value);
                    }
                }
            },
            []
        );
    }
};
