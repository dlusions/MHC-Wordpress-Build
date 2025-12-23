<template>
    <div>
        <div class="yo-dropdown-header">
            <div class="uk-search uk-search-default uk-width-1-1">
                <input
                    ref="search"
                    v-model="search"
                    :placeholder="$t('Search')"
                    class="uk-search-input"
                    type="search"
                    autofocus
                />
                <span class="uk-search-icon-flip" uk-search-icon></span>
            </div>
        </div>

        <div
            ref="scrollEl"
            class="yo-dropdown-body uk-overflow-auto"
            :class="`uk-height-max-${maxHeight}`"
        >
            <ul class="uk-nav uk-dropdown-nav">
                <template v-for="field in additionalFields">
                    <template v-if="field.label">
                        <li :key="`label-${field.label}`" class="uk-nav-header">
                            {{ field.label }}
                        </li>
                        <li v-for="option in field.options" :key="option.value">
                            <a href @click.prevent="select(option)">{{ option.text }}</a>
                        </li>
                    </template>
                    <li v-else :key="field.value">
                        <a href @click.prevent="select(field)">{{ field.text }}</a>
                    </li>
                </template>

                <li v-if="segments.length" class="uk-nav-header uk-padding-remove">
                    <a
                        href
                        class="uk-flex uk-flex-middle uk-text-emphasis"
                        @click.prevent="segments = segments.slice(0, -1)"
                    >
                        <img
                            :uk-svg="`${assetsUrl}/images/field-dynamic-arrow-left.svg`"
                            class="uk-icon uk-margin-xsmall-right"
                        />
                        <span>{{ backButtonLabel }}</span>
                    </a>
                </li>

                <li class="uk-nav-header">
                    {{ toFieldLabel(basefield) }}
                </li>

                <template v-for="[i, field] in Object.entries(fieldList)">
                    <li
                        v-if="toSubgroup(field) && toSubgroup(fields[i - 1]) !== toSubgroup(field)"
                        :key="`${field.value}-label`"
                        class="yo-nav-subheader"
                    >
                        {{ toSubgroup(field) }}
                    </li>
                    <li :key="field.value" :class="{ 'uk-active': selectedField === field }">
                        <a
                            href
                            :class="{
                                'uk-disabled uk-text-muted':
                                    maxNestingReached && field._field.type.kind === 'LIST'
                            }"
                            @click.prevent="select(field)"
                        >
                            <div>
                                <span class="uk-text-nowrap" v-html="field.text"></span>
                                <img
                                    v-if="
                                        field._field.type.kind.match(/LIST|OBJECT/) &&
                                        !resolvable(field._field.type)
                                    "
                                    :uk-svg="`${assetsUrl}/images/field-dynamic-arrow-right.svg`"
                                    class="uk-icon uk-margin-xsmall-left"
                                />
                                <div class="uk-nav-subtitle uk-text-muted">
                                    {{ field.meta }}
                                    {{
                                        field._field?.type?.kind === 'LIST'
                                            ? $t('List of %type%', { type: field._dataType })
                                            : field._dataType
                                    }}
                                </div>
                            </div>
                        </a>
                    </li>
                </template>
            </ul>

            <span v-if="!fieldList.length">{{ $t('No source field found.') }}</span>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '@yootheme/api';
import { stringIncludes } from '@yooessentials/util';
import SchemaHelper, { join } from '../SchemaHelper';

export default {
    props: {
        base: {
            type: String,
            required: true
        },
        path: {
            type: String,
            default: ''
        },
        resolvable: {
            type: Function,
            default: (type) => type.kind === 'SCALAR'
        },
        maxHeight: {
            type: String,
            default: 'large'
        },
        maxNesting: {
            type: Number,
            default: 1
        },
        fieldType: String,
        additionalFields: Array
    },
    data: () => ({
        search: '',
        segments: []
    }),
    computed: {
        assetsUrl() {
            return api.config.assets;
        },
        basefield() {
            return SchemaHelper.findField(this.segments, this.base);
        },
        selectedField() {
            return this.fields.find((field) => {
                return join(this.base, this.path) === join(this.base, this.segments, field.value);
            });
        },
        maxNestingReached() {
            // if total nesting (multi in a multi) reached, disable further LIST types
            const nesting = SchemaHelper.resolvePath(join(this.segments), this.base).filter(
                ({ field }) => SchemaHelper.isList(field)
            ).length;

            return nesting === this.maxNesting;
        },
        fields() {
            if (!this.basefield) {
                return [];
            }

            const fields =
                this.fieldType || this.maxNestingReached
                    ? SchemaHelper.getFieldsByType(this.basefield, this.fieldType)
                    : SchemaHelper.getFields(this.basefield);

            return fields
                .map((field) => ({
                    ...field,
                    _type: SchemaHelper.getFieldType(field._field),
                    _dataType: SchemaHelper.getFieldDataType(field._field)
                }))
                .sort((a, b) => (this.toSubgroup(a) || '').localeCompare(this.toSubgroup(b) || ''));
        },
        fieldList() {
            return this.fields.filter(
                ({ text, meta = '', group = '' }) =>
                    stringIncludes(text, this.search) ||
                    stringIncludes(meta, this.search) ||
                    stringIncludes(group, this.search)
            );
        },
        backButtonLabel() {
            const segment = this.segments.slice(0, -1);
            const field = SchemaHelper.findField(segment, this.base);
            const label = this.toFieldLabel(field);

            let length = 0;

            for (const segment of [...this.segments].reverse()) {
                if (segment !== field?.name) break;
                length++;
            }

            return length > 1 ? Vue.i18n.t('%label% (%depth%)', { label, depth: length }) : label;
        }
    },
    watch: {
        segments() {
            this.search = '';
            this.$refs.search.focus();
            this.$refs.scrollEl.scrollTop = 0;
        }
    },
    created() {
        this.segments = this.segmentPath(this.path);
    },
    methods: {
        select(field) {
            if (field._field && !this.resolvable(field._field.type)) {
                this.segments = this.segments.concat(field.value);
                return;
            }

            this.$emit('resolve', field.value && join(this.segments, field.value));
        },
        toSubgroup(field) {
            const { Schema } = api.builder.helpers;

            for (const [{ metadata }] of Schema.resolvePath(
                field?.value,
                join(this.base, this.segments)
            )) {
                if (metadata?.group) {
                    return metadata.group;
                }
            }
        },
        toFieldLabel(field) {
            const type = SchemaHelper.getFieldType(field);
            return type?.metadata?.label || type?.name || field?.name;
        },
        segmentPath(path) {
            const { Schema } = api.builder.helpers;

            const segments = [];

            let parts = [];
            for (const [field, type] of Schema.resolvePath(path, this.base)) {
                parts.push(field.name);

                if (
                    !this.resolvable(field.type) &&
                    field.type.kind.match(/OBJECT|LIST/) &&
                    type.metadata?.type
                ) {
                    segments.push(join(parts));
                    parts = [];
                }
            }

            return segments;
        }
    }
};
</script>
