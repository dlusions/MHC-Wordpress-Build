<template>
    <div class="uk-modal-body">
        <ResourcePickerFinder :items="list" :grid="grid">
            <template #item="{ item }">
                <ResourcePickerItem
                    v-if="!openCollection && item.collection"
                    v-bind="item"
                    :description="getCollectionDescription(item.collection)"
                    :badge="total(collections[item.collection])"
                    @resolve="openCollection = item.collection"
                />

                <ResourcePickerItem v-else v-bind="item" @resolve="$emit('resolve', item)" />
            </template>
        </ResourcePickerFinder>
    </div>
</template>

<script>
import { groupBy } from '../../util';
import ResourcePickerItem from './ResourcePickerItem.vue';
import ResourcePickerFinder from './ResourcePickerFinder.vue';

export default {
    name: 'ResourcePickerModal',

    components: {
        ResourcePickerItem,
        ResourcePickerFinder
    },

    props: {
        items: {
            type: Array,
            required: true
        },
        grid: {
            type: String
        }
    },

    data: () => ({
        openCollection: null
    }),

    computed: {
        list() {
            if (this.openCollection) {
                return this.items.filter((item) => item.collection === this.openCollection);
            }

            return this.items
                .reduce((items, item) => {
                    if (item.collection && items.find((i) => i.collection === item.collection)) {
                        return items;
                    }

                    return [...items, { ...item, title: item.collection || item.title }];
                }, [])
                .sort((a, b) => a.group?.localeCompare(b.group));
        },

        collections() {
            return groupBy(this.items, 'collection');
        }
    },

    methods: {
        total: (obj) => Object.keys(obj).length,

        getCollectionDescription(collection) {
            return this.collections?.[collection]?.find((i) => i.collectionDescription)
                ?.collectionDescription;
        }
    }
};
</script>
