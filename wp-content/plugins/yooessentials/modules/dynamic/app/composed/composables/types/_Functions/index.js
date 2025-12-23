/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { get, uuid } from '@yooessentials/util';
import {
    ViewPlugin,
    EditorState,
    EditorView,
    Decoration,
    MatchDecorator
} from '@yooessentials/codemirror';
import Widget from '../widget/Widget';
import SyntaxValidator from './Validator';
import { composedField, functionInsertEffect } from '../state';
import { onTransactionWithEffect } from '../../helper';

// import MaxFunction from './Max';
import IfFn from './If';
import MaxFn from './Max';
import UpperFn from './Upper';

const Functions = [IfFn, MaxFn, UpperFn].map((type) => {
    type = {
        ...type,
        group: 'function'
    };

    const Matcher = new MatchDecorator({
        regexp: type.regexp,
        decorate: (add, from, to, match, view) => {
            const id = `${type.name}_${from}`;

            // {open}
            add(
                from + match[0].indexOf(match.groups.open),
                from + match[0].indexOf(match.groups.open) + match.groups.open.length,
                Decoration.replace({
                    widget: new Widget({
                        id,
                        type,
                        main: true,
                        label: `${type.title} (`
                    })
                })
            );

            // value separators {;}
            if (type.separator) {
                let index = -1;
                const sep = type.separator;

                while ((index = match[0].indexOf(sep, index + 1)) !== -1) {
                    add(
                        from + index,
                        from + index + sep.length,
                        Decoration.replace({
                            widget: new Widget({ id, type, label: sep })
                        })
                    );
                }
            }

            // {/close}
            add(
                from + match[0].indexOf(match.groups.close),
                from + match[0].indexOf(match.groups.close) + match.groups.close.length,
                Decoration.replace({
                    widget: new Widget({ id, type, label: ')' })
                })
            );
        }
    });

    return ViewPlugin.fromClass(
        class {
            decorations;
            constructor(view) {
                this.decorations = Matcher.createDeco(view);

                view.state.field(composedField).types.push({ type, group: 'function' });
            }
            update(update) {
                this.decorations = Matcher.updateDeco(update, this.decorations);
            }
        },
        {
            decorations: (v) => v.decorations,
            provide: (plugin) => [
                EditorView.atomicRanges.of(
                    (view) => view.plugin(plugin).decorations || Decoration.none
                )
            ]
        }
    );
});

const handleFunctionInsert = onTransactionWithEffect(functionInsertEffect, (tr, { type }) => {
    if (type.group !== 'function') {
        return;
    }

    return {
        changes: {
            from: tr.state.selection.main.from,
            to: tr.state.selection.main.to,
            insert: type.insertable()
        }
    };
});

export default [
    ...Functions,
    handleFunctionInsert,
    EditorView.theme({
        '.ye-cm-widget-function': {
            textTransform: 'lowercase',
            // background: 'rgb(222, 222, 222, 0.9)'
            background: 'rgb(215, 245, 220, 0.9) !important'
        }
    })
];
