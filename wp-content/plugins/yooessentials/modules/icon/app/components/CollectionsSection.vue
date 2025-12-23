<template>
    <div class="uk-margin-remove-first-child">
        <ul
            v-if="!isEmpty(installed)"
            class="uk-nav uk-nav-default yo-sidebar-marginless yo-nav-iconnav uk-margin uk-margin-remove-bottom"
        >
            <ResourceListItem
                v-for="{ collection, title, icon, meta, actions } in installed"
                :key="collection.id"
                :title="title"
                :icon="icon"
                :meta="meta"
                :actions="actions"
                @delete="remove(collection)"
            />
        </ul>

        <button
            class="uk-button uk-button-default uk-width-1-1 uk-margin-medium-top"
            type="button"
            @click="add"
        >
            {{ $t('Add Collection') }}
        </button>

        <p class="uk-text-muted">
            {{
                $t(
                    'Add a preset Icon Collection to the icon library. For custom collections consult the documentation.'
                )
            }}
        </p>
    </div>
</template>

<script>
import api from '@yootheme/api';
import essentials from 'yooessentials';
import { isEmpty } from 'uikit-util';
import { ResourceListItem, ResourcePicker } from '@yooessentials/components';
import { semverCompare } from '@yooessentials/util';

export default {
    name: 'CollectionsSection',

    components: {
        ResourceListItem
    },

    props: {
        panel: Object
    },

    computed: {
        installed() {
            return essentials.helpers.Icons.collections
                .filter((c) => c.version && c.installed)
                .map((collection) => {
                    return {
                        collection,
                        title: collection?.title,
                        meta: `v${collection?.installed}`,
                        icon: collection?.icon,
                        actions: ['delete']
                    };
                });
        }
    },

    methods: {
        isEmpty,

        remove(collection) {
            essentials.helpers.Icons.removeCollection(collection);
        },

        async add() {
            const collection = await api.uikit.promptModal(
                ResourcePicker,
                {
                    items: essentials.helpers.Icons.collections
                        .filter((c) => c.package)
                        .map((c) => ({
                            ...c,
                            disabled: Boolean(c.installed),
                            description: c.installed ? 'Installed' : c.description
                        }))
                },
                { container: true }
            );

            if (collection) {
                essentials.helpers.Icons.addCollection(collection);
            }
        },

        isUpToDate(collection) {
            return semverCompare(collection.installed, collection.version) >= 0;
        }
    }
};
</script>
