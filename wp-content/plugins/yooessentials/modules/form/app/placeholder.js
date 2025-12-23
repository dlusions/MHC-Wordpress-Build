/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import hooks from '@yootheme/hooks';
import UIkit from 'uikit';
import { FormPlaceholderDrop } from './components';
import { getFormFields, getClosestFormArea } from './helper';

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            prepareFields: {
                handler({ origin: { node } }, fields) {
                    if (!getClosestFormArea(node)) {
                        return;
                    }

                    for (const field of fields) {
                        if (!field.formPlaceholder) {
                            continue;
                        }

                        field.buttons = field.buttons || [];

                        const isDynamic = api.builder.helpers.Source.getProp(node, field.name);

                        field.buttons.unshift({
                            label: Vue.i18n.t('Placeholder'),
                            action: 'yooessentialsPickFormPlaceholder',
                            show: !isDynamic
                        });
                    }
                },

                priority: -15
            },

            yooessentialsPickFormPlaceholder: async ({ origin }, field, { target }) => {
                const { node } = origin;
                const formArea = getClosestFormArea(node);

                const entries = getFormFields(formArea)
                    .filter((f) => f.props?.control_name)
                    .map((f) => {
                        const value = f.props.control_name;

                        return {
                            value,
                            text: f.props?.control_label || value,
                            meta: `{${value}}`
                        };
                    });

                const sel = await api.uikit.promptDropdown(
                    FormPlaceholderDrop,
                    { entries },
                    target,
                    {
                        classes: 'yo-dropdown',
                        boundaryX: target.closest('.yo-sidebar-fields > *') || target
                    }
                );

                if (sel?.meta) {
                    if (!navigator.clipboard) {
                        UIkit.notification({
                            message: 'Copying placeholder not supported, please input manually.',
                            status: 'danger',
                            timeout: 1500
                        });
                        return;
                    }

                    navigator.clipboard.writeText(sel.meta);

                    UIkit.notification({
                        message: 'Placeholder copied to clipboard!',
                        status: 'success',
                        timeout: 1500
                    });

                    // const fieldWrapper = parent(target);
                    // const field = $('div.uk-margin-small > input', fieldWrapper) || $('div.uk-margin-small > textarea', fieldWrapper);

                    // if (sel.meta) {
                    //     field.value = `${field.value}${sel.meta}`;
                    // }
                }
            }
        }
    });
});
