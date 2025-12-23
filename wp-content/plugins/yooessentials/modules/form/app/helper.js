/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import essentials from 'yooessentials';
import { flattenChildren } from '@yooessentials/util';

export function isFormArea(node) {
    return node?.props?.yooessentials_form?.state;
}

export function isFormField(node) {
    return Boolean(essentials.yoo.Builder.types[node?.type]?.submittable);
}

export function isElement(node) {
    return !isFormArea(node) && Boolean(essentials.yoo.Builder.types[node?.type]?.element);
}

export function hasFormAreas(node) {
    return Boolean(getFormAreas(node).length);
}

export function hasFormFields(node) {
    return Boolean(getFormFields(node, essentials.yoo.Builder).length);
}

export function getFormAreas(node, includeFragments = true) {
    return flattenChildren(node, includeFragments).filter(isFormArea);
}

export function getFormFields(node) {
    return flattenChildren(node).filter(isFormField);
}

export function getClosestFormArea(node) {
    node = essentials.yoo?.Builder?.adjacentOf || essentials.yoo?.Builder?.parent(node);

    while (node && !isFormArea(node)) {
        node = essentials.yoo?.Builder?.parent(node);
    }

    return node;
}
