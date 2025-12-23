<template>
    <Finder item-name="Icon" :items="icons" :total="total" :filter.sync="filter">
        <template #count>
            <h2 class="uk-modal-title uk-margin-remove">
                {{ $t(`%smart_count% Icon |||| %smart_count% Icons`, total) }}
                <span v-if="collections.length > 1" class="uk-text-meta uk-display-block">{{
                    $t(
                        `%smart_count% Collection |||| %smart_count% Collections`,
                        collections.length
                    )
                }}</span>
            </h2>
        </template>

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

                <li v-if="menu" ref="menu">
                    <a
                        href
                        class="uk-icon-link"
                        uk-icon="more-vertical"
                        @click.prevent="openCollectionsMenu"
                    ></a>
                </li>
            </ul>
        </template>

        <template #items>
            <component :is="view" :icons="icons" @resolve="$emit('resolve', $event)" />

            <div v-if="fetching" class="uk-flex uk-flex-center uk-padding-small">
                <span uk-spinner ratio="0.9"></span>
            </div>

            <div v-else-if="total > icons.length" class="uk-text-center">
                <a
                    class="uk-icon-link"
                    uk-icon="more"
                    ratio="2"
                    :title="$t('Load More')"
                    href=""
                    uk-tooltip="delay: 500"
                    @click.prevent="fetchNext()"
                ></a>
            </div>
        </template>
    </Finder>
</template>

<script>
import api from '@yootheme/api';
import { debounce, keyBy, storage } from '@yooessentials/util';
import { Finder } from '@yooessentials/components';
import List from './List.vue';
import Thumbnail from './Thumbnail.vue';
import Dropdown from '../IconFinder/Dropdown.vue';

const storageView = 'yooessentials.icons.view';

export default {
    name: 'IconFinder',

    components: {
        List,
        Thumbnail,
        Finder
    },

    props: {
        items: Finder.props.items,

        total: Finder.props.total,

        menu: {
            type: Boolean,
            default: true
        },

        addCollection: {
            type: Boolean,
            default: false
        },

        collections: {
            type: Array,
            default: () => []
        },

        fetching: {
            type: Boolean,
            default: false
        }
    },

    data: () => ({
        view: storage[storageView] || 'thumbnail',
        filter: {
            search: '',
            collection: '',
            group: ''
        }
    }),

    computed: {
        icons() {
            return this.items.map(this.parse);
        }
    },

    watch: {
        view(view) {
            storage[storageView] = view;
        },

        filter: {
            handler: debounce(function () {
                this.fetch({ ...this.filter, offset: 0 });
            }, 100),

            deep: true
        }
    },

    methods: {
        fetch(params) {
            this.$emit('fetch', params);
        },

        fetchNext() {
            if (this.items.length < this.total) {
                this.fetch({ ...this.filter, offset: this.items.length });
            }
        },

        parse(key) {
            const parsed = key.match(/^([^-]*)-?(.*)--(.*)$/);

            if (!parsed) {
                throw new Error(`Bad Formated Icon Name: ${key}`);
            }

            const collections = keyBy(this.collections, 'name');
            const [, collection, group, name] = parsed;

            return { collection: collections[collection]?.title || collection, group, name, key };
        },

        openCollectionsMenu() {
            api.uikit.openDropdown(
                {
                    functional: true,
                    render: (h) =>
                        h(Dropdown, {
                            props: {
                                filter: this.filter,
                                collections: this.collections,
                                addCollection: this.addCollection
                            },
                            on: {
                                hide: () => dp.hide(),
                                'update:filter': (filter) => (this.filter = filter),
                                'add:collection': () => this.$emit('add-collection')
                            }
                        })
                },
                {},
                this.$refs.menu,
                { pos: 'bottom-right', classes: 'yo-dropdown' }
            );
        }
    }
};
</script>
