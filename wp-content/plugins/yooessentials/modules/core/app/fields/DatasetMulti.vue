<script>
import api from '@yootheme/api';
import { get, keyBy, uuid } from '../util';
import { ResourcePicker } from '../components';
import DatasetField from './Dataset.vue';

export default {
    name: 'DatasetMultiField',

    extends: DatasetField,

    computed: {
        items() {
            return this.value.map((item) => {
                const option = this.options[item.type];

                return {
                    item,
                    id: uuid(),
                    title: this.getTitle(item, option),
                    meta: this.getMeta(item, option),
                    error: !option ? `Unknown Type: ${item.type}` : '',
                    disabled: !option || item.props?.status === 'disabled',
                    icon: option?.icon,
                    description: option?.description,
                    actions: [
                        this.isCopyable && option ? 'copy' : null,
                        this.isDeletale !== false ? 'delete' : null
                    ]
                };
            });
        },

        options() {
            const options = (this.field?.options || []).map((opt) => ({
                ...opt,
                panel: this.getPanel(opt.panel)
            }));

            return keyBy(options, 'name');
        }
    },

    methods: {
        async add() {
            const option = await api.uikit.promptModal(
                ResourcePicker,
                {
                    items: Object.values(this.options)
                },
                { container: true }
            );

            if (!option) {
                return;
            }

            const item = {
                type: option.name,
                ...(option.defaults || {})
            };

            this.push(item);
            this.edit(item);
        },

        edit(item) {
            if (!item.props) {
                this.$set(item, 'props', {});
            }

            const option = this.options[item.type];

            this.openPanel({
                title: this.getTitle(item, option),
                name: `dataset-multi-panel-${item.id}`,
                ...option.panel,
                props: {
                    node: item,
                    values: item.props
                }
            });
        },

        getTitle(item, option) {
            return get(item, option?.titleMap || 'props.name') || option?.titleFallback;
        },

        getMeta(item, option) {
            return get(item, option?.metaMap || 'meta') || option?.metaFallback;
        }
    }
};
</script>
