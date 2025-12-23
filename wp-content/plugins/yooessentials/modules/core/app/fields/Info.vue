<template>
    <div :class="field.classes">
        <h2 v-if="field.header" class="yo-sidebar-heading" v-text="$t(field.header)"></h2>
        <div v-if="field.content" v-html="$t(field.content)"></div>
    </div>
</template>

<script>
import fields from '@yootheme/fields';

export default {
    name: 'Info',

    extends: fields.components.FieldText
};
</script>
