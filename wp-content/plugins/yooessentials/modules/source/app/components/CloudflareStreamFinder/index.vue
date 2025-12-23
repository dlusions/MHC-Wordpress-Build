<template>
    <Finder item-name="Video" :items="items" :filter.sync="filter" :selected.sync="selected">
        <!-- <template v-if="selected.length" v-slot:actions>
            <a href :title="$t('Set as Private')" class="uk-icon-link" uk-icon="lock" uk-tooltip="delay: 500" @click.prevent="$emit('update', selected, {requireSignedURLs: true})"></a>
            <a href :title="$t('Set as Public')" class="uk-icon-link" uk-icon="unlock" uk-tooltip="delay: 500" @click.prevent="$emit('update', selected, {requireSignedURLs: false})"></a>
            <a href :title="$t('Delete')" class="uk-icon-link" uk-icon="trash" uk-tooltip="delay: 500" @click.prevent="remove(selected)"></a>
        </template> -->

        <template #right>
            <ul class="uk-grid-small uk-child-width-auto" uk-grid>
                <li :class="{ 'uk-active': view === 'list' }">
                    <a
                        href
                        :title="$t('List')"
                        class="uk-icon-link"
                        uk-icon="table"
                        uk-tooltip="delay: 500"
                        @click.prevent="view = 'list'"
                    ></a>
                </li>
                <li :class="{ 'uk-active': view === 'thumbnail' }">
                    <a
                        href
                        :title="$t('Thumbnails')"
                        class="uk-icon-link"
                        uk-icon="thumbnails"
                        uk-tooltip="delay: 500"
                        @click.prevent="view = 'thumbnail'"
                    ></a>
                </li>
            </ul>
        </template>

        <template #items>
            <component :is="view" />
        </template>
    </Finder>
</template>

<script>
import List from './List.vue';
import Thumbnail from './Thumbnail.vue';
import { storage } from '@yooessentials/util';
import { Finder } from '@yooessentials/components';

const storageKey = 'yooessentials.stream-finder.view';

export default {
    name: 'CloudflareStreamFinder',

    components: {
        List,
        Finder,
        Thumbnail
    },

    provide() {
        return {
            Finder: this
        };
    },

    props: {
        items: Finder.props.items,
        selected: Finder.props.selected
    },

    data: () => ({
        view: storage[storageKey] || 'list',
        filter: {
            search: ''
        }
    }),

    computed: {
        selectedAll: Finder.computed.selectedAll
    },

    watch: {
        view(view) {
            storage[storageKey] = view;
        }

        // search(query) {
        //     // this.selected = [];
        //     // this.load({search: query});
        // }
    },

    methods: Finder.methods
};
</script>
