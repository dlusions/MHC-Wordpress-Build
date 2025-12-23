<template>
    <button
        v-bind="attributes"
        :class="{ 'yo-button-medium': attributes.class === 'yo-form-medium' }"
        class="uk-button yo-button-panel ye-button-panel uk-width-1-1"
        type="button"
        @click.prevent="open"
    >
        {{ $t(field.text || 'Open') }}
    </button>
</template>

<script>
import fields from '@yootheme/fields';
import { isString } from 'uikit-util';

export default {
    extends: fields.components.FieldText,

    inject: {
        Sidebar: { default: {} },
        Builder: { default: {} },
        $node: { default: {} }
    },

    methods: {
        open() {
            this.$trigger('openDynamicPanel', {
                ...this.getPanel(),
                heading: this.field.heading,
                props: {
                    node: this.$node,
                    values: this.values,
                    builder: this.Builder
                }
            });
        },

        getPanel() {
            let panel = this.field.panel;

            if (!panel) {
                throw new Error('Invalid Panel');
            }

            if (isString(panel)) {
                // get panel from current panel, fallback to global panels
                panel = this.Sidebar?.panel?.panels?.[panel] || this.Sidebar?.panels?.[panel];
            }

            if (!panel) {
                throw new Error(`Panel Not Found: ${this.field.panel}`);
            }

            panel = {
                name: this.field.panel,
                title: this.field.panel,
                ...panel
            };

            return panel;
        }
    }
};
</script>
