<template>
    <Fields
        class="uk-margin-medium-top"
        :config="fields"
        :values="source.values"
        :validation="true"
    />
</template>

<script>
import { deepCopy } from '@yooessentials/util';
import Fields from './FieldFilters/Fields.vue';
import SchemaHelper from '../SchemaHelper';

export default {
    name: 'FieldFilters',

    components: {
        Fields
    },

    props: {
        source: {
            type: Object,
            required: true
        }
    },

    computed: {
        fields() {
            const implodeFields = SchemaHelper.getImplodeFields();
            const argumentsFields = SchemaHelper.getFieldArgumentsFields(this.source.fieldSchema);
            const filtersFields = SchemaHelper.getFieldFiltersFields(this.source.fieldSchema);

            return [
                ...(this.source.isMultipleSource ? prepare(implodeFields, 'implode') : []),
                ...prepare(argumentsFields, 'arguments'),
                ...prepare(filtersFields, 'filters')
            ].filter(Boolean);
        }
    }
};

function prepare(fields, prefix) {
    fields = deepCopy(fields);
    prefixName(fields, prefix);
    return Object.values(fields);
}

function prefixName(fields, prefix) {
    Object.entries(fields).forEach(([name, field]) => {
        field.name = `${prefix}.${name}`;

        if (field.fields) {
            prefixName(field.fields, prefix);
        }
    });
}
</script>
