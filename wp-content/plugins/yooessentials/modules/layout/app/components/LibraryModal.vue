<template>
    <ModalForm :values="values" :panel="storage" @resolve="save" />
</template>

<script>
import { $ } from 'uikit-util';
import { ModalForm } from '@yooessentials/components';

export default {
    name: 'LibraryModal',

    components: {
        ModalForm
    },

    props: {
        storages: {
            type: Array,
            required: true
        },

        values: {
            type: Object,
            required: true
        }
    },

    computed: {
        storage() {
            return this.storages?.[this.values.storage];
        }
    },

    watch: {
        'values.storage': function () {
            this.$nextTick(() => {
                const focus = $('[autofocus]', this.$el);
                focus && focus.focus();
            });
        }
    },

    methods: {
        save(values) {
            this.$emit('resolve', values);
        },

        setStorage(storage) {
            this.$set(this.values, 'storage', storage);
        }
    }
};
</script>
