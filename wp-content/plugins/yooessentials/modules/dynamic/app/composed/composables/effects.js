/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { composedField } from '../state';
import { onTransactionWithEffect } from '../helper';
import { composableHoverEffect, composableActiveEffect, composableTypeHoverEffect } from './state';

const isWidget = (w) => w.$options.name.endsWith('Widget');

const handleActiveEffect = onTransactionWithEffect(composableActiveEffect, (tr, widget) => {
    const { Editor } = tr.state.field(composedField);
    const widgets = Editor.$children.filter(isWidget);

    widgets.forEach((w) => (w.active = false));

    if (widget) {
        widgets.find((w) => w.id === widget.id).active = true;
    }
});

const handleHoverEffect = onTransactionWithEffect(composableHoverEffect, (tr, widget) => {
    const { Editor } = tr.state.field(composedField);
    const widgets = Editor.$children.filter(isWidget);

    widgets.forEach((w) => (w.highlighted = false));

    if (widget) {
        widgets.find((w) => w.id === widget.id).highlighted = true;
    }
});

const handleTypeHoverEffect = onTransactionWithEffect(composableTypeHoverEffect, (tr, type) => {
    const { Editor } = tr.state.field(composedField);
    const widgets = Editor.$children.filter(isWidget);

    widgets.forEach((w) => (w.highlighted = false));

    if (type) {
        widgets.filter((w) => w.type.name === type.name).forEach((w) => (w.highlighted = true));
    }
});

export default [handleHoverEffect, handleActiveEffect, handleTypeHoverEffect];
