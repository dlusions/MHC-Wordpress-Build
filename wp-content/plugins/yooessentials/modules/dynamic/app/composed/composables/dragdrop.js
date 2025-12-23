/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { Decoration, EditorView, StateField, StateEffect } from '@yooessentials/codemirror';
import { storeTemp } from './input';

let draggedWidget = null;

const composableDraggingEffect = StateEffect.define({
    map: ({ from, to }, change) => ({ from: change.mapPos(from), to: change.mapPos(to) })
});

const draggingMark = Decoration.mark({ class: 'ye-cm-widget-dragged' });

const draggingField = StateField.define({
    create() {
        return Decoration.none;
    },
    update(dragged, tr) {
        dragged = dragged.map(tr.changes);

        for (let e of tr.effects) {
            if (e.is(composableDraggingEffect)) {
                if (e.value) {
                    return Decoration.set(draggingMark.range(e.value.from, e.value.to));
                } else {
                    return Decoration.none;
                }
            }
        }

        return dragged;
    },
    provide: (f) => EditorView.decorations.from(f)
});

const events = EditorView.domEventObservers({
    dragstart(e, view) {
        const widget = e.srcElement?.__vue__;

        if (widget?.type) {
            storeTemp(widget.id, widget.type, widget.value);
            draggedWidget = widget;
        }
    },
    drop(e, view) {
        setTimeout(() => {
            if (view === draggedWidget.view) {
                view.dispatch({
                    selection: {
                        anchor: draggedWidget.to
                    }
                });
            } else {
                view.dispatch({
                    selection: {
                        anchor: view.state.selection?.ranges?.[0]?.to
                    }
                });
            }

            draggedWidget = null;
        }, 1);
    }
});

export { composableDraggingEffect };

export default [draggingField, events];
