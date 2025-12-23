/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { keymap, showTooltip, EditorView, StateField } from '@yooessentials/codemirror';
import { composedField } from '@yooessentials/dynamic/composed/state';
import InsertDrop from './InsertDrop.vue';

let showingOptions;

const insertDropField = StateField.define({
    create: () => [],
    update(tooltips, tr) {
        const { Editor } = tr.state.field(composedField);

        if (showingOptions && tooltips.length && !tr.docChanged && !tr.selection) {
            return tooltips;
        }

        if (!Editor.cm.hasFocus) {
            return [];
        }

        return getInsertDropTooltip(tr.state);
    },

    provide: (f) => showTooltip.computeN([f], (state) => state.field(f))
});

function getInsertDropTooltip(state) {
    const { Editor, types } = state.field(composedField);

    return state.selection.ranges.map((range) => {
        return {
            pos: range.to,
            strictSide: true,
            create: () => {
                const vm = new Vue({
                    parent: Editor,
                    extends: InsertDrop,
                    propsData: {
                        view: Editor.cm,
                        types
                    }
                }).$mount();

                vm.$watch('showOptions', (v) => {
                    showingOptions = v;

                    if (!v) {
                        Editor.cm.focus();
                    }
                });

                return {
                    dom: vm.$el,
                    destroy: () => {
                        vm.$destroy();
                        showingOptions = null;
                    }
                };
            }
        };
    });
}

export default [
    insertDropField,
    EditorView.theme({
        '.cm-tooltip-insert-drop > a': {
            width: '18px',
            height: '15px',
            margin: '5px 0 0 -9px',
            transform: 'rotate(90deg)'
        },
        '.cm-tooltip-insert-drop .cm-tooltip-arrow:after': {
            borderTopColor: '#e5e5e5 !important',
            borderBottomColor: '#e5e5e5 !important'
        }
    }),
    keymap.of([
        {
            key: 'Mod-/',
            run: () => true
        }
    ])
];
