<template>
    <div>
        <template v-if="sources.length > 10">
            <!-- <div class="uk-search uk-search-default uk-width-1-1">
                <span key="search" class="uk-search-icon-flip" uk-search-icon></span>
                <input ref="search" v-model="search" autofocus :placeholder="$t('Search')" class="uk-search-input" type="search">
            </div> -->

            <div class="uk-flex uk-flex-middle">
                <div class="uk-search uk-search-default uk-flex-1 uk-margin-right">
                    <input
                        ref="search"
                        v-model="search"
                        :placeholder="$t('Search')"
                        class="uk-search-input"
                        type="search"
                        autofocus
                    />
                </div>
                <div class="uk-flex uk-flex-right uk-flex-middle">
                    <button
                        type="button"
                        class="uk-button uk-button-default uk-button-small"
                        @click.prevent="add"
                    >
                        {{ $t('Add') }}
                    </button>
                </div>
            </div>

            <hr class="uk-margin-remove" />
        </template>

        <div>
            <ul
                class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-iconnav uk-margin uk-margin-remove-bottom"
            >
                <ResourceListItem
                    v-for="{ source, title, icon, meta, error, actions, disabled } in filtered"
                    :key="source.id"
                    :title="title"
                    :icon="icon"
                    :meta="meta"
                    :actions="actions"
                    :error="error"
                    :disabled="disabled"
                    :editable="!Boolean(error)"
                    @edit="edit(source)"
                    @copy="copy(source)"
                    @delete="remove(source)"
                >
                    <template v-if="error" #icon>
                        <span uk-icon="question" ratio="1.2" class="uk-margin-small-right"></span>
                    </template>
                </ResourceListItem>
            </ul>

            <div v-if="search && isEmpty(filtered)" class="uk-text-muted uk-margin">
                {{ $t('No Sources Found.') }}
            </div>
        </div>

        <button
            :class="{ 'uk-margin-medium-top': !isEmpty(sources) }"
            class="uk-button uk-button-default uk-width-1-1"
            type="button"
            @click.prevent="add"
        >
            {{ $t('Add Source') }}
        </button>

        <p class="uk-text-muted">
            {{
                $t(
                    'Add a new source instance that will become part of the Dynamic Content workflow.'
                )
            }}
        </p>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '@yootheme/api';
import essentials from 'yooessentials';
import { isEmpty } from 'uikit-util';
import { deepCopy, stringIncludes } from '@yooessentials/util';
import { ModalForm, ResourceListItem, ResourcePicker } from '@yooessentials/components';

export default {
    name: 'SourcesSection',

    components: {
        ResourceListItem
    },

    data: () => ({
        search: ''
    }),

    computed: {
        providers() {
            return essentials.helpers.Sources.providers;
        },

        sources() {
            return essentials.helpers.Sources.sources.map((source) => {
                const provider = this.providers[source.provider];
                const title =
                    source.name || provider?.title || source.provider || this.$t('Unknown');

                const error = !provider ? this.$t('Invalid Source') : '';

                return {
                    source,
                    error,
                    title,
                    disabled: source.status === 'disabled',
                    meta: provider?.title || provider,
                    icon: provider?.icon,
                    actions: error ? ['delete'] : ['copy', 'delete']
                };
            });
        },

        filtered() {
            return this.sources.filter(
                ({ title, source: { provider } }) =>
                    stringIncludes(title, this.search) || stringIncludes(provider, this.search)
            );
        }
    },

    methods: {
        isEmpty,

        async add() {
            const provider = await api.uikit.promptModal(
                ResourcePicker,
                { items: Object.values(this.providers) },
                { container: true }
            );

            if (provider) {
                this.edit(Vue.observable({ provider: provider.name }));
            }
        },

        async edit(source) {
            const panel = this.providers[source.provider];

            const values = await api.uikit.promptModal(ModalForm, { values: source, panel });

            if (values) {
                essentials.helpers.Sources.save(values);
            }
        },

        // eslint-disable-next-line no-unused-vars
        copy({ id, ...source }) {
            source.name = source.name ? `${source.name} Copy` : '';
            essentials.helpers.Sources.save(deepCopy(source));
        },

        remove(source) {
            essentials.helpers.Sources.remove(source);
        }
    }
};
</script>
