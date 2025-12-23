/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import { FormAreaButton, FormAreaIcon } from './components';
import {
    isElement,
    isFormArea,
    isFormField,
    hasFormAreas,
    hasFormFields,
    getFormFields,
    getFormAreas,
    getClosestFormArea
} from './helper';

Vue.events.on(
    'layoutButtons',
    ({ result = [] }, { node } = {}) => {
        if (isFormArea(node)) {
            return [...result, { component: FormAreaButton, node }];
        }

        return result;
    },
    -10
);

Vue.events.on('statusIconsNode', ({ origin: Node, result = [] }) => {
    const { node } = Node;

    if (isFormArea(node) && node.type !== 'column') {
        return [...result, { component: FormAreaIcon, error: hasErrors(node) }];
    }

    if (node.type.match(/^(row|fragment)$/) && hasFormAreas(node)) {
        const contains = getFormAreas(node, node.type !== 'row');

        if (contains.length) {
            result.push({
                component: FormAreaIcon,
                contains,
                error: hasErrors(node)
            });

            return result;
        }
    }

    if (hasErrors(node) && (isFormField(node) || (isElement(node) && hasFormFields(node)))) {
        return [...result, { component: FormAreaIcon, error: hasErrors(node) }];
    }

    return result;
});

function hasErrors(node) {
    const formArea = getClosestFormArea(node);

    if (!formArea && isFormField(node)) {
        return 'field-outside-form-area';
    }

    if (!formArea && hasFormFields(node) && isElement(node) && !hasFormAreas(node)) {
        return 'contains-fields-outside-form-area';
    }

    if (formArea) {
        if (node.type === 'fragment' && (isFormArea(node) || hasFormAreas(node))) {
            return 'duplicated-form-area';
        }

        if (node.type.match(/^(row|yooessentials_form_fieldset)$/) && hasFormAreas(node)) {
            return 'duplicated-form-area';
        }
    }

    if (isFormField(node)) {
        const fields = getFormFields(formArea);
        const controlName = node.props?.control_name;
        const controlNames = fields.map((n) => n.props?.control_name).filter(Boolean);

        if (controlNames.filter((name) => name === controlName).length > 1) {
            return 'duplicated-field-control';
        }
    }

    return false;
}
