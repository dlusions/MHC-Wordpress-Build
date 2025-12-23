/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { isEmpty } from 'uikit-util';
import { FieldsPanel } from '@yootheme/sidebar';
import Source from '../Source';

export default {
    name: 'YooessentialsSourceQueryFieldArgs',

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
        const fields = source.queryFieldSchema?.metadata?.arguments;

        if (source.isInheriting || isEmpty(fields)) {
            return null;
        }

        // a hint for the picker
        source.isNodeSource = true;

        return h(
            FieldsPanel,
            {
                source,
                props: {
                    panel: {
                        name: 'yooessentials-source-query-field-args',
                        fields
                    },
                    values: source.queryFieldArguments
                },
                //update to onChange in vue 3
                on: {
                    change(value, { name }) {
                        source.setQueryFieldArgument(name, value);
                    }
                },
                key: `args-${source.query}-${source.queryField}`
            },
            []
        );
    }
};
