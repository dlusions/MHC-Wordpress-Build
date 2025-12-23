/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import { isEmpty } from 'uikit-util';
import SchemaHelper from '../SchemaHelper';

export default function (FieldSourceQueryArgs) {
    return {
        name: 'SourceQueryArgsOverride',
        props: {
            field: Object,
            values: Object
        },
        render(h) {
            const { Source } = api.builder.helpers;
            return;

            // support for prop source
            this.node = this.values?.query ? { source: this.values } : this.$parent.node;

            const query = Source.getQuery(this.node);
            const fields = SchemaHelper.findField(query)?.metadata?.fields;

            if (isEmpty(fields)) {
                return null;
            }

            // init arguments namespace
            if (this.node?.source?.query && !this.node?.source?.query?.arguments) {
                Vue.set(this.node.source.query, 'arguments', {});
            }

            return h(FieldSourceQueryArgs);
        }
    };
}
