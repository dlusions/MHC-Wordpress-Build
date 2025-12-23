/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { EditorState } from '@codemirror/state';
import { keymap, EditorView } from '@codemirror/view';
import { defaultKeymap } from '@codemirror/commands';

export function createEditor({ parent, value = '', setup = [], onChange, selection }) {
    const theme = EditorView.theme({
        '&.cm-focused': {
            outline: 'none'
        },
        '.cm-scroller': {
            fontFamily: 'inherit'
        }
    });

    return new EditorView({
        parent,
        state: EditorState.create({
            doc: value,
            selection,
            extensions: [
                setup,
                theme,
                keymap.of(defaultKeymap),
                EditorView.updateListener.of((update) => {
                    if (onChange && update.docChanged) {
                        onChange(update.state.doc.toString(), update);
                    }
                })
            ]
        })
    });
}
