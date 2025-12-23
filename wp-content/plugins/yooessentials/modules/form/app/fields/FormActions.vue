<script>
import fields from '@yootheme/fields';
import essentials from 'yooessentials';

export default {
    name: 'FormActionsFields',

    extends: fields.components.FieldYooessentialsDatasetMulti,

    computed: {
        options() {
            const actions = essentials.customizer?.form_actions || {};
            const options = Object.entries(actions).reduce((acc, [name, action]) => {
                acc[name] = {
                    ...action,
                    panel: action,
                    titleFallback: action.title
                };
                return acc;
            }, {});

            return options;
        }
    }
};
</script>
