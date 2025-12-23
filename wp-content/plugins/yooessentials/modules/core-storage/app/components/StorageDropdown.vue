<template>
    <div>
        <div class="yo-dropdown-body uk-overflow-auto uk-height-max-large">
            <ul class="uk-nav uk-dropdown-nav">
                <li
                    v-for="{ storage, adapter } in $storages"
                    :key="storage.id"
                    class="uk-visible-toggle uk-text-truncate uk-position-relative"
                >
                    <a href @click.prevent="$emit('resolve', storage)">
                        <img
                            v-if="adapter.icon"
                            :alt="adapter.title"
                            class="uk-margin-small-right"
                            :src="adapter.icon"
                            uk-svg
                            width="20"
                            height="20"
                        />
                        <span v-else uk-icon="lock" class="uk-margin-small-right"></span>
                        <span class="uk-text-emphasis uk-text-middle">{{ storage.name || adapter.name }}</span>

                        <span class="uk-text-middle uk-text-meta"
                            >{{ adapter.title }}
                            <span v-if="storage.timestamp">{{ $t('created on') }} {{ storage.timestamp | date }}</span></span
                        >
                    </a>

                    <div class="uk-invisible-hover uk-position-center-right uk-position-medium uk-margin-small-right">
                        <ul class="uk-iconnav uk-flex-nowrap">
                            <li>
                                <a
                                    :title="$t('Edit')"
                                    class="uk-icon-link uk-preserve-width"
                                    uk-icon="pencil"
                                    uk-tooltip="delay: 500"
                                    @click.prevent="$emit('edit', storage)"
                                ></a>
                            </li>
                            <li>
                                <a
                                    :title="$t('Delete')"
                                    class="uk-icon-link uk-preserve-width"
                                    uk-icon="trash"
                                    uk-tooltip="delay: 500"
                                    @click.prevent="$emit('remove', storage)"
                                ></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li v-if="storages.length" class="uk-nav-divider"></li>

                <template v-for="adapter in adapters">
                    <li v-if="adapter" :key="adapter.name">
                        <a href @click.prevent="$emit('add', adapter.name)">
                            <span class="uk-margin-small-right" uk-icon="plus-circle"></span>
                            {{ adapter.title }}
                            <span class="uk-text-meta">{{ adapter.description }}</span>
                        </a>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'StorageDropdown',

    filters: {
        date(timestamp) {
            return new Date(timestamp).toLocaleString().split(',')[0];
        },
    },

    props: {
        storages: {
            type: Array,
            required: true,
        },

        adapters: {
            type: Object,
            default: () => ({}),
        },
    },

    computed: {
        $storages() {
            return this.storages.map((storage) => {
                const adapter = { services: [], ...this.adapters?.[storage.adapter] };

                return { storage, adapter };
            });
        },
    },
};
</script>
