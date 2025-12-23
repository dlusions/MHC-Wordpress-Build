/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { get } from '@yooessentials/util';
import {
    Text,
    Decoration,
    ViewPlugin,
    EditorView,
    EditorState,
    MatchDecorator
} from '@yooessentials/codemirror';
import {
    generateId,
    getSelectedText,
    onTransactionWithEffect
} from '@yooessentials/dynamic/composed/helper';
import { composedField } from '@yooessentials/dynamic/composed/state';
import { composableInsertEffect } from '../../state';
import WidgetType from '../../widget/WidgetType';
import SyntaxValidator from './Validator';
import ConditionWidget from './ConditionWidget.vue';

class ConditionType {
    static name = 'condition';
    static title = 'Condition';
    static storage = 'conditions';
    static icon = 'ye--composed-condition';
    static description = 'Insert a conditional expression';
    static panel = 'yooessentials-composed-condition';

    static validate(view) {
        return ConditionType.inserts.filter((i) => i.validate).some((i) => i.validate(view));
    }

    static validateInsert(view, insert) {
        const id = 'xxxx';
        const selectedText = getSelectedText(view.state);
        const insertable = insert.insertable(id, selectedText);

        const predicate = view.state.doc.replace(
            view.state.selection.main.from,
            view.state.selection.main.to,
            Text.of([insertable])
        );

        return SyntaxValidator(predicate.toString());
    }

    static countTotal(view) {
        const conditions = view.state.field(composedField)?.conditions ?? {};
        return Object.keys(conditions).length;
    }

    static inserts = [
        {
            title: 'Condition',
            description: 'Insert a condition',
            insertable: (id, selectedText = '') => `{{ conditions.${id} }}${selectedText}{{ / }}`,
            selectionOffset: '{{ conditions.xxxx }}'.length,
            validate: (view) => ConditionType.validateInsert(view, ConditionType.inserts[0])
        },
        {
            title: 'Condition/Else',
            description: 'Insert a condition with an else branch',
            insertable: (id, selectedText = '') =>
                `{{ conditions.${id} }}${selectedText}{{ else }}{{ / }}`,
            selectionOffset: '{{ conditions.xxxx }}'.length,
            validate: (view) => ConditionType.validateInsert(view, ConditionType.inserts[1])
        },
        {
            title: 'Else',
            description: 'Insert an else clause within a condition',
            insertable: () => '{{ else }}',
            validate: (view) => ConditionType.validateInsert(view, ConditionType.inserts[2])
        }
    ];
}

const Matcher = new MatchDecorator({
    regexp: /({{ conditions\.(\w{4}) }}).*?(?:({{ else }}).*?)??({{ \/ }})/g,
    decorate: (add, from, to, match, view) => {
        const { conditions } = view.state.field(composedField);
        const id = match[2];
        const offset = from;
        const value = get(conditions, id);

        if (!value) {
            return;
        }

        // opening tag
        add(
            from,
            from + match[1].length,
            Decoration.replace({
                widget: new WidgetType({
                    id,
                    value,
                    type: ConditionType,
                    component: ConditionWidget
                })
            })
        );

        // else tag (optional)
        if (match[3]) {
            from = offset + match[0].indexOf(match[3]);
            add(
                from,
                from + match[3].length,
                Decoration.replace({
                    widget: new WidgetType({
                        id,
                        satellite: 'else',
                        type: ConditionType,
                        component: ConditionWidget
                    })
                })
            );
        }

        // closing tag
        from = offset + match[0].indexOf(match[4]);
        add(
            from,
            from + match[4].length,
            Decoration.replace({
                widget: new WidgetType({
                    id,
                    satellite: '/',
                    type: ConditionType,
                    component: ConditionWidget
                })
            })
        );
    }
});

const handleInsert = onTransactionWithEffect(composableInsertEffect, (tr, { type, insert }) => {
    if (type.name !== ConditionType.name) {
        return;
    }

    const { conditions } = tr.state.field(composedField);
    const newSourceId = generateId();

    Vue.set(conditions, newSourceId, {});

    const selectedText = getSelectedText(tr.state);

    return {
        selection: { anchor: tr.state.selection.main.from + (insert.selectionOffset ?? 0) },
        changes: {
            from: tr.state.selection.main.from,
            to: tr.state.selection.main.to,
            insert: insert.insertable(newSourceId, selectedText)
        }
    };
});

const validate = EditorState.transactionFilter.of((tr) => {
    if (!SyntaxValidator(tr.newDoc.toString())) {
        return false;
    }

    return [tr];
});

const theme = EditorView.theme({
    '.ye-cm-widget-condition': {
        background: 'rgb(245, 232, 215)'
    }
});

export default ViewPlugin.fromClass(
    class {
        decorations;
        constructor(view) {
            this.decorations = Matcher.createDeco(view);

            view.state.field(composedField).types.push(ConditionType);
        }
        update(update) {
            this.decorations = Matcher.updateDeco(update, this.decorations);
        }
    },
    {
        decorations: (v) => v.decorations,
        provide: (plugin) => [
            theme,
            validate,
            handleInsert,
            EditorView.atomicRanges.of((view) => view.plugin(plugin).decorations || Decoration.none)
        ]
    }
);
