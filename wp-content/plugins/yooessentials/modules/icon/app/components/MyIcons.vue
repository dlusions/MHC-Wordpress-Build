<template>
    <div v-if="collection.icons === 0" class="uk-height-medium uk-flex">
        <div class="uk-margin-auto uk-margin-auto-vertical uk-text-center">
            <p class="uk-text-lead uk-text-center">{{ $t('MyIcons is your Icon Collection') }}</p>
            <p class="uk-text-muted uk-text-center">
                {{ $t('Start by placing icons into the "child_theme/myicons" folder.') }}
            </p>
        </div>
    </div>

    <IconFinder
        v-else
        :menu="Boolean(totalGroups > 1)"
        :items="icons"
        :total="total"
        :fetching="fetching"
        :collections="[collection]"
        @fetch="fetch"
        @resolve="$emit('select', $event)"
    />
</template>

<script>
import essentials from 'yooessentials';
import IconFinder from './IconFinder/index.vue';
import CollectionsIcons from './CollectionsIcons.vue';

export default {
    name: 'MyIcons',

    components: {
        IconFinder
    },

    extends: CollectionsIcons,

    computed: {
        collection() {
            return essentials.helpers.Icons.collections.find((c) => c.name === 'myicons') || {};
        },

        totalGroups() {
            return this.collection.groups?.length || 0;
        }
    },

    created() {
        this.total = this.collection.icons || 0;
        this.fetch();
    },

    methods: {
        fetch(params = {}, force = false) {
            CollectionsIcons.methods.fetch.call(this, { ...params, collection: 'myicons' }, force);
        }
    }
};
</script>
