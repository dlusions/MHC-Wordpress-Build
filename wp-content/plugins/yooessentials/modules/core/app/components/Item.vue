<template>
    <li class="uk-visible-toggle" tabindex="-1">
        <a
            href
            class="uk-display-block"
            :class="{ 'uk-disabled': !editable }"
            @click.prevent="$emit('edit')"
        >
            <slot>
                <ul
                    v-if="statusIcons && statusIcons.length"
                    class="uk-grid uk-grid-collapse uk-flex-inline uk-text-bottom"
                >
                    <li v-for="{ component, ...props } in statusIcons" :key="component.name">
                        <component :is="component" :node="$node" v-bind="props" />
                    </li>
                </ul>

                <img
                    v-else-if="icon"
                    class="uk-svg uk-margin-small-right"
                    :alt="$t(title)"
                    :src="icon"
                    width="20"
                    height="20"
                    uk-svg
                />

                <span
                    v-else-if="uikitIcon"
                    :uk-icon="uikitIcon"
                    class="uk-margin-small-right"
                ></span>

                <span
                    :class="['uk-text-truncate uk-text-middle', { 'uk-text-danger': statusError }]"
                    >{{ $t(title || 'Item') }}</span
                >

                <span v-if="$slots.meta" class="uk-text-middle uk-text-meta uk-margin-small-left">
                    <slot name="meta"></slot>
                </span>

                <div v-if="error" class="uk-flex uk-flex-middle uk-margin-small-top">
                    <span class="ye-builder-icon-error"></span>
                    <span class="uk-text-small uk-text-danger uk-margin-small-left uk-text-break">{{
                        error
                    }}</span>
                </div>
            </slot>
        </a>

        <div class="uk-invisible-hover uk-position-center-right uk-position-medium">
            <ul class="uk-iconnav uk-flex-nowrap">
                <li v-if="copyable">
                    <a
                        href
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="copy"
                        @click.prevent="$emit('copy')"
                    ></a>
                </li>
                <li v-if="removable">
                    <a
                        href
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="trash"
                        @click.prevent="$emit('remove')"
                    ></a>
                </li>
            </ul>
        </div>
    </li>
</template>

<script>
export default {
    inject: {
        $node: { default: {} }
    },

    props: {
        title: String,

        error: String,

        statusIcons: Array,

        icon: String,

        uikitIcon: String,

        editable: {
            type: Boolean,
            default: true
        },

        copyable: {
            type: Boolean,
            default: true
        },

        removable: {
            type: Boolean,
            default: true
        }
    },

    computed: {
        statusError() {
            return this.statusIcons?.some(({ error }) => error);
        }
    }
};
</script>
