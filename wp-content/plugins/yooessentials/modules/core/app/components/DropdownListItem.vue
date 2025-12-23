<template>
    <NavItem
        class="uk-visible-toggle"
        :active="active"
        :parent="Boolean(children.length)"
        @click="children.length || $emit('select', value)"
    >
        {{ text }} <span v-if="subtext" class="uk-text-muted">{{ subtext }}</span>
        <template v-if="metaList.length" #meta>
            <span v-for="m in metaList" :key="m.value" class="uk-margin-small-right">
                <b v-if="m.text">{{ m.text }}</b>
                <span>{{ m.value }}</span>
            </span>
        </template>

        <!-- <div
            v-if="actions.length"
            class="uk-position-center-right uk-position-medium uk-invisible-hover"
        >
            <ul class="uk-iconnav uk-flex-nowrap">
                <li
                    v-for="action in actions"
                    :key="action.value"
                    :class="{ 'uk-padding-remove': actions.length === 1 }"
                >
                    <a
                        href
                        :uk-icon="action.icon"
                        :title="$t(action.text)"
                        class="uk-icon-link"
                        uk-tooltip="delay: 500"
                        @click.prevent="$emit('action', action.value)"
                    ></a>
                </li>
            </ul>
        </div> -->
    </NavItem>
</template>

<script>
import { isObject } from 'uikit-util';
import NavItem from './NavItem.vue';

export default {
    name: 'DropdownListItem',

    components: {
        NavItem
    },

    props: {
        text: {
            type: String,
            required: true
        },

        subtext: {
            type: String
        },

        value: {
            type: [String, Number]
        },

        active: {
            type: Boolean,
            default: false
        },

        meta: {
            type: [String, Object]
        },

        children: {
            type: Array,
            default: () => []
        },

        actions: {
            type: Array,
            default: () => []
        }
    },

    computed: {
        metaList() {
            if (!this.meta) {
                return [];
            }

            return !isObject(this.meta)
                ? [{ value: this.meta }]
                : Object.keys(this.meta).map((text) => ({ text, value: this.meta[text] }));
        }
    }
};
</script>
