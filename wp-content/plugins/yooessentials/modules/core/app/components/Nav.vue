<template functional>
    <ul class="uk-nav" :class="{ 'uk-dropdown-nav': props.dropdown }" uk-nav>
        <slot></slot>
    </ul>
</template>

<script>
export default {
    functional: true,

    props: {
        dropdown: {
            type: Boolean,
            default: false
        }
    }
};
</script>
