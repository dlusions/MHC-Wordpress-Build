<template>
    <FieldFilters :node="$node" :source="source" />
</template>

<script>
import fields from '@yootheme/fields';
import Source from '../Source';
import { FieldFilters } from '../components';

export default {
    name: 'SourceFieldFilters',

    components: {
        FieldFilters
    },

    extends: fields.components.FieldText,

    inject: ['$node'],

    computed: {
        source() {
            return new Source(this.values, this.$node);
        }
    }
};
</script>
