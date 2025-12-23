<template>
    <div
        :class="[
            'uk-cursor-default uk-inline uk-select',
            attributes.class,
            { 'uk-form-danger': source.query && !source.isQueryValid }
        ]"
        @click.prevent="select"
    >
        <div class="uk-disabled uk-text-truncate">
            <span v-html="source.queryOverview"></span>
            <span v-if="source.querySchemaType" class="uk-text-meta">{{
                source.querySchemaType?.metadata?.label
            }}</span>
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import essentials from 'yooessentials';
import { QueryFieldsList } from '../components';
import SourceHelper from '../SourceHelper';
import Source from '../Source';

export default {
    name: 'YooessentialsSourceQuery',

    extends: fields.components.FieldText,

    inject: {
        $node: {},
        $source: {
            default: null
        }
    },

    computed: {
        source() {
            return new Source(this.$source ?? this.$node.source, this.$node);
        },

        matchedOption() {
            return essentials.helpers.Dynamic.matchOptions(this.$node, this.source);
        },

        omittedOptions() {
            const omitted = this.field.omittedOptions || [];

            if (this.$source) {
                return [...omitted, 'composed'];
            }

            return [...omitted, 'node', 'composed'];
        },

        nullable() {
            return !this.$source;
        },

        value: {
            get() {
                if (this.$source) {
                    return this.source.query;
                }

                return SourceHelper.getQuery(this.$node);
            },

            set(name) {
                if (this.$source) {
                    this.source.query = name;
                } else {
                    SourceHelper.setQuery(this.$node, name);
                }
            }
        }
    },

    methods: {
        async select() {
            const nullable = this.nullable && this.source.query;
            let path = this.value;

            // when source is inheriting limit the path to root
            if (this.source.isInheriting) {
                path = '';
            }

            // when source query is not valid, reset path
            if (!this.source.isQueryValid) {
                path = '';
            }

            const [name, option] =
                (await api.uikit.promptDropdown(
                    QueryFieldsList,
                    {
                        path,
                        node: this.$node,
                        isSelected: (field) => {
                            const query = this.source.values.query?.name ?? '';
                            return query === field.path || query.startsWith(field.path + '.');
                        },
                        options: essentials.helpers.Dynamic.matchOptions(this.$node).filter(
                            (opt) => !this.omittedOptions.includes(opt.name)
                        ),
                        additionalFields: [
                            nullable ? { text: this.$t('None'), value: '' } : null
                        ].filter(Boolean)
                    },
                    this.$el,
                    { flip: false, classes: 'yo-dropdown' }
                )) || [];

            if (name !== undefined) {
                if (option?.directResolve) {
                    option.directResolve(this.$node, null, this.value);
                } else {
                    this.value = name;
                }

                if (option?.resolve) {
                    option.resolve(this.value);
                }
            }
        }
    }
};
</script>
