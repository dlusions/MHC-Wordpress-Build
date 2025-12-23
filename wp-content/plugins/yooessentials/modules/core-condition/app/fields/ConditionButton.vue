<script>
import essentials from 'yooessentials';
import { FieldYooessentialsButtonPanelFilters } from 'yooessentials-fields';

export default {
    name: 'FieldConditionButton',

    extends: FieldYooessentialsButtonPanelFilters,

    computed: {
        rules() {
            const rules = essentials.customizer?.condition_rules || [];

            return (this.values?.yooessentials_access_conditions || [])
                .map(({ type, ...rest }) => ({ ...rest, type: rules[type] }))
                .filter(({ type }) => type);
        },

        activeRules() {
            return this.rules.filter((rules) => rules?.props?.status !== 'disabled');
        },

        inactiveRules() {
            return this.rules.filter((rules) => rules?.props?.status === 'disabled');
        }
    },

    methods: {
        empty() {
            this.$delete(this.values, 'yooessentials_access_conditions');
            this.$delete(this.values, 'yooessentials_access_mode');
            this.$delete(this.values, 'yooessentials_access_mode_query');
        }
    }
};
</script>
