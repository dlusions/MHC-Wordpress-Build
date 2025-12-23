<script>
import fields from '@yootheme/fields';

export default {
    name: 'Checkbox',

    extends: fields.components.FieldCheckbox,

    mounted() {
        if (this.field.dependencies) {
            this.field.dependencies.split(',').forEach((dependency) => {
                this.$watch(`values.${dependency}`, (checked) => {
                    this.value = checked ? this.trueValue : this.falseValue;
                });
            });
        }
    }
};
</script>
