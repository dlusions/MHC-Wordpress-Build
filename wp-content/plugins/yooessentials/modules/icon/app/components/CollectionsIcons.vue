<template>
    <div v-if="!collections.length" class="uk-height-medium uk-flex">
        <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
            <p class="uk-text-lead uk-text-center">
                {{ $t('Start by adding your first Icon Collection') }}
            </p>
            <button class="uk-button uk-button-primary" @click="addCollection">
                {{ $t('Add Collection') }}
            </button>
            <p class="uk-text-meta uk-text-center">
                {{ $t('Or manage all collections in the Essentials Section.') }}
            </p>
        </div>
    </div>

    <IconFinder
        v-else
        :items="icons"
        :total="total"
        :fetching="fetching"
        :collections="collections"
        @fetch="fetch"
        @resolve="$emit('select', $event)"
    />
</template>

<script>
import api from '@yootheme/api';
import { ItemsFinderModal } from '@yooessentials/components';
import IconFinder from './IconFinder/index.vue';
import essentials from 'yooessentials';

export default {
    name: 'Collections',

    components: {
        IconFinder
    },

    data: () => ({
        icons: [],
        total: 0,
        batch: 500,
        fetching: false,
        query: {}
    }),

    computed: {
        collections() {
            return essentials.helpers.Icons.collections.filter(
                (c) => c.installed && c.name !== 'myicons'
            );
        }
    },

    created() {
        // this.total = this.installedCollections.reduce((carry, { icons = 0 }) => carry + icons, 0);

        this.fetch();
    },

    methods: {
        async addCollection() {
            const collection = await api.uikit.promptModal(ItemsFinderModal, {
                items: essentials.helpers.Icons.collections.filter(
                    (c) => !c.installed && c.name !== 'myicons'
                )
            });

            if (collection) {
                await essentials.helpers.Icons.addCollection(collection);
                await this.fetch({}, true);
            }
        },

        fetch(params = {}, force = false) {
            this.fetching = true;

            params = { offset: 0, length: this.batch, ...params };

            return essentials.helpers.Icons.fetch(params, force).then((response) => {
                const { total, data } = response;

                if (params.offset === 0) {
                    this.total = total;
                    this.icons = data;
                } else {
                    this.icons = [...this.icons, ...data];
                }

                this.fetching = false;
            });
        }
    }
};
</script>
