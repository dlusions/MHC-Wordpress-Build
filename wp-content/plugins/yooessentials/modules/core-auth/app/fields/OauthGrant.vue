<template>
    <button
        v-bind="attributes"
        :class="{
            'yo-button-medium': attributes.class === 'yo-form-medium',
            'uk-disabled': active
        }"
        class="uk-button yo-button-panel uk-width-1-1"
        type="button"
        @click.prevent="connect"
    >
        <span v-if="active">{{ $t('Authenticating') }}...</span>
        <span v-else>{{ $t(connected ? 'Re-Authenticate' : 'Authenticate') }}</span>
        <span v-if="auth.expiresAt" class="uk-text-meta uk-margin-small-left"
            >{{ $t('Expires') }} {{ auth.expiresAt | date }}</span
        >
    </button>
</template>

<script>
import fields from '@yootheme/fields';
import format from 'date-fns/format';

export default {
    name: 'oAuthGrant',

    filters: {
        date(dateValue) {
            if (!dateValue) return '';

            // Handle Unix timestamp (number)
            if (typeof dateValue === 'number') {
                dateValue = new Date(dateValue * 1000);
            }

            // Handle date object with date property
            if (dateValue?.date) {
                dateValue = new Date(dateValue.date);
            }

            return format(dateValue, 'do MMM, yyyy');
        }
    },

    extends: fields.components.FieldText,

    inject: ['AuthModal'],

    data: () => ({
        active: false
    }),

    computed: {
        auth() {
            return this.values;
        },

        connected() {
            return this.auth.scopes?.length;
        }
    },

    methods: {
        connect() {
            this.active = true;

            this.AuthModal.connect()
                .then(() => {
                    this.active = false;
                })
                .catch(() => {
                    this.active = false;
                });
        }
    }
};
</script>
