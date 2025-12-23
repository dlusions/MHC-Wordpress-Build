<template>
    <li :class="{ 'uk-active': active, 'uk-parent': parent }">
        <a class="uk-display-block" @click.prevent="$emit('click')">
            <div class="uk-text-nowrap uk-text-truncate">
                <slot></slot>
                <span v-if="parent" uk-nav-parent-icon class="uk-margin-small-left"></span>
            </div>
            <div v-if="$slots.meta" class="uk-nav-subtitle uk-text-muted uk-text-truncate">
                <slot name="meta"></slot>
            </div>
        </a>
    </li>
</template>

<script>
export default {
    name: 'NavItem',

    props: {
        active: {
            type: Boolean,
            default: false
        },

        parent: {
            type: Boolean,
            default: false
        }
    }
};
</script>
