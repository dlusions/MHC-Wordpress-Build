<template>
    <div class="uk-hidden"></div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import { $$, on } from 'uikit-util';
import { FormControlModal } from '../components';

export default {
    extends: fields.components.FieldText,

    mounted() {
        $$('#yooessentials-form-show-control-names', this.$parent.$el).forEach((n) => {
            on(n, 'click', (e) => {
                e.preventDefault();
                this.showTags();
            });
        });
    },

    methods: {
        showTags() {
            api.uikit.openModal(FormControlModal);
        },
    },
};
</script>
