/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { get } from '@yooessentials/util';
import { DynamicDropdown } from '@yooessentials/dynamic/components';
import { composedField } from '@yooessentials/dynamic/composed/state';
import {
    generateId,
    getSelectedText,
    onTransactionWithEffect
} from '@yooessentials/dynamic/composed/helper';
import { ViewPlugin, EditorView, Decoration, MatchDecorator } from '@yooessentials/codemirror';
import SourceWidget from './SourceWidget.vue';
import WidgetType from '../../widget/WidgetType';
import { composableInsertEffect } from '../../state';

class SourceType {
    static name = 'source';
    static title = 'Source';
    static group = 'source';
    static storage = 'sources';
    static icon = 'ye--dynamic';
    static description = 'Insert a dynamic source';

    static validate(view) {
        return !getSelectedText(view.state);
    }

    static inserts = [
        {
            insertable: (id) => `{{ sources.${id} }}`,
            selectionOffset: '{{ sources.xxxx }}'.length,
            component: DynamicDropdown
        }
    ];

    static countTotal(view) {
        const { sources } = view.state.field(composedField);
        return Object.keys(sources).length;
    }
}

const Matcher = new MatchDecorator({
    regexp: /{{ sources\.(\w{4}) }}/g,
    decoration: (match, view) => {
        const id = match[1];
        const { sources } = view.state.field(composedField);

        const value = get(sources, id);

        if (!value) {
            return;
        }

        return Decoration.replace({
            widget: new WidgetType({
                id,
                value,
                type: SourceType,
                component: SourceWidget
            })
        });
    }
});

const handleInsert = onTransactionWithEffect(
    composableInsertEffect,
    (tr, { type, insert, data }) => {
        if (type.name !== SourceType.name) {
            return;
        }

        const { sources } = tr.state.field(composedField);
        const newSourceId = generateId();

        Vue.set(sources, newSourceId, data);

        const selectedText = getSelectedText(tr.state);

        return {
            selection: { anchor: tr.state.selection.main.from + (insert.selectionOffset ?? 0) },
            changes: {
                from: tr.state.selection.main.from,
                to: tr.state.selection.main.to,
                insert: insert.insertable(newSourceId, selectedText)
            }
        };
    }
);

const theme = EditorView.theme({
    '.ye-cm-widget-source': {
        background: 'rgb(235, 244, 255)'
    }
});

export default ViewPlugin.fromClass(
    class {
        decorations;
        constructor(view) {
            this.decorations = Matcher.createDeco(view);

            view.state.field(composedField).types.push(SourceType);
        }
        update(update) {
            this.decorations = Matcher.updateDeco(update, this.decorations);
        }
    },
    {
        decorations: (v) => v.decorations,
        provide: (plugin) => [
            theme,
            handleInsert,
            EditorView.atomicRanges.of((view) => view.plugin(plugin).decorations || Decoration.none)
        ]
    }
);
