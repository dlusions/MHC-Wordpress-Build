<template>
    <div>
        <DropdownList :entries="entriesList">
            <template #entry="{ entry }">
                <li
                    class="uk-visible-toggle uk-position-relative"
                    @click.prevent="$emit('resolve', entry)"
                >
                    <a style="cursor: default !important">
                        {{ entry.text }}
                        <span v-if="entry.meta" class="uk-text-meta uk-margin-small-left">{{
                            entry.meta
                        }}</span>
                    </a>

                    <!-- <div class="uk-position-center-right uk-position-small">
                        <ul class="uk-iconnav uk-flex-nowrap">
                            <li class="uk-invisible-hover">
                                <a href :title="$t('Copy')" class="uk-icon-link uk-preserve-width" uk-icon="pull" uk-tooltip="delay: 500" @click.prevent=""></a>
                            </li>
                        </ul>
                    </div> -->
                </li>
            </template>
        </DropdownList>
    </div>
</template>

<script>
import { DropdownList } from '@yooessentials/components';

export default {
    name: 'FormPlaceholderDrop',

    components: {
        DropdownList
    },

    props: {
        entries: Array
    },

    computed: {
        entriesList() {
            return this.entries.map((ctrl) => ({ ...ctrl, group: 'Form' }));
        }
    }
};
</script>
