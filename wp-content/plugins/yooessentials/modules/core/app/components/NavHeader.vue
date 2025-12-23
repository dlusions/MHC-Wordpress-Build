<template functional>
    <li :class="props.sub ? 'yo-nav-subheader' : 'uk-nav-header'">{{ props.label }}</li>
</template>

<script>
export default {
    functional: true,

    props: {
        label: {
            type: String,
            required: true
        },
        sub: {
            type: Boolean,
            default: false
        }
    }
};
</script>
