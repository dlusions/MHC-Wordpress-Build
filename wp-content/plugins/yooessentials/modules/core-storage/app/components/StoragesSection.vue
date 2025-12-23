<template>
    <div class="uk-margin-remove-first-child">
        <ul
            v-if="!isEmpty(storages)"
            class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-iconnav uk-margin uk-margin-remove-bottom"
        >
            <ResourceListItem
                v-for="{ storage, title, icon, meta, error, actions } in storages"
                :key="storage.id"
                :title="title"
                :icon="icon"
                :meta="meta"
                :actions="actions"
                :error="error"
                :disabled="Boolean(error)"
                @edit="edit(storage)"
                @copy="copy(storage)"
                @delete="remove(storage)"
            >
                <template v-if="error" #icon>
                    <span uk-icon="question" ratio="1.2"></span>
                </template>
            </ResourceListItem>
        </ul>

        <button
            :class="{ 'uk-margin-medium-top': !isEmpty(storages) }"
            class="uk-button uk-button-default uk-width-1-1"
            type="button"
            @click.prevent="add()"
        >
            {{ $t('New Storage') }}
        </button>

        <p class="uk-text-muted">
            {{
                $t(
                    'Create virtual storages from local and external locations, used by Shared Locations and others.',
                )
            }}
        </p>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '@yootheme/api';
import { isEmpty } from 'uikit-util';
import essentials from 'yooessentials';
import { deepCopy } from '../../../../modules/core/app/util.js';
import { ModalForm, ResourceListItem, ResourcePicker } from 'yooessentials-components';

export default {
    name: 'StoragesSection',

    components: {
        ResourceListItem,
    },

    props: {
        panel: Object,
    },

    computed: {
        storages() {
            return essentials.helpers.Storage.storages.map((storage) => {
                const adapter = essentials.helpers.Storage.adapters[storage?.adapter];
                const title =
                    storage.name || adapter?.title || storage.adapter || this.$t('Unknown');

                const error = !adapter ? this.$t('Invalid Adapter') : '';

                return {
                    storage,
                    error,
                    title,
                    meta: !error ? adapter.title : '',
                    icon: adapter?.icon,
                    actions: error ? ['delete'] : ['copy', 'delete'],
                };
            });
        },
    },

    methods: {
        isEmpty,

        async add() {
            const adapter = await api.uikit.promptModal(
                ResourcePicker,
                { items: Object.values(essentials.helpers.Storage.adapters) },
                { container: true },
            );

            if (adapter) {
                this.edit(Vue.observable({ adapter: adapter.name }));
            }
        },

        async edit(storage) {
            const panel = essentials.helpers.Storage.adapters[storage.adapter];
            const values = await api.uikit.promptModal(ModalForm, { values: storage, panel });

            if (values) {
                essentials.helpers.Storage.save(values);
            }
        },

        remove(storage) {
            essentials.helpers.Storage.remove(storage);
        },

        // eslint-disable-next-line no-unused-vars
        copy({ id, ...storage }) {
            storage.name = storage.name ? `${storage.name} Copy` : '';
            essentials.helpers.Storage.save(deepCopy(storage));
        },
    },
};
</script>
