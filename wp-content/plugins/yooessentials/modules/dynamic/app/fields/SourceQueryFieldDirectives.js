/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import { FieldsPanel } from '@yootheme/sidebar';
import Source from '../Source';

export default {
    name: 'YooessentialsSourceQueryFieldDirectives',

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

        if (!source.queryFieldSchema) {
            return null;
        }

        const directives = source.queryFieldSchema.metadata?.directives || ['slice'];
        const fields = directives
            .filter((directive) => api.builder?.sources?.directives?.[directive])
            .map((directive) => ({
                type: 'source-field-directive',
                name: directive,
                ...api.builder.sources.directives[directive]
            }));

        if (!fields.length) {
            return null;
        }

        return h(
            FieldsPanel,
            {
                props: { panel: { fields } },
                key: `directives-${source.query}-${source.queryField}`
            },
            []
        );
    }
};
