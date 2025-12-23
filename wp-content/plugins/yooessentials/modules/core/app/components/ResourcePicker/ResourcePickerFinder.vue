<template>
    <Finder :items="items" :total="list.length" :filter.sync="filter">
        <template v-if="list.length" #items>
            <div
                v-for="(groupItems, group) in groups"
                :key="group"
                :class="{ 'uk-margin': total(groups) > 1 }"
            >
                <h3 v-if="group && total(groups) > 1" class="uk-heading-divider uk-margin-small">
                    {{ group }}
                </h3>

                <div
                    uk-grid
                    :class="[`uk-grid-collapse uk-child-width uk-child-width-${grid}`]"
                    uk-height-match="target: > div > .uk-card"
                >
                    <div v-for="item in groupItems" :key="item.name">
                        <slot name="item" :item="item"></slot>
                    </div>
                </div>
            </div>
        </template>
    </Finder>
</template>

<script>
import { groupBy } from '../../util';
import Finder from '../Finder.vue';

export default {
    name: 'ResourcePickerFinder',

    components: {
        Finder
    },

    props: {
        items: {
            type: Array,
            required: true
        },

        grid: {
            type: String,
            default: '1-6'
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
                .filter((item) => item.group?.toLowerCase() !== 'legacy') // hide legacy group
                .filter(({ name, title, group }) => {
                    const value = [name, title, group]
                        .map((v) => v?.toString().toLowerCase())
                        .join(' ');

                    return !this.filter.search || ~value.indexOf(this.filter.search.toLowerCase());
                })
                .map((item) => ({ group: '', ...item }))
                .sort((a, b) => {
                    if (a.group === b.group) {
                        return 0;
                    }

                    return a.group === '' ? -1 : 1;
                });
        },

        groups() {
            return groupBy(this.list, 'group');
        }
    },

    methods: {
        total: (obj) => Object.keys(obj).length
    }
};
</script>
