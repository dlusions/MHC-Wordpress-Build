/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { Annotation, StateEffect, StateField } from '@yooessentials/codemirror';

export const focusEffect = StateEffect.define();
export const panelStatus = StateEffect.define();
export const freezeEffect = StateEffect.define();

export const silentUpdateAnnotation = Annotation.define();

export const composedField = StateField.define({
    create: () => ({}),
    update(v) {
        return v;
    }
});
