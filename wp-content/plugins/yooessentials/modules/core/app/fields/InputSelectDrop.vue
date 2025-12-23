<template>
    <a v-bind="attributes" href class="uk-select" @click.prevent="open" @keydown.down="open">
        {{ value }}
    </a>
</template>

<script>
import InputDrop from './InputDrop.vue';

export default {
    name: 'SelectDrop',

    extends: InputDrop
};
</script>
