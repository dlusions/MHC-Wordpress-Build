<template>
    <button
        class="uk-button uk-button-small uk-button-default uk-active"
        type="button"
        @click="$trigger('toggleLayoutEditor')"
    >
        <span ratio="0.8" :uk-icon="active ? 'close' : 'code'"></span>
    </button>
</template>

<script>
export default {
    name: 'LayoutEditorButton',

    props: {
        instance: Object
    },

    data: () => ({
        active: false
    }),

    created() {
        if (this.instance) {
            this.active = true;
        }
    },

    events: {
        toggleLayoutEditor() {
            this.active = !this.active;
        }
    }
};
</script>
