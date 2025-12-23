/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import essentials from 'yooessentials';
import http from '@yootheme/http';
import { isArray } from 'uikit-util';
import { arrify, uuid } from '@yooessentials/util';

export default {
    name: 'LayoutsModel',

    data: () => ({
        layouts: {}
    }),

    computed: {
        libraries: {
            get() {
                return essentials.helpers.Config.get('layout.libraries', []);
            },

            set(values) {
                essentials.helpers.Config.set('layout.libraries', values);
            }
        }
    },

    methods: {
        isWritable(libraryId) {
            const library = this.libraries.find((lib) => lib.id === libraryId);
            return Boolean(this.storageOf(library));
        },

        storageOf(library) {
            return essentials.helpers.Storage.storages.find((s) => s.id === library?.storage);
        },

        saveLibrary(values) {
            const storage = essentials.helpers.Storage.storages.find(
                (s) => s.id === values.storage
            );
            const libraries = [...this.libraries];

            if (!storage) {
                throw Error(`Unknown Storage: ${values.storage}`);
            }

            if (values.id) {
                libraries[libraries.findIndex(({ id }) => id === values.id)] = values;
            } else {
                values = {
                    ...values,
                    id: uuid(),
                    _meta: {
                        created_at: Date.now(),
                        created_on: essentials.version
                    }
                };

                libraries.push(values);
            }

            this.libraries = libraries;

            return values;
        },

        deleteLibrary(library) {
            const libraries = [...this.libraries];
            libraries.splice(libraries.indexOf(library), 1);

            this.libraries = libraries;
        },

        getNodesList(library, refresh = 0) {
            return http('yooessentials/layout/library')
                .post({ library, refresh })
                .json((nodes) => {
                    if (isArray(nodes)) {
                        this.layouts = { ...this.layouts, [library]: nodes };
                        return nodes;
                    }
                });
        },

        getNode(library, nodes) {
            return http('yooessentials/layout/library/node')
                .post({
                    library,
                    ids: arrify(nodes).map((n) => n.id)
                })
                .json();
        },

        saveNode(library, node) {
            if (library === 'my') {
                return essentials.yoo.Library.saveElement(
                    node,
                    node.type === 'layout'
                        ? essentials.yoo.Library.findElement(node.name, node.type)
                        : undefined
                );
            }

            if (node.created) {
                node.modified = new Date().toISOString();
            }

            return http('yooessentials/layout/library/node/save')
                .post({ library, node })
                .res(() => {
                    this.getNodesList(library);
                });
        },

        updateNode(library, node) {
            return this.getNode(library, [{ id: node.id }]).then(([oldnode]) =>
                this.saveNode(library, { ...oldnode, ...node })
            );
        },

        deleteNodes(library, ids) {
            return http('yooessentials/layout/library/node/delete')
                .post({ library, ids })
                .res(() => {
                    this.getNodesList(library);
                });
        },

        findNode(library, node) {
            return this.layouts[library].find(
                ({ name, type }) => node.name === name && node.type === type
            );
        }
    }
};
