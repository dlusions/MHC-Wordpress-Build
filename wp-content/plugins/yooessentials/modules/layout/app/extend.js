/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import api from '@yootheme/api';
import hooks from '@yootheme/hooks';

import essentials from 'yooessentials';
import { LayoutsManager, NodeModal } from './components';

['layoutsModalTabs', 'elementsModalTabs'].forEach((eventName) => {
    hooks.before('app.init', ({ extend }) => {
        extend({
            events: {
                [eventName]({ result = [] }) {
                    const libraries = essentials.helpers.Layouts.libraries.filter((lib) =>
                        essentials.helpers.Layouts.storageOf(lib)
                    );

                    libraries.forEach((library) => {
                        result.push({
                            name: library.name,
                            component: {
                                render(h) {
                                    return h(LayoutsManager, {
                                        props: {
                                            mode:
                                                eventName === 'layoutsModalTabs'
                                                    ? 'layouts'
                                                    : 'presets',
                                            library: library.id
                                        },
                                        on: {
                                            select: (node) => {
                                                this.$emit('select', node); // layoutsModalTabs
                                                this.$emit('resolve', node); // elementsModalTabs
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    });

                    return result;
                }
            }
        });
    });
});

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            async saveNode(e, savedNode) {
                const builder = essentials.yoo.Builder;

                if (savedNode.type === 'layout' || !essentials.helpers.Layouts.libraries.length) {
                    return;
                }

                const { library, node } = await api.uikit.promptModal(
                    NodeModal,
                    {
                        builder,
                        node: { ...builder.clone(savedNode), modified: new Date().toISOString() },
                        libraries: essentials.helpers.Layouts.libraries
                    },
                    { width: 'xlarge' }
                );

                if (!node || !library) {
                    return;
                }

                essentials.helpers.Layouts.saveNode(library, node);

                return false;
            }
        }
    });
});
