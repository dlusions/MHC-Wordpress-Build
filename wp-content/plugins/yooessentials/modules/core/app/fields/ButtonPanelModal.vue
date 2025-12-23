<template>
    <button
        v-bind="attributes"
        :class="{
            'yo-button-medium': attributes.class === 'yo-form-medium'
        }"
        class="uk-button yo-button-panel uk-width-1-1"
        type="button"
        @click.prevent="open"
    >
        {{ $t(field.text || 'Open') }}
    </button>
</template>

<script>
import api from '@yootheme/api';
import ButtonPanel from './ButtonPanel.vue';
import { ModalForm } from '../components';

export default {
    extends: ButtonPanel,

    inject: {
        Sidebar: { default: {} }
    },

    methods: {
        async open() {
            const values = await api.uikit.promptModal(
                ModalForm,
                {},
                {
                    panel: this.getPanel(),
                    values: this.values
                }
            );

            if (!values) {
                return;
            }

            for (const key in values) {
                this.$set(this.values, key, values[key]);
            }
        }
    }
};
</script>
