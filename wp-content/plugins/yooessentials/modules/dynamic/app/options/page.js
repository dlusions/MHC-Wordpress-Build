/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { includes } from 'uikit-util';
import essentials from 'yooessentials';
import SchemaHelper from '../SchemaHelper';

export const pageOption = {
    priority: 20,
    name: 'page',
    title: 'Page',
    match: (source) => getPageSources().find(({ name }) => name === source.query),
    matchNode: () => getPageSources().length,
    queries: () => getPageSources()
};

function getPageSources() {
    const view = essentials.yoo.Builder?.view;
    const fields = SchemaHelper.rootQueryFields;

    if (!fields) {
        return [];
    }

    return fields?.filter(
        ({ metadata }) =>
            metadata?.group === Vue.i18n.t('Page') && includes(metadata?.view ?? [], view)
    );
}
