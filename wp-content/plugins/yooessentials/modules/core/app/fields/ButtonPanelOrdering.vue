<script>
import ButtonPanelFilters from './ButtonPanelFilters.vue';

export default {
    name: 'ButtonPanelOrdering',

    extends: ButtonPanelFilters,

    computed: {
        rules() {
            return (this.$node?.source?.query?.arguments?.ordering || []).filter(
                (rule) =>
                    (rule?.props?.direction ||
                        rule?.source?.props?.direction ||
                        rule?.source_extended?.props?.direction) &&
                    rule?.props?.field
            );
        }
    },

    methods: {
        empty() {
            this.$node.source.query.arguments.ordering = [];
        }
    }
};
</script>
