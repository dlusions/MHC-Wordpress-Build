<template>
    <div v-if="error" class="uk-text-danger">{{ error }}</div>

    <div v-else-if="fetching || !fields">{{ $t('Fetching') }} ...</div>

    <div v-else class="ye-async-fields">
        <BuilderPanel
            v-if="Builder"
            :builder="Builder"
            :node="$node"
            :panel="panel"
            :values="values"
        />

        <fields-panel v-else :panel="panel" :values="values" />
    </div>
</template>

<script>
import http from '@yootheme/http';
import fields from '@yootheme/fields';
import { BuilderPanel } from '../components';

export default {
    name: 'AsyncFields',

    components: {
        BuilderPanel
    },

    extends: fields.components.FieldText,

    inject: {
        Builder: { default: undefined },
        $node: { default: undefined }
    },

    data: () => ({
        error: '',
        fetching: false,
        fields: null
    }),

    computed: {
        panel() {
            return {
                fields: this.fields
            };
        }
    },

    created() {
        if (!this.field.endpoint) {
            throw new Error('AsyncFields: Missing endpoint');
        }

        this.fetch().then((fields) => {
            this.fields = fields;
        });
    },

    methods: {
        fetch(params = {}) {
            params = { ...params, ...(this.field.params || {}) };
            this.$trigger('yooessentials-resolve-field-argument', params);

            this.error = false;
            this.fetching = true;

            return http(this.field.endpoint)
                .post(params)
                .json((fields) => {
                    this.fetching = false;

                    return fields;
                })
                .catch((e) => {
                    this.error = e?.json || e?.message || e?.toString() || 'Error';
                    this.fetching = false;
                });
        }
    }
};
</script>
