<template>
    <div class="uk-modal-body">
        <Finder :items="items" :total="items.length" :filter.sync="filter">
            <template v-if="list.length" #items>
                <div v-for="($items, group) in groups" :key="group" class="uk-margin-medium">
                    <h3 v-if="groupsTotal > 1" class="uk-heading-divider uk-margin-small">
                        {{ group }}
                    </h3>
                    <div
                        class="uk-grid-collapse uk-child-width uk-child-width-1-2 uk-child-width-1-4@s uk-child-width-1-5@m"
                        uk-grid
                        uk-height-match="target: > div > .uk-card"
                    >
                        <div
                            v-for="item in $items"
                            :key="item.name"
                            :title="item.description"
                            uk-tooltip="pos: bottom; delay: 300"
                        >
                            <div class="uk-card uk-card-body uk-card-hover yo-panel uk-text-center">
                                <img
                                    v-if="item.icon"
                                    :alt="item.title"
                                    :src="item.icon"
                                    uk-svg
                                    width="40"
                                    height="40"
                                />
                                <p class="uk-margin-small-top uk-margin-remove-bottom uk-text-wrap">
                                    {{ item.title }}
                                </p>
                                <a
                                    href
                                    class="uk-position-cover"
                                    @click.prevent="$emit('resolve', item)"
                                ></a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Finder>
    </div>
</template>

<script>
import { groupBy } from '../util';
import Finder from './Finder.vue';

export default {
    name: 'ItemsFinderModal',

    components: {
        Finder
    },

    props: {
        items: {
            type: Array,
            require: true
        }
    },

    data: () => ({
        filter: {
            search: ''
        }
    }),

    computed: {
        list() {
            return this.items
                .filter(({ id, name, group }) => {
                    const value = [id, name, group]
                        .map((v) => v?.toString().toLowerCase())
                        .join(' ');

                    return !this.filter.search || ~value.indexOf(this.filter.search.toLowerCase());
                })
                .map((item) => ({ group: 'custom', ...item }));
        },

        groups() {
            const groups = groupBy(this.list, 'group');

            for (const group in groups) {
                if (groups[group].length) {
                    groups[group] = groups[group].sort((a, b) => a?.name?.localeCompare(b.name));
                }
            }

            return groups;
        },

        groupsTotal() {
            return Object.keys(this.groups).length;
        }
    }
};
</script>
