<template>
    <Finder
        :item-name="$parent.mode === 'layouts' ? 'Layout' : 'Preset'"
        :items="items"
        :filter="filter"
        :selected="selected"
        @update:selected="$emit('update:selected', $event)"
        @update:filter="$emit('update:filter', $event)"
    >
        <template v-if="selected.length" #actions>
            <a
                :title="$t('Download Selected')"
                class="uk-icon-link"
                uk-icon="download"
                uk-tooltip="delay: 500"
                @click.prevent="$emit('export', selectedNodes)"
            ></a>
            <a
                v-if="isWritable"
                v-confirm="'Are you sure?'"
                :title="$t('Delete Selected')"
                class="uk-icon-link"
                uk-icon="trash"
                uk-tooltip="delay: 500"
                @click.prevent="$emit('delete', selectedNodes)"
            ></a>
        </template>

        <template #right>
            <div class="uk-flex">
                <slot name="toolbar"></slot>

                <!-- <div>

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

                </div> -->
            </div>
        </template>

        <template #items>
            <div class="uk-overflow-auto">
                <component :is="view" />
            </div>
        </template>
    </Finder>
</template>

<script>
import List from './ListView.vue';
import Thumbnail from './ThumbnailView.vue';
import { Finder } from '@yooessentials/components';
import { storage } from '@yooessentials/util';

const storageKey = 'yooessentials.shared-layouts.view';

export default {
    name: 'LibraryTabFinder',

    components: {
        List,
        Finder,
        Thumbnail
    },

    directives: {
        confirm: 'confirm'
    },

    provide() {
        return {
            Finder: this
        };
    },

    props: {
        items: Finder.props.items,
        filter: Finder.props.filter,
        selected: Finder.props.selected,

        isWritable: {
            type: Boolean,
            default: false
        }
    },

    data: () => ({
        view: storage[storageKey] || 'list'
    }),

    computed: {
        selectedAll: Finder.computed.selectedAll,
        selectedNodes() {
            return this.selected.map((id) => this.items.find((i) => i.id === id));
        }
    },

    watch: {
        view(view) {
            storage[storageKey] = view;
        }
    },

    methods: {
        ...Finder.methods
    }
};
</script>
