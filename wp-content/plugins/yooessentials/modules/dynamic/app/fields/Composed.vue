<template>
    <div class="ye-composed-field uk-position-relative uk-textarea uk-padding-remove">
        <ComposedEditor
            v-model="value"
            :field="$field"
            :sources="values.sources"
            :conditions="values.conditions"
            multiline
        />
    </div>
</template>

<script>
import Vue from 'vue';
import fields from '@yootheme/fields';
import { isObject, isArray } from 'uikit-util';
import ComposedEditor from '../composed/ComposedEditor.vue';

export default {
    name: 'ComposedEditorField',

    components: {
        ComposedEditor
    },

    extends: fields.components.FieldText,

    inject: ['$field'],

    created() {
        const isValid = (obj) => isObject(obj) && !isArray(obj);

        if (!isValid(this.values.sources)) {
            Vue.set(this.values, 'sources', {});
        }

        if (!isValid(this.values.conditions)) {
            Vue.set(this.values, 'conditions', {});
        }
    }
};
</script>
