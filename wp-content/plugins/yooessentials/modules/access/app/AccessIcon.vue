<template>
    <a
        v-if="linked"
        href="#"
        :class="icon"
        :title="$t(title)"
        :uk-tooltip="`delay: 1000; pos: ${tooltipDirection}`"
        @click.prevent.stop="edit"
    ></a>

    <span
        v-else
        :class="icon"
        :title="$t(title)"
        :uk-tooltip="`delay: 1000; pos: ${tooltipDirection}`"
    ></span>
</template>

<script>
import api from '@yootheme/api';

export default {
    name: 'AccessIcon',

    inject: ['Sidebar', 'Builder'],

    props: {
        node: {
            type: Object,
            required: true
        },
        error: {
            type: [String, Boolean],
            default: ''
        },
        tooltipDirection: {
            type: String,
            default: 'bottom'
        }
    },

    data: () => ({
        status: null
    }),

    computed: {
        linked() {
            const { node, type } = this.node;

            if (node.type === 'row' || (type.element && type.container)) {
                return false;
            }

            return true;
        },
        icon() {
            let icon = 'ye-builder-icon-access';

            if (this.status === false || this.error) {
                icon += '-denied';
            }

            return icon;
        },
        title() {
            if (this.error) {
                return this.error;
            }

            if (this.linked) {
                return 'Restricted Access';
            }

            return '';
        }
    },

    mounted() {
        this.setStatus(window.$yooesslogs || {});
    },

    methods: {
        edit() {
            const panel = api.customizer.panels['yooessentials-access-condition'];

            if (!panel) {
                throw new Error('Condition Panel Not Found');
            }

            this.$trigger('openDynamicPanel', {
                ...panel,
                width: 450,
                props: {
                    node: this.node.node,
                    builder: this.Builder,
                    values: this.node.node.props
                }
            });
        },

        setStatus({ access }) {
            const nodeId = this.Builder.id(this.node.node);
            this.status = access?.[nodeId]?.result;
        }
    },

    events: {
        readyPreview: {
            handler(e, { window: { $yooesslogs = {} } }) {
                this.setStatus($yooesslogs);
            },

            priority: 50
        }
    }
};
</script>
