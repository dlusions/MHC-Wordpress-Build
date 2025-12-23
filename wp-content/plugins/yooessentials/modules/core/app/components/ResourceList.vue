<template>
    <ul
        v-if="sortable"
        v-sortable="{ group: 'dataset-items' }"
        :class="[
            'uk-nav uk-nav-default yo-sidebar-marginless yo-nav-sortable uk-margin uk-margin-remove-bottom',
            { 'yo-nav-iconnav': !$attrs.disabled }
        ]"
        cls-custom="yo-nav-sortable-drag"
        style="min-height: 45px"
    >
        <slot></slot>
    </ul>

    <ul
        v-else
        :class="[
            'uk-nav uk-nav-default yo-sidebar-marginless uk-margin uk-margin-remove-bottom',
            { 'yo-nav-iconnav': !$attrs.disabled }
        ]"
    >
        <slot></slot>
    </ul>
</template>

<script>
export default {
    name: 'ResourceList',

    props: {
        sortable: {
            type: Boolean,
            default: true
        }
    },

    methods: {
        copy(...args) {
            if (this.$parent.copy) {
                this.$parent.copy(...args);
                return;
            }

            this.$trigger('copy', ...args);
        },

        move(...args) {
            if (this.$parent.move) {
                this.$parent.move(...args);
                return;
            }

            this.$trigger('move', ...args);
        },

        remove(...args) {
            if (this.$parent.remove) {
                this.$parent.remove(...args);
                return;
            }

            this.$trigger('remove', ...args);
        }
    }
};
</script>
