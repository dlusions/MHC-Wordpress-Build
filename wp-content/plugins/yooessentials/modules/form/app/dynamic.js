/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import http from '@yootheme/http';
import hooks from '@yootheme/hooks';
import essentials from 'yooessentials';
import { isFormArea, getFormFields } from './helper';

let initialSchema;
let currentFormAreaNode = null;

hooks.after('app.init', () => {
    initialSchema = api.builder.schema;
});

hooks.after('app.init', () => {
    const query = { name: 'yooessentials_form_query' };

    const FormOption = {
        priority: 90,
        name: 'yooessentials.form',
        group: 'primary',
        title: 'Submission',
        description: 'Form submission source',
        query: () => query,
        queries: () => {
            if (!currentFormAreaNode) {
                return [];
            }

            return [
                {
                    name: query.name,
                    metadata: {
                        option: 'yooessentials.form',
                        label: Vue.i18n.t('Form'),
                        group: Vue.i18n.t('Submission'),
                        description: Vue.i18n.t('Form submission source')
                    },
                    type: {
                        name: 'YooessentialsForm'
                    }
                }
            ];
        },
        match: ({ prop }) => prop?.query?.name === query.name,
        matchNode: () => Boolean(currentFormAreaNode),
        resolve: ({ prop }) => {
            prop.query = { ...(prop.query || {}), ...query };
        }
    };

    if (essentials.helpers.Dynamic) {
        essentials.helpers.Dynamic.$options.dynamicOptions.push(FormOption);
    }
});

Vue.events.on('yooessentialsSourceQueryOrigin', ({ result }, { node, prop }) => {
    if (result) {
        return result;
    }

    prop = essentials.helpers.Source.getProp(node, prop);

    if (prop?.query?.name === 'yooessentials_form_query') {
        return 'yooessentials.form';
    }
});

Vue.events.on('transformedNode', (e, newNode, node) => {
    if (
        node.children &&
        node.type.startsWith('yooessentials_form_') &&
        newNode.type.startsWith('yooessentials_form_') &&
        node.children.every((n) => n.type === 'yooessentials_form_option')
    ) {
        newNode.children = node.children;
    }
});

Vue.events.on('openPanel', (e, panel) => {
    const panels = [panel, ...(e.sidebar?.stack || [])];
    const formPanel = panels.find((s) => s.name === 'yooessentials-form-settings');

    if (formPanel && !currentFormAreaNode) {
        const node = formPanel.props?.node;
        const enabled = isFormArea(node);

        if (enabled) {
            currentFormAreaNode = node;

            const controls = getFormFields(node, essentials.yoo.Builder).filter(
                (n) => n.props?.control_name
            );

            controls.length &&
                http('yooessentials/form/schema')
                    .post({ controls })
                    .json((schema) => {
                        api.builder.schema = schema;
                    });
        }
    }
});

Vue.events.on('closePanel', (e, payload, panel) => {
    if (panel.name === 'yooessentials-form-settings') {
        currentFormAreaNode = null;
        api.builder.schema = initialSchema;
    }
});

// remove Dynamic Options not available during a form submissions
Vue.events.on(
    'essentialsDynamicOptionMatchNode',
    ({ result }, options) => {
        result = result || options;

        if (!currentFormAreaNode) {
            return result;
        }

        return result.filter((opt) => !['node', 'parent', 'page'].includes(opt.name));
    },
    -100
);
