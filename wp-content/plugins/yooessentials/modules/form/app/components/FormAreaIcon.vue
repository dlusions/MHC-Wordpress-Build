<template>
    <a
        v-if="formAreaNode && !error"
        href="#"
        :class="icon"
        :title="$t(title)"
        :uk-tooltip="`delay: 1000; pos: ${tooltipDirection}`"
        @click.prevent="openFormAreaSettings"
    ></a>

    <span
        v-else
        :class="icon"
        :title="$t(title)"
        :uk-tooltip="`delay: 1000; pos: ${tooltipDirection}`"
    ></span>
</template>

<script>
import { isFormArea } from '../helper';

const errors = {
    'duplicated-form-area': 'Duplicated Form Area',
    'duplicated-field-control': 'Duplicated field Control Name',
    'field-outside-form-area': 'Field outside a Form Area',
    'contains-fields-outside-form-area': 'Contains fields outside a Form Area'
};

export default {
    name: 'FormAreaIcon',

    props: {
        node: {
            type: Object,
            required: true
        },
        contains: {
            type: Array,
            default: () => []
        },
        error: {
            type: [String, Boolean],
            default: ''
        },
        tooltipDirection: {
            type: String,
            default: 'bottom'
        }
    },

    computed: {
        formAreaNode() {
            if (isFormArea(this.node.node)) {
                return this.node.node;
            }

            return this.contains.length > 1 ? null : this.contains[0];
        },

        icon() {
            return `ye-builder-icon-form${this.error ? '-error' : ''}`;
        },

        title() {
            const { node } = this.node;

            if (this.error) {
                return errors[this.error] || 'Invalid Form Area';
            }

            if (isFormArea(node)) {
                return 'Form Area';
            }

            if (this.contains.length) {
                return 'Contains Form Area';
            }

            return '';
        }
    },

    methods: {
        openFormAreaSettings() {
            this.$trigger('yeOpenFormAreaSettings', this.formAreaNode);
        }
    }
};
</script>
