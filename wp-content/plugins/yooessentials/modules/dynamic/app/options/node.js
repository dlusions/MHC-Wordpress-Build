/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import Source from '../Source';
import SourceHelper from '../SourceHelper';

/**
 * node
 */
export const nodeOption = {
    priority: 30,
    name: 'node',
    title: 'Node',
    inherit: true,
    match: (source) => source.isNodeInheriting,
    matchNode: (node) => new Source(node.source, node).query,
    query: ({ prop }) => new Source(prop).query,
    queries: ({ node }) => {
        const source = new Source(node.source, node);

        if (source.querySchema) {
            return [
                {
                    name: SourceHelper.nodeKey,
                    type: source.querySchema.type,
                    metadata: {
                        option: 'node',
                        group: 'Inherit',
                        label: Vue.i18n.t('Node - %type% (%label%)', {
                            type: source.nodeType.title,
                            label: `<span class="uk-text-small">${source.baseOverview.replace(/ \/ /g, '/')}</span>`
                        }),
                        description: Vue.i18n.t('Current node source')
                    }
                }
            ];
        }

        return [];
    }
};

/**
 * parent
 */
export const parentOption = {
    priority: 30,
    name: 'parent',
    title: 'Parent',
    inherit: true,
    match: (source) => source.isParentInheriting,
    matchNode: (node) => Boolean(new Source({}, node).parentSource),
    query: ({ prop }) => ({ ...(prop?.query || {}), name: SourceHelper.parentKey }),
    queries: ({ node }) => {
        const source = new Source(node.source, node);
        const parent = source.parentSource;

        if (parent.querySchema) {
            return [
                {
                    name: SourceHelper.parentKey,
                    type: parent.querySchema.type,
                    metadata: {
                        option: 'parent',
                        group: 'Inherit',
                        label: Vue.i18n.t('Parent - %type% (%label%)', {
                            type: parent.nodeType.title,
                            label: `<span class="uk-text-small">${parent.baseOverview.replace(/ \/ /g, '/')}</span>`
                        }),
                        description: Vue.i18n.t('Closest node source')
                    }
                }
            ];
        }

        return [];
    }
};
