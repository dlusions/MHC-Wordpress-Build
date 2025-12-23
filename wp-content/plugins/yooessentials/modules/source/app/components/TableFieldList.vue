<template>
    <div>
        <div class="yo-dropdown-header uk-flex uk-flex-middle">
            <div class="uk-search uk-search-default uk-width-1-1">
                <span class="uk-search-icon" uk-search-icon></span>
                <input
                    ref="search"
                    v-model="search"
                    :placeholder="$t('Search')"
                    class="uk-search-input"
                    type="search"
                    autofocus
                />
            </div>

            <a
                href=""
                uk-icon="refresh"
                class="uk-text-muted"
                @click.prevent="$emit('refresh')"
            ></a>
        </div>

        <div class="yo-dropdown-body uk-overflow-auto uk-height-max-medium">
            <ul v-if="fieldList.length" class="uk-nav uk-dropdown-nav">
                <li v-for="field in fieldList" :key="field" @click.prevent="select(field)">
                    <a href>{{ field }}</a>
                </li>
            </ul>

            <span v-else>{{ $t('No fields found.') }}</span>
        </div>
    </div>
</template>

<script>
import { stringIncludes } from '@yooessentials/util';

export default {
    props: {
        fields: {
            type: Array,
            default: () => [],
        },
    },

    data: () => ({ search: '' }),

    computed: {
        fieldList() {
            return this.fields.filter((field) => stringIncludes(field, this.search));
        },
    },

    methods: {
        select(value) {
            this.$emit('resolve', value);
        },
    },
};
</script>
