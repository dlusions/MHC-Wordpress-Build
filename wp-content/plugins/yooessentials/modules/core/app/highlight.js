/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { $$, append, css, isDocument, positionAt } from 'uikit-util';

export function add(selector, context) {
    for (const target of $$(selector, context)) {
        const element = append(toBody(context), '<div class="yo-hover"></div>');
        css(element, {
            width: target.offsetWidth,
            height: target.offsetHeight
        });
        positionAt(element, target, {
            attach: {
                element: ['top', 'left'],
                target: ['top', 'left']
            }
        });
    }
}

export function remove(context) {
    for (const element of $$('> .yo-hover', toBody(context))) {
        element.remove();
    }
}

function toBody(context) {
    return (isDocument(context) ? context : context?.ownerDocument)?.body;
}
