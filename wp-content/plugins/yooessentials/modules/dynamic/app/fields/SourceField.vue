<template>
    <input v-if="attributes.disabled" disabled class="uk-input" v-html="value" />

    <div
        v-else
        :class="[
            'uk-cursor-default uk-inline uk-select',
            attributes.class,
            { 'uk-form-danger': source.field && !source.isFieldValid }
        ]"
        @click.prevent="select"
    >
        <div v-if="source.field" class="uk-disabled uk-text-truncate">
            <span v-html="source.fieldOverview"></span>
            <span v-if="source.fieldSchemaType" class="uk-text-meta">{{
                source.fieldSchemaType
            }}</span>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import { FieldsList } from '../components';
import Source from '../Source';

export default {
    name: 'SourceField',

    extends: fields.components.FieldText,

    inject: ['$node'],

    computed: {
        source() {
            return new Source(this.values, this.$node);
        }
    },

    methods: {
        async select() {
            const name = await api.uikit.promptDropdown(
                FieldsList,
                {
                    base: this.source.basePath,
                    path: this.source.field,
                    maxNesting: 0
                },
                this.$el,
                {
                    flip: false,
                    classes: 'yo-dropdown'
                }
            );

            if (name !== undefined) {
                this.source.field = name;
            }
        }
    }
};
</script>
