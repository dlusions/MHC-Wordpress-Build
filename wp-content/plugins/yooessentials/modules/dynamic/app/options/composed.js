/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { uuid } from '@yooessentials/util';

export const composedOption = {
    priority: 95,
    name: 'composed',
    title: 'Composed',
    queries() {
        return [
            {
                name: 'composed',
                metadata: {
                    option: 'composed',
                    label: Vue.i18n.t('Composed'),
                    description: Vue.i18n.t('Compose content from multiple sources')
                }
            }
        ];
    },
    matchNode: (node) => node,
    match: (source) => source.isComposed,
    directResolve(node, field) {
        return {
            composed: {
                value: node.props?.[field] || '', // inherit static content from the field
                sources: {},
                new: true // hint
            }
        };
    }
};

export function toCompose(source) {
    const id = uuid(4).toLowerCase();

    return {
        composed: {
            value: `{{ sources.${id} }}`,
            sources: {
                [id]: source
            }
        }
    };
}
