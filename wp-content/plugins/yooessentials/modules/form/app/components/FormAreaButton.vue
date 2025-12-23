<template>
    <button
        class="uk-button uk-button-small uk-button-default"
        type="button"
        @click="$trigger('yeOpenFormAreaSettings', node)"
    >
        {{ $t('Form Settings') }}
    </button>
</template>

<script>
export default {
    name: 'FormAreaButton',

    props: {
        node: Object
    }
};
</script>
