/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { StateEffect, StateField } from '@yooessentials/codemirror';

export const composableInsertEffect = StateEffect.define();
export const composableHoverEffect = StateEffect.define();
export const composableActiveEffect = StateEffect.define();
export const composableCopyEffect = StateEffect.define();
export const composableDeleteEffect = StateEffect.define();

export const composableTypeHoverEffect = StateEffect.define();

export const composableActiveField = StateField.define({
    create: () => ({}),
    update(v, tr) {
        for (const e of tr.effects) {
            if (e.is(composableActiveEffect)) {
                v.activeWidget = e.value;
            }

            if (e.is(composableDeleteEffect)) {
                v.activeWidget = null;
            }

            if (e.is(composableHoverEffect)) {
                v.hoveredWidget = e.value;
            }
        }

        return v;
    }
});

export default [composableActiveField];
