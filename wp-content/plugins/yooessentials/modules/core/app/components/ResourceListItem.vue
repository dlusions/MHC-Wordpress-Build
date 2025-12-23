<template>
    <li class="uk-visible-toggle yooessentials-resource-item" tabindex="-1">
        <a
            href
            :class="{
                'uk-disabled': !editable,
                'uk-text-muted': error && !editable
            }"
            class="uk-flex uk-flex-middle uk-position-relative"
            @click.prevent="$emit('edit')"
        >
            <slot name="icon" v-bind="$props">
                <span v-if="icon" class="uk-margin-small-right">
                    <img
                        class="uk-svg"
                        :alt="$t(title || 'Item')"
                        :src="icon"
                        width="28"
                        height="28"
                        uk-svg
                    />
                </span>
            </slot>

            <div class="uk-width-5-6">
                <div class="uk-text-truncate uk-flex uk-flex-middle">
                    {{ title || $t('Item') }}
                    <img
                        v-if="disabled"
                        :uk-svg="`${api.config.assets}/images/builder/disabled.svg`"
                        class="uk-text-danger uk-margin-small-left"
                    />
                </div>
                <div class="uk-text-truncate">
                    <div v-if="error" class="uk-text-meta uk-text-danger">
                        {{ error }}
                    </div>

                    <slot v-if="meta || $slots.meta" name="meta" :meta="meta">
                        <span v-for="m in prepareMeta(meta)" :key="m.value">
                            <span v-if="m.text" class="uk-text-small">{{ m.text }}</span>
                            <span class="uk-text-meta">{{ m.value }}</span>
                        </span>
                    </slot>
                </div>
            </div>
        </a>

        <div class="uk-position-center-right uk-position-medium" style="padding-right: 5px">
            <ul class="uk-iconnav uk-flex-nowrap">
                <slot name="actions"></slot>

                <li v-for="indication in indications" :key="indication.title">
                    <span
                        :title="$t(indication.title)"
                        :class="[indication.class, 'uk-preserve-width']"
                        :uk-icon="indication.icon"
                        uk-tooltip="delay: 500"
                    ></span>
                </li>

                <ResourceListItemAction
                    v-for="(action, i) in actionsList"
                    :key="action.key || i"
                    v-bind="action"
                    @click="$emit(action.emit)"
                />
            </ul>
        </div>
    </li>
</template>

<script>
import api from '@yootheme/api';
import { isObject } from 'uikit-util';
import ResourceListItemAction from './ResourceListItemAction.vue';

export default {
    name: 'ResourceListItem',

    components: {
        ResourceListItemAction
    },

    props: {
        title: String,

        icon: String,

        status: Object,

        meta: String,

        error: [String, Boolean],

        disabled: Boolean,

        editable: {
            type: Boolean,
            default: true
        },

        actions: {
            type: Array,
            default: () => []
        },

        indications: {
            type: Array,
            default: () => []
        }
    },

    computed: {
        api() {
            return api;
        },

        actionsList() {
            const actions = {
                copy: { title: 'Copy', emit: 'copy', icon: 'copy' },
                delete: { title: 'Delete', emit: 'delete', icon: 'trash' }
            };

            return this.actions.map((action) => actions[action] || action).filter(Boolean);
        }
    },

    methods: {
        prepareMeta(meta) {
            return !isObject(meta)
                ? [{ value: meta }]
                : Object.keys(meta).map((text) => ({ text, value: meta[text] }));
        }
    }
};
</script>
