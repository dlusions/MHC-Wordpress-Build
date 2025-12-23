/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import Vue from 'vue';
import hooks from '@yootheme/hooks';
import essentials from 'yooessentials';
import LayoutEditor from './LayoutEditor.vue';
import LayoutEditorButton from './LayoutEditorButton.vue';
import { $, addClass, removeClass, remove, isEmpty } from 'uikit-util';

let editorInstance = null;

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            layoutButtons: {
                handler({ result = [] }) {
                    if (essentials.helpers.Config.get('debug.layout')) {
                        return [
                            ...result,
                            { component: LayoutEditorButton, instance: editorInstance }
                        ];
                    }

                    return result;
                },
                priority: -10
            },

            toggleLayoutEditor() {
                const { Sidebar, Builder } = essentials.yoo;

                if (editorInstance) {
                    editorOff();
                    return;
                }

                const panel = Sidebar?.panel || {};

                if (panel.props?.template) {
                    editorOn(panel.props.template.layout);
                    return;
                }

                if (panel.props?.current) {
                    editorOn(Builder.node);
                    return;
                }

                editorOn(panel.props?.node);
            },

            openPanel(e, panel) {
                if (!editorInstance || !panel.props?.node) {
                    return;
                }

                if (panel.name.startsWith('yooessentials-composed')) {
                    editorInstance.source = panel.props.values;
                    return;
                }

                if (panel.name.startsWith('yooessentials-access-condition')) {
                    editorInstance.source = panel.props.values.yooessentials_access_conditions;
                    return;
                }

                if (panel.name.startsWith('yooessentials-form-settings')) {
                    editorInstance.source = panel.props.values.yooessentials_form;
                    return;
                }

                if (panel.name.startsWith('yooessentials-source')) {
                    editorInstance.source = panel.props.values;
                    return;
                }

                editorInstance.source = panel.props.node;
            },

            closePanel(e, event, closingPanel, panel) {
                if (!editorInstance) {
                    return;
                }

                if (closingPanel.name.match(/builder|template-builder/)) {
                    editorOff();
                    return;
                }

                if (panel.name.startsWith('yooessentials-access-condition')) {
                    editorInstance.source = panel.props.values.yooessentials_access_conditions;
                    return;
                }

                if (panel.name.startsWith('yooessentials-form-settings')) {
                    editorInstance.source = panel.props.values.yooessentials_form;
                    return;
                }

                if (panel.props?.template) {
                    editorInstance.source = panel.props.template.layout;
                } else if (panel.props?.current) {
                    editorInstance.source = essentials.yoo.Builder.node;
                } else if (panel.props?.node) {
                    editorInstance.source = panel.props?.node;
                }
            },

            resetNode() {
                if (editorInstance) {
                    editorInstance.source = essentials.yoo.Builder.node;
                }
            },

            loadPreview: {
                handler(e, { loadedPage } = {}) {
                    if (editorInstance) {
                        // workaround when the layout is empty
                        // and then new layout is created
                        if (
                            editorInstance?.source?.version &&
                            isEmpty(editorInstance?.source?.children)
                        ) {
                            editorInstance.source = essentials.yoo.Builder.node;
                        }
                    }

                    // don't update preview if editor enabled,
                    // unless is being reseted
                    if (editorInstance && loadedPage) {
                        return false;
                    }
                },

                priority: 99
            }
        }
    });
});

function editorOn(node) {
    addClass($('.yo-preview-iframe'), 'uk-hidden');

    editorInstance = new Vue({
        extends: LayoutEditor,
        propsData: {
            builder: essentials.yoo.Builder
        }
    }).$mount();

    editorInstance.source = node;

    document.getElementsByClassName('yo-preview')[0].append(editorInstance.$el);
}

function editorOff() {
    if (!editorInstance) {
        return;
    }

    remove(editorInstance.$el);
    editorInstance.$destroy();
    removeClass($('.yo-preview-iframe'), 'uk-hidden');

    editorInstance = null;
    Vue.events.trigger('loadPreview');
}
