<template>
    <div>
        <input v-model="value" type="text" class="uk-input" :class="{ 'uk-form-danger': error }" />
        <span v-if="error" class="uk-text-meta">{{ $t(error) }}</span>
    </div>
</template>

<script>
import fields from '@yootheme/fields';
import { getClosestFormArea, getFormFields } from '../helper';

const errors = {
    'duplicated-control': 'Name already assigned to another field.',
    'invalid-chars': 'Name contains invalid characters.'
};

export default {
    extends: fields.components.FieldText,

    inject: ['$node'],

    computed: {
        error() {
            if (!this.value) {
                return false;
            }

            if (this.value.match(/[^a-zA-Z0-9_\-[\]]/)) {
                return errors['invalid-chars'];
            }

            const formArea = getClosestFormArea(this.$node);
            const fields = getFormFields(formArea);
            const controls = fields.map((field) => field.props?.control_name);

            if (controls.filter((name) => name === this.value).length > 1) {
                return errors['duplicated-control'];
            }

            return false;
        }
    }
};
</script>
