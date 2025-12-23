<template>
    <div class="uk-inline uk-width-1-1">
        <div class="uk-position-center-right uk-position-small">
            <ul class="uk-iconnav uk-flex-nowrap">
                <li>
                    <a
                        tabindex="-1"
                        :title="$t('Pick')"
                        href
                        class="uk-icon-link uk-preserve-width"
                        uk-icon="album"
                        uk-tooltip="delay: 500"
                        @click.prevent="open"
                    ></a>
                </li>
            </ul>
        </div>
        <input
            v-model="value"
            v-bind="attributes"
            class="uk-input yo-input-iconnav-right"
            type="text"
        />
    </div>
</template>

<script>
import fields from '@yootheme/fields';
import { isPlainObject } from 'uikit-util';

export default {
    name: 'File',

    extends: fields.components.FieldText,

    methods: {
        open() {
            this.$trigger('openFilePicker', true).then(this.select);
        },

        select(value) {
            if (isPlainObject(value)) {
                value = value.src;
            }

            if (this.field.mode === 'folder' && !isFolder(value)) {
                return;
            }

            if (this.field.mode === 'file' && isFolder(value)) {
                return;
            }

            this.value = value;
        }
    }
};

function isFolder(value) {
    return value && value.match(/\/[^.]+$/);
}
</script>
