<script>
import BaseFields from '@yootheme/fields';
import essentials from 'yooessentials';

export default {
    name: 'ComposedCondition',

    extends: BaseFields.components.FieldYooessentialsConditionRules,

    computed: {
        logs: {
            get() {
                const logs = essentials?.logs || {};

                const nodeId = essentials.yoo.Builder.id(this.$node);
                const { field, source } = essentials.yoo?.Sidebar?.panel?.composed || {};

                const id = `${nodeId}#${field}#${source}`;

                return logs.composed?.[id];
            },
            cache: false
        }
    }
};
</script>
