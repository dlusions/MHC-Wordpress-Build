<template>
    <input v-model="val" v-bind="attributes" class="uk-input" type="number" />
</template>

<script>
import fields from '@yootheme/fields';
import { isNumeric, toFloat } from 'uikit-util';

export default {
    extends: fields.components.FieldText,

    computed: {
        mod() {
            return toFloat(this.field.modifier || 0);
        },

        val: {
            get() {
                return isNumeric(this.value) ? toFloat(this.value) + this.mod : this.value;
            },

            set(value) {
                this.value = isNumeric(value) ? toFloat(value) - this.mod : undefined;
            }
        }
    }
};
</script>
