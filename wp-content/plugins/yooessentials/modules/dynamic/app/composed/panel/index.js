/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { showPanel, Facet, StateEffect, EditorView } from '@yooessentials/codemirror';
import Panel from './Panel.vue';
import { composedField, panelStatus } from '../state';

const addPanelItems = StateEffect.define();
const setPanelStatus = StateEffect.define();

const panelItem = Facet.define({
    combine: (v) => v.flat()
});

export { setPanelStatus, addPanelItems, panelItem };

function createPanel(view) {
    const { types, sources, Editor } = view.state.field(composedField);

    const vm = new Vue({
        parent: Editor,
        extends: Panel,
        propsData: {
            view,
            types,
            sources
        }
    }).$mount();

    return vm.$el;
}

export default [
    showPanel.of((view) => ({
        dom: createPanel(view),
        update(update) {
            const effects = update.transactions.map((tr) => tr.effects).flat();
            const status = effects.find((e) => e.is(panelStatus));

            if (status) {
                this.dom.__vue__.setStatus(status?.value);
            }
        },
        destroy() {
            this.dom.__vue__.$destroy();
        }
    })),
    EditorView.theme({
        '.cm-panels': {
            position: 'inherit',
            zIndex: '0'
        },
        '.cm-panels-bottom': {
            padding: '8px',

            '& .ye-cm-panel-label': {
                height: '16px',
                padding: '0 4px',
                lineHeight: 'inherit',
                fontSize: '9px',
                borderRadius: '3px',
                outline: 'inherit',
                display: 'inline-flex',
                alignItems: 'center',
                cursor: 'default',
                border: '1px solid #cacaca',
                color: '#777',
                overflow: 'visible',
                boxSizing: 'border-box',
                fontFamily: 'Montserrat, "Helvetica Neue", Arial, sans-serif',

                '& .uk-button.uk-disabled': {
                    '&, & .uk-text-meta': {
                        color: '#bbb'
                    }
                }
            }
        }
    })
];
