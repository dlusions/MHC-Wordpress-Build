<template>
    <div
        :class="[
            'uk-cursor-default uk-inline uk-select',
            attributes.class,
            { 'uk-form-danger': source.queryField && !source.isQueryFieldValid }
        ]"
        @click.prevent="select"
    >
        <div class="uk-disabled uk-text-truncate">
            <template v-if="source.queryField">
                <span v-html="source.queryFieldOverview"></span>
                <span v-if="source.querySchemaType" class="uk-text-meta">{{
                    source.querySchemaType?.metadata?.label
                }}</span>
            </template>
            <template v-else>
                {{ $t('None') }}
            </template>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import { FieldsList } from '../components';
import Source from '../Source';

export default {
    name: 'YooessentialsSourceQueryField',

    extends: fields.components.FieldText,

    inject: {
        Builder: {},
        $node: {},
        $source: {
            default: null
        }
    },

    computed: {
        source() {
            return new Source(this.$source ?? this.$node.source, this.$node);
        },

        value: {
            get() {
                return this.source.queryField;
            },

            set(name) {
                this.source.queryField = name;
            }
        }
    },

    methods: {
        async select() {
            const name = await api.uikit.promptDropdown(
                FieldsList,
                {
                    base: this.source.queryPath,
                    path: this.value,
                    fieldType: 'LIST',
                    resolvable: (type) => type.kind === 'LIST',
                    additionalFields: [this.value && { text: this.$t('None'), value: '' }].filter(
                        Boolean
                    )
                },
                this.$el,
                { flip: false, classes: 'yo-dropdown' }
            );

            if (name !== undefined) {
                this.value = name;
            }
        }
    }
};
</script>
