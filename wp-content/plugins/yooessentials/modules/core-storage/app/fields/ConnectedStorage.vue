<template>
    <div v-if="!adapters.length">No valid adapters.</div>

    <div v-else class="uk-inline uk-width-1-1">
        <div class="uk-position-center-right uk-position-small">
            <ul class="uk-iconnav uk-flex-nowrap">
                <li v-if="value">
                    <a
                        tabindex="-1"
                        :title="$t('Edit')"
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="pencil"
                        uk-tooltip="delay: 500"
                        @click.prevent="edit()"
                    ></a>
                </li>
                <li>
                    <a
                        tabindex="-1"
                        :title="$t('Pick')"
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="album"
                        uk-tooltip="delay: 500"
                        @click.prevent="empty ? add() : select()"
                    ></a>
                </li>
            </ul>
        </div>

        <span class="uk-input uk-disabled" :title="attributes.title" :class="[attributes.class]">
            <template v-if="selectedStorage && adapter">
                <!-- icon -->
                <img
                    v-if="adapter.icon"
                    :alt="adapter.title"
                    :src="adapter.icon"
                    uk-svg
                    width="20"
                    height="20"
                />
                <span v-else uk-icon="lock"></span>

                <!-- name -->
                <span class="uk-text-emphasis uk-margin-small-left uk-text-baseline">{{
                    selectedStorage.name || adapter.name
                }}</span>

                <!-- meta -->
                <span class="uk-text-meta uk-margin-small-left uk-text-baseline">{{
                    adapter.title
                }}</span>
            </template>
        </span>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import essentials from 'yooessentials';
import { includes, isEmpty } from 'uikit-util';
import { StorageDropdown, StorageModal } from '../components';

export default {
    name: 'ConnectedStorage',

    filters: {
        date(timestamp) {
            const date = new Date(timestamp).toLocaleString();
            return date.split(',')[0];
        },
    },

    extends: fields.components.FieldText,

    computed: {
        selectedStorage() {
            return essentials.helpers.Storage.storages.find((storage) => storage.id === this.value);
        },

        empty() {
            return this.adapters.length === 1 && isEmpty(this.storages);
        },

        adapter() {
            return essentials.helpers.Storage.adapters?.[this.selectedStorage?.adapter];
        },

        adapters() {
            return Object.values(essentials.helpers.Storage.adapters).map(
                (adapter) => adapter?.name,
            );
        },

        storages() {
            return essentials.helpers.Storage.storages.filter((storage) =>
                includes(this.adapters, storage.adapter),
            );
        },
    },

    created() {
        if (!this.selectedStorage) {
            this.value = '';
        }
    },

    methods: {
        isEmpty,

        add(adapter) {
            adapter = adapter || this.adapters[0];

            return this.edit({ adapter }).then((storage) => {
                if (storage) {
                    this.value = storage.id;
                }
            });
        },

        async edit(storage = this.selectedStorage) {
            const adapters = essentials.helpers.Storage.adapters;

            let values = await api.uikit.promptModal(StorageModal, { adapters, values: storage });

            if (values) {
                values = essentials.helpers.Storage.save(values);
            }

            this.$forceUpdate();
            return values;
        },

        select() {
            const drop = api.uikit.openDropdown(
                {
                    functional: true,
                    render: (h) =>
                        h(StorageDropdown, {
                            props: {
                                storages: this.storages,
                                adapters: essentials.helpers.Storage.adapters,
                            },
                            on: {
                                add: (adapter) => {
                                    drop.hide();
                                    this.add(adapter).then(() => {
                                        drop.$forceUpdate();
                                    });
                                },
                                edit: (storage) => {
                                    drop.hide();
                                    this.edit(storage).then(() => {
                                        drop.$forceUpdate();
                                    });
                                },
                                remove: (storage) => {
                                    essentials.helpers.Storage.remove(storage);
                                    drop.$forceUpdate();
                                    if (storage.id === this.value) {
                                        this.value = '';
                                    }
                                    if (isEmpty(this.storages)) {
                                        drop.hide();
                                    }
                                },
                                resolve: (storage) => {
                                    drop.hide();
                                    this.value = storage.id;
                                    drop.$forceUpdate();
                                },
                            },
                        }),
                },
                {},
                this.$el,
                { classes: 'yo-dropdown' },
            );
        },
    },
};
</script>
