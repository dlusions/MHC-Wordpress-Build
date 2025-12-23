<script>
import fields from '@yootheme/fields';
import { getClosestFormArea, getFormFields } from '../helper';

export default {
    name: 'FormControlSelect',

    extends: fields.components.FieldYooessentialsSelectDropdown,

    inject: ['$node'],

    computed: {
        status() {
            return !this.value || this.fields.some((f) => f.props?.control_name === this.value);
        },

        fields() {
            const formArea = getClosestFormArea(this.$node);
            const fields = getFormFields(formArea);

            return fields
                .filter((field) => field?.type.match(this.field?.filterType))
                .filter((field) => field?.props.control_name);
        }
    },

    created() {
        this.entries = this.fields.map((field) => ({
            value: field.props.control_name,
            text: field.props.control_label || field.props.control_name
        }));
    }
};
</script>
