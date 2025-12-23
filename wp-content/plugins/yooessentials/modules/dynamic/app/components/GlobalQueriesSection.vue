<template>
    <div class="uk-margin-remove-first-child">
        <ul
            v-if="!isEmpty(queries)"
            class="uk-nav uk-nav-default yo-sidebar-marginless uk-text-capitalize yo-nav-iconnav"
        >
            <li v-for="query in queries" :key="query.id" class="uk-visible-toggle">
                <a href @click.prevent="editQuery(query)">
                    <div lass="uk-flex">
                        <span>{{ query.name || getQueryLabel(query) }}</span>
                        <div v-if="query.name" class="uk-text-meta">{{ getQueryLabel(query) }}</div>
                    </div>
                </a>

                <button
                    style="padding: 10px 0 10px 10px"
                    class="uk-position-center-right uk-position-medium uk-icon-link uk-invisible-hover"
                    type="button"
                    uk-icon="more-vertical"
                    @click.prevent="edit(query)"
                ></button>
            </li>
        </ul>

        <button
            :class="{ 'uk-margin-medium-top': !isEmpty(queries) }"
            class="uk-button uk-button-default uk-width-1-1"
            type="button"
            @click.prevent="add"
        >
            {{ $t('New Query') }}
        </button>

        <p class="uk-text-muted">{{ $t('Global Queries for the Dynamic Content workflow.') }}</p>
    </div>
</template>

<script>
import api from '@yootheme/api';
import { isEmpty } from 'uikit-util';
import { deepCopy } from '@yooessentials/util';
import essentials from 'yooessentials';
import GlobalQueryModal from './GlobalQueryModal.vue';
import SchemaHelper from '../SchemaHelper';

export default {
    name: 'GlobalQueriesSection',

    props: {
        panel: Object
    },

    computed: {
        queries() {
            return essentials.helpers.Dynamic.queries;
        }
    },

    events: {
        // eslint-disable-next-line no-unused-vars
        yeCopyGlobalQuery(e, { id, ...query }) {
            query.name = query.name ? `${query.name} Copy` : '';
            essentials.helpers.Dynamic.saveGlobalQuery(deepCopy(query));
        },

        yeDeleteGlobalQuery(e, query) {
            essentials.helpers.Dynamic.removeGlobalQuery(query);
        }
    },

    methods: {
        isEmpty,

        getQueryLabel(query) {
            return (
                SchemaHelper.findField(query?.source?.query?.name || '')?.metadata?.label ||
                'Unknown'
            );
        },

        async add() {
            const query = await this.edit({});

            if (!query) {
                return;
            }

            this.editQuery(query);
        },

        async edit(values) {
            const query = await api.uikit.promptModal(GlobalQueryModal, {
                values
            });

            if (!query) {
                return;
            }

            return essentials.helpers.Dynamic.saveGlobalQuery(query);
        },

        editQuery(query) {
            const node = essentials.helpers.Dynamic.getGlobalQuery(query.id);

            if (!node.source) {
                this.$set(node, 'source', {});
            }

            this.$trigger('openDynamicPanel', {
                ...api.customizer.panels['yooessentials-source-query'],
                name: 'yooessentials-source-global-query',
                title: query.name || this.$t('Global Query'),
                width: 500,
                props: {
                    node,
                    values: node.source
                }
            });
        }
    }
};
</script>
