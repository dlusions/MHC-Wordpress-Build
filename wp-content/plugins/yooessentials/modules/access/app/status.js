/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import AccessIcon from './AccessIcon.vue';

export const errors = {
    'empty-rules': 'Contains empty rules'
};

Vue.events.on('statusIconsNode', ({ origin: Node, result = [] }) => {
    const { node } = Node;

    if (hasAccessConditions(Node)) {
        return [...result, { component: AccessIcon, error: errors[hasError(node)] }];
    }

    return result;
});

function hasAccessConditions(Node) {
    const nodes = [Node.node];

    if ((Node.type.element && Node.type.container) || Node.type.name === 'row') {
        nodes.push(...Node.children);
    }

    return nodes.some((node) => (node?.props?.yooessentials_access_conditions ?? []).length);
}

function hasError(node) {
    const rules = node?.props?.yooessentials_access_conditions ?? [];

    const ruleIsEmpty = (rule) => Object.values(rule?.props ?? {}).filter(Boolean).length === 0;

    if (rules.some(ruleIsEmpty)) {
        return 'empty-rules';
    }

    return false;
}
