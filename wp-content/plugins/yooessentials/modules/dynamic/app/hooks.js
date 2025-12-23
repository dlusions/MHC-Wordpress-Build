/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import api from '@yootheme/api';
import hooks from '@yootheme/hooks';
import { BuilderPanel } from '@yooessentials/components';
import { toCompose } from './options/composed';
import ComposedField from './composed/ComposedField.vue';
import { DynamicField, DynamicDropdown } from './components';
import Source from './Source';
import SourceHelper from './SourceHelper';

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            prepareFields: {
                handler({ origin: { node, panel } }, fields) {
                    // skip fields rendered by SourceQueryArgs or SourceQueryFieldArgs
                    if (panel?.name?.startsWith('yooessentials-source-query')) {
                        return;
                    }

                    if (!node) {
                        return;
                    }

                    for (const field of fields) {
                        if (!field.source) {
                            continue;
                        }

                        field.buttons = field.buttons || [];

                        // filter out yoo source picker
                        field.buttons = field.buttons.filter((btn) => btn.action !== 'pickSource');

                        const prop = SourceHelper.getProp(node, field);

                        field.buttons.push({
                            label: this.$t('Dynamic'),
                            action: 'essentialsPickQuery',
                            show: !prop
                        });

                        if (prop) {
                            const source = new Source(prop, node);

                            field.component = {
                                functional: true,
                                render: (h) =>
                                    h(prop?.composed ? ComposedField : DynamicField, {
                                        props: {
                                            field,
                                            source
                                        },
                                        on: {
                                            remove() {
                                                SourceHelper.removeProp(node, field);
                                            },
                                            compose() {
                                                SourceHelper.setProp(
                                                    node,
                                                    field,
                                                    toCompose(source.values)
                                                );
                                            }
                                        }
                                    })
                            };
                        }
                    }
                },
                priority: -5 // execute latest
            },

            evaluateExpression({ origin: { node }, params }, expression, values) {
                params[1] = {
                    ...values,
                    ...(node?.source_extended?.props || {})
                };
            },

            openComposedPanel(e, panel) {
                Vue.events.trigger('openPanel', {
                    ...api.customizer.panels['yooessentials-source'],
                    ...panel,
                    heading: !panel?.fieldset,
                    component: {
                        extends: BuilderPanel,
                        provide() {
                            return {
                                $field: panel?.props?.field
                            };
                        }
                    }
                });
            }
        }
    });
});

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            prepareFields: {
                handler({ origin: { $vnode, panel, $parent } }, fields) {
                    // deal with source query args fields only
                    if (!panel?.name?.startsWith('yooessentials-source-query-args')) {
                        return;
                    }

                    const origin = panel?.name.replace('yooessentials-source-', '');
                    const source = $vnode.data.source;
                    const node = $parent.$parent.node;

                    if (!node) {
                        return;
                    }

                    for (const field of fields) {
                        if (!field.source) {
                            continue;
                        }

                        field.origin = origin;
                        field.buttons = field.buttons || [];

                        // filter out yoo source picker
                        field.buttons = field.buttons.filter((btn) => btn.action !== 'pickSource');

                        const arg =
                            origin === 'query-args'
                                ? source.getQueryExtendedArgument(field.name)
                                : source.getQueryFieldExtendedArgument(field.name);

                        field.buttons = field.buttons.concat([
                            {
                                label: this.$t('Dynamic'),
                                action: 'essentialsPickQueryArgument',
                                show: !arg,
                                origin
                            }
                        ]);

                        if (arg) {
                            const argSource = new Source(arg, node);

                            field.component = {
                                functional: true,
                                render: (h) =>
                                    h(argSource?.composed ? ComposedField : DynamicField, {
                                        props: {
                                            field,
                                            source: argSource
                                        },
                                        on: {
                                            remove() {
                                                if (origin === 'query-args') {
                                                    source.removeQueryArgument(field.name);
                                                } else {
                                                    source.removeQueryFieldArgument(field.name);
                                                }
                                            },
                                            compose() {
                                                const value = toCompose(argSource.values);

                                                if (origin === 'query-args') {
                                                    source.setQueryExtendedArgument(
                                                        field.name,
                                                        value
                                                    );
                                                } else {
                                                    source.setQueryFieldExtendedArgument(
                                                        field.name,
                                                        value
                                                    );
                                                }
                                            }
                                        }
                                    })
                            };
                        }
                    }
                },
                priority: -5 // execute latest
            },

            evaluateExpression({ origin: { $vnode, panel }, params }, expression, values) {
                if (panel?.name !== 'yooessentials-source-query-args') {
                    return;
                }

                const source = $vnode.data.source;

                params[1] = {
                    ...values,
                    ...(source?.values?.query?.arguments_extended || {})
                };
            }
        }
    });
});

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            async essentialsPickQuery({ origin: { node } }, field, { target }) {
                const prop = SourceHelper.getProp(node, field);

                if (prop) {
                    return;
                }

                const source = await api.uikit.promptDropdown(
                    DynamicDropdown,
                    {
                        node,
                        field
                    },
                    target,
                    {
                        flip: false,
                        classes: 'yo-dropdown',
                        boundaryX: target.closest('.yo-sidebar-fields > *') || target
                    }
                );

                if (source) {
                    SourceHelper.setProp(node, field, source);
                }
            },

            async essentialsPickQueryArgument({ origin: { $vnode, $parent } }, field, { target }) {
                const node = $parent?.$parent?.node;

                if (!node) {
                    throw new Error('Node not found');
                }

                const source = $vnode.data.source;

                const arg = await api.uikit.promptDropdown(
                    DynamicDropdown,
                    {
                        field,
                        node,
                        // do not display a node option if this is a node query arg
                        omittedOptions: source.isNodeSource ? ['node'] : []
                    },
                    target,
                    {
                        classes: 'yo-dropdown',
                        flip: false,
                        boundaryX: target.closest('.yo-sidebar-fields > *') || target
                    }
                );

                if (source) {
                    if (field.origin === 'query-args') {
                        source.setQueryExtendedArgument(field.name, arg);
                    }

                    if (field.origin === 'query-field-args') {
                        source.setQueryFieldExtendedArgument(field.name, arg);
                    }
                }
            }
        }
    });
});
