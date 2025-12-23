<template>
    <span class="ye-builder-icon-error" :title="$t(error)" :uk-tooltip="`pos: ${position}`"></span>
</template>

<script>
export default {
    name: 'NodeStatus',

    props: {
        node: Object,

        error: String,

        icon: String
    },

    computed: {
        position() {
            const positions = { section: 'top', row: 'left' };
            return positions[this.node?.type?.name] || 'bottom';
        }
    }
};
</script>
