<template>
    <div v-if="!loaded" class="uk-height-small">
        <span
            uk-spinner
            class="uk-position-absolute uk-transform-center"
            style="left: 50%; top: 50%"
        ></span>
    </div>

    <div v-else>
        <input
            ref="files"
            hidden
            accept="application/json"
            type="file"
            name="files[]"
            multiple="multiple"
            @change="upload"
        />

        <LayoutsFinder
            v-if="nodesList.length || nodes.length"
            :items="nodesList"
            :selected.sync="selected"
            :filter.sync="filter"
            :is-writable="isWritable"
            @select="selectNode"
            @rename="renameNode"
            @delete="deleteNodes"
            @export="exportNodes"
        >
            <template #toolbar>
                <div v-if="mode === 'layouts' && isWritable">
                    <button
                        class="uk-button uk-button-primary uk-button-small uk-margin-right"
                        @click.prevent="saveNode"
                    >
                        {{ $t('Save Layout') }}
                    </button>
                </div>

                <div>
                    <ul class="uk-grid-small uk-child-width-auto" uk-grid>
                        <li v-if="isWritable">
                            <a
                                href
                                :title="$t('Import')"
                                class="uk-icon-link"
                                uk-icon="pull"
                                uk-tooltip="delay: 500"
                                @click.prevent="triggerUpload"
                            ></a>
                        </li>
                        <li>
                            <a
                                href
                                :title="$t('Refresh')"
                                class="uk-icon-link"
                                uk-icon="refresh"
                                uk-tooltip="delay: 500"
                                @click.prevent="getNodes(true)"
                            ></a>
                        </li>
                    </ul>
                </div>
            </template>
        </LayoutsFinder>

        <!-- empty state -->
        <div v-else class="uk-text-center uk-height-small uk-flex uk-flex-column uk-flex-center">
            <p class="uk-h1 uk-text-muted uk-margin-large">
                {{ $t(mode === 'layouts' ? 'No layouts found.' : 'No element presets found') }}
            </p>

            <div v-if="isWritable">
                <div>
                    <button
                        class="uk-button uk-button-default uk-margin-small-right"
                        type="button"
                        @click.prevent="triggerUpload"
                    >
                        {{ $t(mode === 'layouts' ? 'Upload Layout' : 'Upload Preset') }}
                    </button>
                    <button
                        v-if="mode === 'layouts'"
                        class="uk-button uk-button-primary"
                        type="button"
                        @click.prevent="saveNode"
                    >
                        {{ $t('Save Layout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import { includes } from 'uikit-util';
import NodeModal from './NodeModal.vue';
import LayoutsFinder from './LayoutsFinder/index.vue';
import flattenDeep from 'lodash-es/flattenDeep';
import startCase from 'lodash-es/startCase';
import { arrify, deepCopy, download, readFile } from '@yooessentials/util';
import essentials from 'yooessentials';

export default {
    name: 'LayoutsModal',

    components: {
        LayoutsFinder
    },

    props: {
        library: {
            type: String,
            required: true
        },
        mode: {
            type: String,
            default: 'layouts' // layouts | presets
        }
    },

    data: () => ({
        loaded: false,
        nodes: [],
        selected: [],
        filter: {
            search: ''
        }
    }),

    computed: {
        isWritable() {
            return essentials.helpers.Layouts.isWritable(this.library);
        },

        nodesList() {
            return this.nodes.filter(({ name }) => {
                const value = [name].map((v) => v?.toString().toLowerCase()).join(' ');

                return !this.filter.search || ~value.indexOf(this.filter.search.toLowerCase());
            });
        }
    },

    watch: {
        nodes(nodes) {
            this.selected = [...this.selected].filter((id) => nodes.find((i) => i.id === id));
        }
    },

    created() {
        essentials.helpers.Layouts.$watch(
            'layouts',
            (value) => {
                this.nodes = (value[this.library] ?? []).filter(({ type }) => {
                    const isLayout = type === 'layout' || type === 'section' || type === 'fragment';
                    return (
                        (this.mode === 'layouts' && isLayout) ||
                        (this.mode === 'presets' && !isLayout)
                    );
                });
            },
            { deep: true }
        );
    },

    mounted() {
        this.getNodes().then(() => {
            this.loaded = true;
        });
    },

    methods: {
        startCase,

        triggerUpload() {
            this.$refs.files.click();
        },

        getNodes(refresh = false) {
            return essentials.helpers.Layouts.getNodesList(this.library, refresh).catch((e) => {
                api.uikit.notify(e?.json, 'danger');
            });
        },

        selectNode(node) {
            essentials.helpers.Layouts.getNode(this.library, node)
                .then((node) => {
                    this.$emit('select', node[0]);
                })
                .catch((e) => {
                    api.uikit.notify(e?.json, 'danger');
                });
        },

        formatDate(date) {
            return date ? new Date(date).toLocaleString() : this.$t('-');
        },

        async renameNode(renamedNode) {
            const { node, library } = await api.uikit.promptModal(
                NodeModal,
                {
                    node: deepCopy(renamedNode),
                    builder: essentials.Builder,
                    library: this.library,
                    edit: true,
                    libraries: essentials.helpers.Layouts.libraries
                },
                { width: 'xlarge' }
            );

            if (!node || !library) {
                return;
            }

            return essentials.helpers.Layouts.updateNode(library, {
                id: node.id,
                name: node.name
            }).catch((e) => {
                api.uikit.notify(e?.json, 'danger');
            });
        },

        async saveNode() {
            const builder = essentials.yoo.Builder;
            const savedNode = { ...builder.clone(builder.node) };

            const { node, library } = await api.uikit.promptModal(
                NodeModal,
                {
                    builder,
                    node: savedNode,
                    library: this.library,
                    libraries: essentials.helpers.Layouts.libraries
                },
                { width: 'xlarge' }
            );

            if (!node || !library) {
                return;
            }

            essentials.helpers.Layouts.saveNode(library, node).catch((e) => {
                api.uikit.notify(e?.json, 'danger');
            });
        },

        exportNodes(nodes) {
            essentials.helpers.Layouts.getNode(this.library, nodes)
                .then((nodes) => {
                    let filename, contents;

                    if (nodes.length > 1) {
                        filename = 'layouts.json';
                        contents = nodes;
                    } else {
                        filename = `${nodes[0].name || 'layout'}.json`;
                        contents = { ...nodes[0], version: api.customizer.version };
                    }

                    download(filename, JSON.stringify(contents, null, '    '));
                })
                .catch((e) => {
                    api.uikit.notify(e?.json, 'danger');
                });
        },

        deleteNodes(nodes) {
            essentials.helpers.Layouts.deleteNodes(
                this.library,
                arrify(nodes).map((n) => n.id)
            ).catch((e) => {
                api.uikit.notify(e?.json, 'danger');
            });
        },

        upload(e) {
            try {
                const files = e.currentTarget.files || e.dataTransfer?.files || [];
                const uploads = [];

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    try {
                        uploads.push(readFile(file));
                    } catch (error) {
                        throw new Error(`Error loading file '${file.name}'.`);
                    }
                }

                Promise.all(uploads)
                    .then((files) => {
                        const _uploads = files.map((file) => JSON.parse(file.target.result));

                        const names = [];
                        const nodes = flattenDeep(_uploads);

                        nodes.forEach((node) => {
                            const { name, type } = node;

                            if (!name) {
                                throw new Error('Invalid layout.');
                            }

                            if (this.mode === 'layouts' && !includes(['section', 'layout'], type)) {
                                throw new Error('Invalid layout.');
                            }

                            if (essentials.helpers.Layouts.findNode(this.library, node)) {
                                names.push(name);
                            }
                        });

                        if (
                            names.length &&
                            !window.confirm(
                                this.$t(
                                    `Layout ${names.join(
                                        ', '
                                    )} already exists in the library, do you want to import it anyway?`
                                )
                            )
                        ) {
                            return;
                        }

                        for (let i = 0; i < nodes.length; i++) {
                            essentials.helpers.Layouts.saveNode(this.library, nodes[i]);
                        }

                        return true;
                    })
                    .then((res) => {
                        if (res) {
                            api.uikit.notify('Uploaded successfully.', 'success');
                        }

                        e.srcElement.value = '';
                    })
                    .catch((error) => {
                        api.uikit.notify(error, 'danger');
                    });
            } catch (error) {
                api.uikit.notify(error, 'danger');
            }
        }
    }
};
</script>
