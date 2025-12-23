<template>
    <div class="uk-margin-remove-first-child">
        <ul
            v-if="!isEmpty(libraries)"
            class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-iconnav uk-margin uk-margin-remove-bottom"
        >
            <ResourceListItem
                v-for="{ library, title, icon, meta, error, actions } in libraries"
                :key="library.id"
                :title="title"
                :icon="icon"
                :meta="meta"
                :actions="actions"
                :error="error"
                :disabled="Boolean(error)"
                @edit="edit(library)"
                @copy="copy(library)"
                @delete="remove(library)"
            />
        </ul>

        <button
            class="uk-button uk-button-default uk-width-1-1 uk-margin-medium-top"
            type="button"
            @click="add"
            v-text="$t('New Library')"
        ></button>

        <p class="uk-text-muted">
            {{ $t('Add custom Layout Libraries from local or shared sources.') }}
        </p>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '@yootheme/api';
import essentials from 'yooessentials';
import { isEmpty } from 'uikit-util';
import { ModalForm, ResourceListItem } from '@yooessentials/components';
import { deepCopy, keyBy } from '@yooessentials/util';

export default {
    name: 'LibrariesSection',

    components: {
        ResourceListItem
    },

    inject: {
        Sidebar: {}
    },

    props: {
        panel: Object
    },

    computed: {
        storages() {
            return keyBy(essentials.helpers.Storage.storages, 'id');
        },

        libraries() {
            return essentials.helpers.Layouts.libraries.map((library) => {
                const storage = this.storages[library?.storage];
                const adapter = essentials.helpers.Storage.adapters[storage?.adapter];
                const title =
                    library.name ||
                    storage?.name ||
                    adapter?.title ||
                    storage?.adapter ||
                    this.$t('Unknown');

                const error = !storage
                    ? this.$t('Invalid Storage')
                    : !adapter
                      ? this.$t('Invalid Storage Adapter')
                      : '';

                return {
                    library,
                    error,
                    title,
                    meta: !error ? adapter.title : '',
                    icon: adapter?.icon,
                    actions: error ? ['delete'] : ['copy', 'delete']
                };
            });
        }
    },

    methods: {
        isEmpty,

        remove(library) {
            essentials.helpers.Layouts.deleteLibrary(library);
        },

        add() {
            this.edit(Vue.observable({}));
        },

        async edit(library) {
            const values = await api.uikit.promptModal(ModalForm, {
                panel: this.getPanel(),
                values: library
            });

            if (values) {
                essentials.helpers.Layouts.saveLibrary(values);
            }
        },

        // eslint-disable-next-line no-unused-vars
        copy({ id, ...layout }) {
            layout.name = layout.name ? `${layout.name} Copy` : '';
            essentials.helpers.Layouts.saveLibrary(deepCopy(layout));
        },

        getStorageAdapter(library) {
            const storage = this.storages[library.storage];
            return Boolean(essentials.helpers.Storage.adapters[storage?.adapter]);
        },

        getPanel() {
            const name = 'yooessentials-layout-library';

            // get panel from current panel, fallback to global panels
            const panel = this.Sidebar?.panel?.panels?.[name] || this.Sidebar?.panels?.[name];

            if (!panel) {
                throw new Error(`Missing Panel: ${name}`);
            }

            return {
                ...panel,
                component: this.Sidebar?.panel?.component
            };
        }
    }
};
</script>
