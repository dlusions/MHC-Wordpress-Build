<script>
import BaseFields from '@yootheme/fields';
import essentials from 'yooessentials';

export default {
    name: 'AccessCondition',

    extends: BaseFields.components.FieldYooessentialsConditionRules,

    computed: {
        logs: {
            get() {
                const { access } = essentials?.logs || {};
                const nodeId = essentials.yoo.Builder.id(this.$node);

                return access?.[nodeId];
            },
            cache: false
        }
    }
};
</script>
