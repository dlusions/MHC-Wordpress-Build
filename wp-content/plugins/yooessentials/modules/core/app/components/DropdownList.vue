<template>
    <Dropdown :max-height="maxHeight">
        <template v-if="showHeader" #header>
            <slot name="header">
                <div class="uk-flex uk-flex-middle uk-flex-between uk-height-1-1">
                    <slot v-if="!searching" name="nav"></slot>

                    <div class="uk-search uk-search-default uk-flex-1">
                        <input
                            v-if="searching || !$slots.nav"
                            ref="search"
                            :value="search"
                            :placeholder="$t('Search')"
                            class="uk-search-input uk-padding-remove"
                            type="search"
                            autofocus
                            @input="(e) => (search = e.target.value)"
                        />
                    </div>
                    <span
                        v-if="fetching"
                        key="spinner"
                        class="uk-margin-right-small"
                        uk-spinner="ratio: 0.5"
                    ></span>
                    <a
                        v-else-if="searching && search"
                        class="uk-search-icon-flip"
                        uk-icon="close"
                        ratio="0.9"
                        @click.prevent="
                            searching = false;
                            search = '';
                        "
                    ></a>
                    <span
                        v-else-if="searching || !$slots.nav"
                        key="search-icon"
                        class="uk-search-icon-flip"
                        uk-search-icon
                        @click.prevent="doSearch"
                    ></span>
                    <a
                        v-else
                        key="search-action"
                        class="uk-search-icon-flip"
                        uk-search-icon
                        @click.prevent="doSearch"
                    ></a>
                </div>
            </slot>
        </template>

        <template #body>
            <Nav v-if="Object.keys(entriesList).length" dropdown>
                <template v-for="(group, label) in entriesList">
                    <NavDivider v-if="!isFirstGroup(label)" :key="`${label}-divider`" />
                    <NavHeader
                        v-if="label && showGroupLabel"
                        :key="`${label}-label`"
                        :label="label"
                    />
                    <slot v-for="(entry, j) in group" name="entry" :entry="entry">
                        <NavHeader
                            v-if="entry.subgroup && group[j - 1]?.subgroup !== entry.subgroup"
                            :key="`${entry.value}-label`"
                            :label="entry.subgroup"
                            sub
                        />
                        <DropdownListItem
                            :key="`${entry.group}${j}`"
                            v-bind="entry"
                            :active="entry === selectedEntry"
                            @select="resolve(entry)"
                        />
                    </slot>
                </template>
            </Nav>

            <span v-else-if="fetching">...</span>
            <span v-else>{{ $t('No entries found.') }}</span>
        </template>

        <template #footer>
            <slot name="footer"></slot>
        </template>
    </Dropdown>
</template>

<script>
import { groupBy } from '../util';
import Dropdown from './Dropdown.vue';
import Nav from './Nav.vue';
import NavHeader from './NavHeader.vue';
import NavDivider from './NavDivider.vue';
import DropdownListItem from './DropdownListItem.vue';

export default {
    name: 'DropdownList',

    components: {
        Dropdown,
        Nav,
        NavHeader,
        NavDivider,
        DropdownListItem
    },

    props: {
        entries: {
            type: Array,
            default: () => []
        },

        selected: {
            type: String
        },

        fetching: {
            type: Boolean,
            default: false
        },

        async: {
            type: Boolean,
            default: false
        },

        showHeader: {
            type: Boolean,
            default: true
        },

        showGroupLabel: {
            type: Boolean,
            default: true
        },

        maxHeight: {
            type: String,
            default: 'medium'
        }
    },

    data: () => ({
        search: '',
        searching: false,
        selectedEntry: null
    }),

    computed: {
        entriesList() {
            let entries = [...this.entries];

            // support sync search
            if (this.search && !this.async) {
                entries = entries
                    .map((e) => {
                        const search = this.search.split(' ').filter(Boolean);

                        // custom terms supported, fallback to default
                        const terms = (e.terms || [e.text, e.meta, e.group])
                            .join(' ')
                            .toLowerCase();

                        let match = search.reduce((acc, keyword) => {
                            if (terms.includes(keyword.toLowerCase())) {
                                // first terms higher relevance
                                const relevance = [...search].reverse().indexOf(keyword);
                                acc.push(relevance + 1);
                            }

                            return acc;
                        }, []);

                        // match all keywords
                        if (match.length === search.length) {
                            match = match.reduce((a, b) => a + b);
                        } else {
                            match = 0;
                        }

                        return { ...e, match };
                    })
                    .filter((e) => e.match > 0)
                    .sort((a, b) => b.match - a.match);
            }

            // sort subgroup entries to the end
            entries.sort((a, b) => (a.subgroup === b.subgroup ? 0 : a.subgroup ? 1 : -1));

            entries = groupBy(
                entries.map((entry) => ({ group: '', ...entry })),
                'group'
            );

            return entries;
        }
    },

    watch: {
        entries() {
            if (!this.async) {
                this.search = '';
            }
        },

        search(value) {
            this.$emit('search', value);
        }
    },

    created() {
        if (this.selected) {
            this.selectedEntry = this.entries.find((entry) => entry.value === this.selected);
        }

        if (!this.$slots.nav) {
            this.searching = true;
        }
    },

    mounted() {
        this.$nextTick(() => this.$refs.search?.focus());

        window.addEventListener('keydown', this.handleGlobalKeydown);
    },

    beforeDestroy() {
        window.removeEventListener('keydown', this.handleGlobalKeydown);
    },

    methods: {
        resolve(entry) {
            entry.value && this.$emit('resolve', entry.value);
        },
        doSearch() {
            this.searching = true;
            this.$nextTick(() => this.$refs?.search?.focus());
        },
        handleGlobalKeydown(e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();

                const entries = Object.values(this.entriesList).flat();
                const maxIndex = entries.length - 1;
                const currentIndex = entries.findIndex((entry) => entry === this.selectedEntry);
                const nextIndex =
                    e.key === 'ArrowDown'
                        ? (currentIndex + 1) % (maxIndex + 1)
                        : currentIndex - 1 < 0
                          ? maxIndex
                          : currentIndex - 1;

                this.selectedEntry = entries[nextIndex];
            }

            if (e.key === 'Enter' || e.key === 'ArrowRight') {
                e.preventDefault();

                if (this.selectedEntry) {
                    this.resolve(this.selectedEntry);
                }
            }
        },
        isFirstGroup(group) {
            return Object.keys(this.entriesList).indexOf(group) === 0;
        }
    }
};
</script>
