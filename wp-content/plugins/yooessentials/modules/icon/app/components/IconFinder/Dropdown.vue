<template>
    <div>
        <div class="yo-dropdown-body uk-overflow-auto uk-height-max-medium">
            <ul class="uk-nav uk-dropdown-nav">
                <li v-if="collections.length" class="uk-nav-header uk-flex uk-position-relative">
                    {{ $t('Filter by') }}
                    <span
                        v-if="isFiltered"
                        uk-icon="close"
                        ratio="0.7"
                        :title="$t('Reset Filters')"
                        uk-tooltip="delay: 500"
                        class="uk-link uk-margin-small-right uk-position-center-right uk-position-medium"
                        @click.prevent="reset"
                    ></span>
                </li>

                <li
                    v-for="{ name, title, groups = [] } in collections"
                    :key="name"
                    :class="{
                        'uk-active': filter.collection === name,
                        'uk-parent': groups.length
                    }"
                >
                    <a
                        v-if="collections.length > 1"
                        href="#"
                        @click.prevent="applyFilter({ collection: name })"
                        >{{ title }}</a
                    >
                    <a v-else class="uk-disabled" href="#" @click.prevent>{{ title }}</a>
                    <ul
                        v-if="
                            groups.length > 1 &&
                            (filter.collection === name || collections.length === 1)
                        "
                        class="uk-nav-sub"
                    >
                        <li
                            v-for="group in groups"
                            :key="group"
                            :class="{ 'uk-active uk-text-bold': filter.group === group }"
                        >
                            <a href="#" @click.prevent="applyFilter({ group })">{{
                                group || $t('Main')
                            }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        collections: {
            type: Array,
            required: true
        },

        filter: {
            type: Object,
            default: () => ({
                search: '',
                collection: '',
                group: ''
            })
        }
    },

    computed: {
        isFiltered() {
            return this.filter.collection || this.filter.group;
        }
    },

    watch: {
        filter: {
            handler(filter) {
                const collection = this.collections.find((c) => c.name === filter.collection);

                if (
                    (collection && !collection.groups) ||
                    filter.group ||
                    (!filter.collection && !filter.group)
                ) {
                    this.$emit('hide');
                }
            },

            deep: true
        }
    },

    methods: {
        applyFilter(params) {
            // reset group is switching collection
            if (params.collection && this.filter.collection !== params.collection) {
                params.group = '';
            }

            this.$emit('update:filter', { ...this.filter, ...params });
        },

        reset() {
            if (this.collections.length === 1) {
                this.applyFilter({ group: '' });
            } else {
                this.applyFilter({ collection: '', group: '' });
            }
        }
    }
};
</script>
