/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { FieldsPanel } from '@yootheme/sidebar';
import Source from '../Source';

export default {
    name: 'YooessentialsSourceQueryFieldDirective',

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
        const { node } = this.$parent.$parent.$parent;
        const source = new Source(this.$source ?? node.source, node);

        if (!source.queryFieldSchema) {
            return null;
        }

        const { name: directive, fields } = this.field;

        return h(
            FieldsPanel,
            {
                props: {
                    panel: {
                        fields
                    },
                    values: source.getQueryFieldDirectiveArgs(directive)
                },
                //update to onChange in vue 3
                on: {
                    change(value, { name }) {
                        source.setQueryFieldDirectiveArg(directive, name, value);
                    }
                },
                key: `directive-${source.query}-${source.queryField}`
            },
            []
        );
    }
};
