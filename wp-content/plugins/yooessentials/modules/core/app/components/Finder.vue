<template>
    <div>
        <!-- HEADER -->
        <div class="yo-modal-subheader uk-margin-medium-bottom">
            <div class="uk-grid-small uk-flex-middle uk-flex-between" uk-grid>
                <div class="uk-flex uk-flex-middle uk-flex-1">
                    <div>
                        <slot name="count" :count-text="countText">
                            <h2 class="uk-modal-title uk-margin-remove uk-text-nowrap">
                                {{ selected.length ? selectedText : countText }}
                            </h2>
                        </slot>
                    </div>

                    <div v-if="$slots.actions" class="uk-margin-small-left">
                        <slot name="actions"></slot>
                    </div>

                    <div class="yo-finder-search uk-flex uk-flex-between uk-flex-1">
                        <div class="uk-search uk-search-navbar uk-width-expand uk-width-small@s">
                            <span uk-search-icon></span>
                            <input
                                :value="filter.search"
                                class="uk-search-input"
                                type="search"
                                autofocus
                                @input="
                                    $emit('update:filter', {
                                        ...filter,
                                        search: $event.target.value
                                    })
                                "
                            />
                        </div>
                    </div>

                    <slot name="filter"></slot>

                    <div v-if="$slots.right">
                        <slot name="right" :items="items"></slot>
                    </div>
                </div>
            </div>
        </div>

        <!-- BODY -->
        <div class="yo-finder-body" uk-overflow-auto>
            <h3 v-if="!items.length && filter.search" class="uk-h1 uk-text-muted uk-text-center">
                {{ $t('Not found. Try a different criteria.') }}
            </h3>

            <div v-else>
                <slot name="items" :items="items"></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FinderView',

    props: {
        itemName: {
            type: String,
            default: 'Item'
        },

        items: {
            type: Array,
            required: true
        },

        total: {
            type: Number,
            default: 0
        },

        selected: {
            type: Array,
            default: () => []
        },

        filter: {
            type: Object,
            default: () => ({
                search: ''
            })
        }
    },

    computed: {
        totalItems() {
            return this.total || this.items.length;
        },

        countText() {
            return this.$t(
                `%smart_count% ${this.itemName} |||| %smart_count% ${this.itemName}s`,
                this.totalItems
            );
        },

        selectedText() {
            return this.$t('%smart_count% Selected', this.selected.length);
        },

        selectedAll() {
            return this.selected.length === this.items.length;
        }
    },

    methods: {
        isSelected({ id }) {
            return ~this.selected.indexOf(id);
        },

        toggleSelect({ id }) {
            const selected = [...this.selected];
            const index = this.selected.indexOf(id);

            ~index ? selected.splice(index, 1) : selected.push(id);

            this.$emit('update:selected', selected);
        },

        toggleSelectAll() {
            const selected = this.selectedAll ? [] : [...this.items.map(({ id }) => id)];

            this.$emit('update:selected', selected);
        }
    }
};
</script>
