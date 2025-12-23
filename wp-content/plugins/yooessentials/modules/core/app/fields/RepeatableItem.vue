<template>
    <li class="uk-visible-toggle" tabindex="-1">
        <div>
            <slot :values="values"></slot>
        </div>

        <hr v-if="separator" class="uk-margin-small-bottom" />

        <div class="uk-flex uk-flex-between" :class="{ 'uk-margin-small-top': !separator }">
            <div>
                <button
                    v-if="index + 1 === total"
                    type="button"
                    class="uk-button uk-button-small uk-button-default"
                    @click.prevent="$emit('add')"
                >
                    {{ $t(txtAdd) }}
                </button>
            </div>

            <ul class="uk-grid uk-grid-collapse uk-invisible-hover">
                <li key="delete">
                    <a
                        href
                        :title="$t('Delete')"
                        uk-tooltip="delay: 500"
                        class="yo-builder-icon-delete"
                        @click.prevent="$emit('remove')"
                    ></a>
                </li>
                <li v-if="orderable && index !== 0" key="moveup">
                    <a
                        href
                        :title="$t('Move Up')"
                        uk-tooltip="delay: 500"
                        uk-icon="arrow-up"
                        class="uk-icon-link"
                        @click.prevent="$emit('move', -1)"
                    ></a>
                </li>
                <li v-if="orderable && index + 1 !== total" key="movedown">
                    <a
                        href
                        :title="$t('Move Down')"
                        uk-tooltip="delay: 500"
                        uk-icon="arrow-down"
                        class="uk-icon-link"
                        @click.prevent="$emit('move', 1)"
                    ></a>
                </li>
            </ul>
        </div>
    </li>
</template>

<script>
export default {
    name: 'RepeatableItem',

    props: {
        total: {
            type: Number,
            required: true
        },

        values: {
            type: Object,
            required: true
        },

        orderable: {
            type: Boolean,
            default: true
        },

        separator: {
            type: Boolean,
            default: false
        },

        txtAdd: {
            type: String,
            default: 'Add'
        }
    },

    computed: {
        index() {
            return this.$vnode.key;
        }
    }
};
</script>
