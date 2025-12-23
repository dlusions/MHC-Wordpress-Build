<script>
import { BuilderPanel } from '@yooessentials/components';
import Source from '../Source';
import SourceHelper from '../SourceHelper';

export default {
    name: 'SourcePanel',

    extends: BuilderPanel,

    provide() {
        return {
            $source: this.values
        };
    },

    props: {
        field: Object
    },

    created() {
        const field = this.field?.name;
        const isNodeInheriting = this.node?.source?.props?.[field];

        // move to source_extended in case an essentials
        // feature is used, as we cannot change it on the fly
        if (isNodeInheriting) {
            const source = new Source(this.values, this.node);

            source.query = SourceHelper.nodeKey;
            SourceHelper.setExtendedProp(this.node, this.field, source.values);
        }
    }
};
</script>
