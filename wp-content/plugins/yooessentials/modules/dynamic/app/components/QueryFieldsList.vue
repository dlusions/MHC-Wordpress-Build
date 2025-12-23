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

                <li v-if="!isRootQuery" class="uk-nav-header uk-padding-remove">
                    <a
                        href
                        class="uk-flex uk-flex-middle uk-text-emphasis"
                        @click.prevent="segments = segments.slice(0, -1)"
                    >
                        <img
                            :uk-svg="`${assetsUrl}/images/field-dynamic-arrow-left.svg`"
                            class="uk-icon uk-margin-xsmall-right"
                        />
                        <span v-if="!isRootQuery">
                            {{ getSegmentField(segments[0])?.metadata?.group || $t('Back') }}
                        </span>
                    </a>
                </li>

                <template v-for="[i, field] in Object.entries(fieldList)">
                    <li
                        v-if="!search && field.group && fields[i - 1]?.group !== field.group"
                        :key="`${field.value}-${field.group}-group`"
                        class="uk-nav-header"
                    >
                        <template v-if="!isRootQuery">
                            {{ getSegmentLabel(segments[0]) }}
                        </template>
                        <template v-else>
                            {{ field.group }}
                        </template>
                    </li>

                    <li
                        :key="`${field.value}-${field.group}`"
                        :class="{ 'uk-active': isSelected(field) }"
                    >
                        <a href @click.prevent="select(field)">
                            <div>
                                <span v-html="field.text"></span>
                                <img
                                    v-if="field.isQueryType"
                                    :uk-svg="`${assetsUrl}/images/field-dynamic-arrow-right.svg`"
                                    class="uk-icon uk-margin-xsmall-left"
                                />
                                <div class="uk-nav-subtitle uk-text-muted">
                                    {{ field.desc }}
                                </div>
                            </div>
                        </a>
                    </li>
                </template>
            </ul>

            <span v-if="!fieldList.length">{{ $t('No source query found.') }}</span>
        </div>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '@yootheme/api';
import { stringIncludes } from '@yooessentials/util';
import SchemaHelper, { join } from '../SchemaHelper';

export default {
    name: 'QueryFieldsList',
    props: {
        node: {
            type: Object,
            required: true
        },
        path: {
            type: String,
            default: () => ''
        },
        options: {
            type: Array,
            default: () => []
        },
        isSelected: {
            type: Function,
            default: (field) => false
        },
        additionalFields: {
            type: Array,
            default: () => []
        },
        builder: {
            type: Object
        },
        maxHeight: {
            type: String,
            default: 'large'
        }
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
            return SchemaHelper.findField(this.segments);
        },
        isRootQuery() {
            return this.segments.length === 0;
        },
        queries() {
            return this.options
                .filter((option) => option.queries)
                .reduce(
                    (opts, option) => [
                        ...opts,
                        ...option
                            .queries({ node: this.node })
                            .map((field) => ({ ...field, option }))
                            .sort((a, b) =>
                                (a.metadata?.group || '').localeCompare(b.metadata?.group || '')
                            )
                    ],
                    []
                );
        },
        fields() {
            const { Schema } = api.builder.helpers;

            const fields = this.basefield
                ? Schema.getFieldType(this.basefield)?.fields?.filter(this.isQueryField)
                : this.queries;

            // console.log(this.queries);

            return fields.map((f) => {
                return {
                    value: f.name,
                    path: join(this.segments, f.name),
                    option: f.option,
                    text: f.metadata?.label,
                    group: f.metadata?.group,
                    desc: f.metadata?.description,
                    metadata: f.metadata,
                    isQueryType: this.isQueryType(f)
                };
            });
        },
        fieldList() {
            return this.fields.filter(
                ({ text = '', meta = '', group = '' }) =>
                    stringIncludes(text, this.search) ||
                    stringIncludes(meta, this.search) ||
                    stringIncludes(group, this.search)
            );
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
        this.segments = this.path.split('.').filter(Boolean).slice(0, -1);
    },
    methods: {
        select(field) {
            if (field.isQueryType) {
                this.segments = this.segments.concat(field.value);
                return;
            }

            this.$emit('resolve', [
                field.value ? join(this.segments, field.value) : field.value,
                field.option
            ]);
        },
        isQueryType(field) {
            const { Schema } = api.builder.helpers;
            const typeName = field?.type?.name || field?.ofType?.name;
            const isType = Schema.getFieldType(field)?.metadata?.type === true;
            const isRootField = Schema.rootQueryType.fields.some(
                ({ type }) => type?.name === typeName
            );

            // if field is part of the root queries
            return !isType && isRootField;
        },
        isQueryField(field) {
            const nonQueryGroups = {
                Page: Vue.i18n.t('Page'),
                Parent: Vue.i18n.t('Parent'),
                Global: Vue.i18n.t('Global'),
                Submission: Vue.i18n.t('Submission')
            };

            // has group and is none of the above
            return (
                field?.metadata?.group &&
                !Object.values(nonQueryGroups).includes(field?.metadata?.group)
            );
        },
        getSegmentField(segment) {
            return SchemaHelper.findField(segment);
        },
        getSegmentLabel(segment) {
            const field = this.getSegmentField(segment);
            return field?.metadata?.label || field?.name;
        }
    }
};
</script>
