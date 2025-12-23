<script>
import essentials from 'yooessentials';
import { FieldsPanel } from '@yootheme/sidebar';

export default {
    name: 'YooessentialsBuilderPanel',

    extends: FieldsPanel,

    inject: ['Sidebar'],

    provide() {
        return {
            $node: this.node,
            Builder: this.builder,
            ...this.inject
        };
    },

    props: {
        node: Object,
        builder: {
            type: Object,
            default: () => essentials.yoo.Builder
        },
        inject: {
            type: Object,
            default: () => ({})
        }
    },

    events: {
        'yooessentials-prerequest'(e) {
            const prevPanel = this.Sidebar.stack.at(-2);

            e.params[0]['node'] = this.node;
            e.params[0]['form'] = this.values;
            e.params[0]['parentForm'] = prevPanel.props?.values;
        },

        resetNode() {
            this.$trigger('closeSidebarPanel', this.panel);
        }
    }
};
</script>
