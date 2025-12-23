<template>
    <div
        :class="{ 'uk-form-danger ye-dynamic-field-error': !source.isFieldValid }"
        class="uk-position-relative uk-input yo-input-iconnav-right ye-dynamic-field"
    >
        <div
            class="uk-flex uk-flex-middle"
            :title="source.pathOverview"
            uk-tooltip="delay: 500"
            @click.prevent="remap"
        >
            <span
                :uk-icon="`ye--dynamic${source.isMultipleSource ? '-n' : ''}`"
                ratio="1.2"
                class="uk-flex-none uk-flex uk-flex-middle uk-margin-small-right"
            ></span>

            <div class="uk-flex-1 uk-disabled uk-text-truncate">
                <span v-html="source.fieldOverview"></span>
                <span v-if="source.fieldSchemaType" class="uk-text-meta">{{
                    source.fieldSchemaType
                }}</span>
            </div>
        </div>

        <div class="uk-position-center-right uk-position-small">
            <ul class="uk-iconnav uk-flex-nowrap">
                <li>
                    <a
                        :title="$t('Edit')"
                        href
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="pencil"
                        uk-tooltip="delay: 500"
                        @click.prevent="edit"
                    ></a>
                </li>
                <li v-if="source.isFieldValid && !source?.composed">
                    <a
                        :title="$t('Compose')"
                        href
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="forward"
                        uk-tooltip="delay: 500"
                        @click.prevent="$emit('compose')"
                    ></a>
                </li>
                <li>
                    <a
                        :title="$t('Delete')"
                        href
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="trash"
                        uk-tooltip="delay: 500"
                        @click.prevent="$emit('remove')"
                    ></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';
import FieldsList from './FieldsList.vue';

export default {
    name: 'DynamicField',

    inject: ['$node'],

    provide() {
        return {
            $field: this.field
        };
    },

    props: {
        field: {
            type: Object,
            required: true
        },
        source: {
            type: Object,
            required: true
        },
        values: {
            type: Object,
            default: () => ({})
        }
    },

    computed: {
        matchedOption() {
            return essentials.helpers.Dynamic.matchOptions(this.$node, this.source);
        }
    },

    methods: {
        edit() {
            this.$trigger('openSourcePanel', {
                name: `yooessentials-source-${essentials.yoo.Builder.id(this.$node)}-${this.field.name}`,
                title: this.field.label || this.field.name,
                props: {
                    node: this.$node,
                    field: this.field,
                    values: this.source.values
                }
            });
        },

        async remap() {
            const path = await api.uikit.promptDropdown(
                FieldsList,
                {
                    base: this.source.basePath,
                    path: this.source.path.replace(this.source.basePath + '.', ''),
                    maxNesting: this.source.queryField ? 0 : 1
                },
                this.$el,
                {
                    flip: false,
                    classes: 'yo-dropdown'
                }
            );

            if (path) {
                this.source.queryField = null;
                this.source.field = path;
            }
        }
    }
};
</script>
