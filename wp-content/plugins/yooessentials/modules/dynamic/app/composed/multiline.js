/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import panel from './panel';
import {
    keymap,
    indentUnit,
    EditorView,
    Compartment,
    lineNumbers
} from '@yooessentials/codemirror';

const multilineCompartment = new Compartment();
const indent = ' '.repeat(4); // 4 spaces for indentation

const extensions = [
    panel, // must go after sources
    lineNumbers(),
    indentUnit.of(indent),
    EditorView.lineWrapping,
    EditorView.theme({
        '&': {
            padding: '0 !important'
        },
        '& .cm-scroller': {
            minHeight: '250px !important',
            resize: 'vertical'
        }
    }),
    keymap.of([
        {
            key: 'Enter',
            preventDefault: true
        },
        {
            key: 'Tab',
            preventDefault: true,
            run(view) {
                const { from } = view.state.selection.main;

                view.dispatch({
                    changes: {
                        from,
                        to: from,
                        insert: indent
                    },
                    selection: { anchor: from + indent.length }
                });
            }
        },
        {
            key: 'Shift-Tab',
            preventDefault: true,
            run(view) {
                const { from } = view.state.selection.main;
                const line = view.state.doc.lineAt(from);
                const lineText = line.text;

                if (lineText.startsWith(indent)) {
                    view.dispatch({
                        changes: {
                            from: line.from,
                            to: line.from + indent.length,
                            insert: ''
                        },
                        selection: { anchor: from - indent.length }
                    });
                }
            }
        }
    ])
];

export function toggleMultiline(view) {
    const on = multilineCompartment.get(view.state) === extensions;

    view.dispatch({
        effects: multilineCompartment.reconfigure(on ? [] : extensions)
    });
}

export default function (state) {
    return [multilineCompartment.of(state ? extensions : [])];
}
