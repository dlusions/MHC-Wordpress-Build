<template>
    <input v-if="attributes.disabled" disabled class="uk-input" />

    <div v-else class="uk-inline uk-width-1-1">
        <input
            v-if="togglable && hidden"
            v-model="draft"
            class="uk-input"
            :placeholder="'*'.repeat(value?.length)"
            style="padding-right: 35px"
            type="text"
            v-bind="attributes"
        />

        <input
            v-else-if="togglable"
            v-model="value"
            class="uk-input"
            :placeholder="'*'.repeat(value?.length)"
            style="padding-right: 35px"
            type="text"
            v-bind="attributes"
        />

        <input
            v-else
            v-model="draft"
            class="uk-input"
            type="text"
            :placeholder="'*'.repeat(value?.length)"
            :style="togglable ? 'padding-right: 40px' : ''"
            v-bind="attributes"
        />

        <div v-if="togglable" class="uk-position-center-right uk-position-small">
            <ul class="uk-iconnav">
                <li>
                    <a
                        :key="hidden"
                        tabindex="-1"
                        :title="$t(hidden ? 'Show' : 'Hide')"
                        uk-tooltip="delay: 500"
                        class="uk-icon-link uk-preserve-width"
                        :uk-icon="hidden ? 'eye' : 'eye-slash'"
                        @click.prevent="hidden = !hidden"
                    ></a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import fields from '@yootheme/fields';

export default {
    name: 'Secret',

    extends: fields.components.FieldText,

    data: () => ({
        hidden: true,
        draft: '',
        copy: ''
    }),

    computed: {
        togglable() {
            return this.field.togglable || false;
        }
    },

    watch: {
        draft(draft) {
            this.value = this.hidden ? draft || this.copy : draft;
        },

        hidden(hidden) {
            if (hidden) {
                this.copy = this.value;
            }
        }
    },

    created() {
        this.copy = this.value;

        if (!this.value) {
            this.hidden = false;
        }
    }
};
</script>
