/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { keymap, EditorView, StateField, showTooltip } from '@yooessentials/codemirror';
import { composedField } from '@yooessentials/dynamic/composed/state';
import { composableActiveField } from '../state';
import WidgetTooltip from './WidgetTooltip.vue';

const TooltipField = StateField.define({
    create: () => [],
    update(tooltips, tr) {
        const { Editor } = tr.state.field(composedField);
        const { activeWidget } = tr.state.field(composableActiveField);

        if (!activeWidget) {
            return [];
        }

        if (activeWidget.from === tooltips?.[0]?.pos) {
            return tooltips;
        }

        return getWidgetTooltip(Editor, activeWidget);
    },

    provide: (f) => showTooltip.computeN([f], (state) => state.field(f))
});

function getWidgetTooltip(Editor, activeWidget) {
    const pos = {
        from: activeWidget.widgets.at(0).$el?.cmView?.posAtStart,
        to: activeWidget.widgets.at(0).$el?.cmView?.posAtEnd
    };

    return [
        {
            pos: pos.from,
            arrow: true,
            above: true,
            strictSide: true,
            create: (view) => {
                const vm = new Vue({
                    parent: Editor,
                    extends: WidgetTooltip,
                    propsData: {
                        view,
                        widget: activeWidget,
                        value: activeWidget.value
                    }
                }).$mount();

                return {
                    dom: vm.$el,
                    offset: { x: 8, y: 0 },
                    destroy: () => {
                        vm.$destroy();
                    }
                };
            }
        }
    ];
}

const theme = EditorView.baseTheme({
    '.cm-tooltip-widget': {
        maxWidth: '100px',
        background: 'white !important',
        borderRadius: '3px',
        boxShadow: '0 5px 10px rgba(0,0,0,.2),0 0 5px rgba(0,0,0,.02)'
    },
    '.cm-tooltip-widget .uk-iconnav': {
        marginLeft: 0,
        flexWrap: 'nowrap',
        '& > * ': {
            padding: 0
        }
    }
});

const keymaps = keymap.of([
    {
        key: 'Mod-/',
        run: () => true
    },
    {
        key: 'Enter',
        run: ignoreEventIfDropActive
    },
    {
        key: 'ArrowUp',
        run: ignoreEventIfDropActive
    },
    {
        key: 'ArrowDown',
        run: ignoreEventIfDropActive
    },
    {
        key: 'ArrowLeft',
        run: ignoreEventIfDropActive
    },
    {
        key: 'ArrowRight',
        run: ignoreEventIfDropActive
    }
]);

export default [TooltipField, theme, keymaps];

function ignoreEventIfDropActive(view) {
    return view.state.field(TooltipField)?.active;
}
