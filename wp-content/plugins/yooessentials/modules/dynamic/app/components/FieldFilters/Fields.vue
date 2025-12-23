<template>
    <div class="uk-panel uk-margin-small-top">
        <div
            v-for="field in fields"
            v-show="evaluate(field.show)"
            :key="field.name"
            :class="{
                'uk-margin-medium uk-margin-remove-bottom': field.label || field.type === 'grid'
            }"
        >
            <h3 v-if="field.label" class="yo-sidebar-subheading">
                {{ $t(field.label) }}
            </h3>

            <div class="uk-flex uk-flex-column uk-margin-small-top">
                <div>
                    <component
                        :is="field.component"
                        :values="values"
                        :field="
                            merge(field, {
                                attrs: {
                                    class: !validate(field) ? ' uk-form-danger' : ''
                                }
                            })
                        "
                        @change="change"
                    />
                </div>

                <div v-if="field.description" class="uk-text-muted uk-margin-small-top">
                    <span v-html="markdown($t(field.description))"></span>
                    <a
                        v-if="field.reference"
                        target="_blank"
                        :href="field.reference"
                        class="uk-icon-link"
                        ratio="0.7"
                        uk-icon="ye--link-external"
                        >{{ $t('Ref') }}</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import fields from '@yootheme/fields';
import { get } from '@yooessentials/util';
import merge from 'lodash-es/merge';
import isEmpty from 'lodash-es/isEmpty';
import FieldGrid from './FieldsGrid.vue';

export default {
    components: {
        FieldGrid
    },

    extends: fields,

    props: {
        validation: {
            type: Boolean,
            default: true
        }
    },

    methods: {
        merge,
        markdown(v) {
            return v.replace(/`([^`]+)`/g, '<code class="uk-text-nowrap">$1</code>');
        },
        validate(field) {
            if (!this.validation) {
                return true;
            }

            const value = get(this.values, field.name);

            if (field.required && isEmpty(value)) {
                return false;
            }

            return true;
        }
    }
};
</script>
