/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { uuid } from '@yooessentials/util';
import { EditorState } from '@yooessentials/codemirror';

export const generateId = () => uuid(4).toLowerCase();
export const findEffect = (tr, effect) => tr.effects.find((e) => e.is(effect));
export const findAnnotation = (tr, annotation) => tr.annotations.find((a) => a.type === annotation);

export const forceViewRefresh = (view) => {
    const index = 0;

    view.dispatch({
        changes: [
            {
                from: index,
                insert: ' '
            }
        ]
    });

    setTimeout(() => {
        view.dispatch({
            changes: [
                {
                    from: index,
                    to: index + 1
                }
            ]
        });
    }, 10);
};

export function onTransactionWithEffect(effect, cb) {
    return EditorState.transactionFilter.of((tr) => {
        const matched = findEffect(tr, effect);

        if (matched) {
            return [tr, cb(tr, matched?.value)].filter(Boolean);
        }

        return [tr];
    });
}

export function matchAll(doc, regexp) {
    let match;
    const matches = [];

    while ((match = regexp.exec(doc)) !== null) {
        matches.push(match[1]);
    }

    return matches;
}

export function getExitBorder(element, e) {
    // Get the element's bounding rectangle
    const rect = element.getBoundingClientRect();

    // Get the pointer coordinates
    const x = e.clientX;
    const y = e.clientY;

    // Calculate distances to each border
    const distToTop = Math.abs(y - rect.top);
    const distToBottom = Math.abs(y - rect.bottom);
    const distToLeft = Math.abs(x - rect.left);
    const distToRight = Math.abs(x - rect.right);

    // Determine which border is closest to the pointer
    const minDist = Math.min(distToTop, distToBottom, distToLeft, distToRight);

    let exitBorder;
    if (minDist === distToTop) exitBorder = 'top';
    else if (minDist === distToBottom) exitBorder = 'bottom';
    else if (minDist === distToLeft) exitBorder = 'left';
    else exitBorder = 'right';

    return exitBorder;
}

export function getLineNumber(view) {
    const selection = view.state.selection.main;
    return view.state.doc.lineAt(selection.head).number;
}

export function getSelectedText(state) {
    return state.sliceDoc(state.selection.main.from, state.selection.main.to);
}
