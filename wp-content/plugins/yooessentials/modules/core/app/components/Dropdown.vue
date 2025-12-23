<template>
    <div class="ye-dropdown">
        <div v-if="$slots.header" class="yo-dropdown-header">
            <slot name="header"></slot>
        </div>

        <div :class="['yo-dropdown-body uk-overflow-auto', `uk-height-max-${maxHeight}`]">
            <slot name="body"></slot>
        </div>

        <div v-if="$slots.footer" class="yo-dropdown-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Dropdown',

    props: {
        maxHeight: {
            type: String,
            default: 'medium'
        }
    }
};
</script>
