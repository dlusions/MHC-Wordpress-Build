/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import essentials from 'yooessentials';
import Source from '../Source';
import SourceHelper from '../SourceHelper';

export const globalOption = {
    priority: 15,
    name: 'global',
    title: 'Global',
    match: (source) =>
        essentials.helpers.Dynamic.globalSources.find(({ name }) => name === source.query),
    matchNode: () => true,
    queries: () => essentials.helpers.Dynamic.globalSources
};

export const globalQueriesOption = {
    priority: 5,
    name: 'global-query',
    title: 'Global Query',
    inherit: true,
    match: (source) => source.query.startsWith(SourceHelper.globalKey),
    matchNode: () => true,
    queries: () =>
        (essentials.helpers.Dynamic?.queries || [])
            .map((query) => {
                const source = new Source(query.source);
                const field = source.querySchema;

                if (field) {
                    return {
                        name: asGlobalQueryKey(query.id),
                        type: field.type,
                        metadata: {
                            option: 'global-query',
                            group: Vue.i18n.t('Global'),
                            label:
                                query.name ||
                                Vue.i18n.t('Global Query (%label%)', {
                                    label: `<span class="uk-text-small">${source.baseOverview.replace(/ \/ /g, '/')}</span>`
                                }),
                            description: query.description || Vue.i18n.t('Custom Global Query')
                        }
                    };
                }
            })
            .filter(Boolean)
};

function asGlobalQueryKey(id) {
    return `${SourceHelper.globalKey}${id}`;
}
