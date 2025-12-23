<script>
import Vue from 'vue';
import api from '@yootheme/api';
import { deepCopy } from '@yooessentials/util';
import { FieldsList } from '@yooessentials/dynamic/components';
import { generateId } from '@yooessentials/dynamic/composed/helper';
import { composedField } from '@yooessentials/dynamic/composed/state';
import Source from '@yooessentials/dynamic/Source';
import Widget from '../../widget/Widget.vue';
import { composableCopyEffect } from '../../state';

export default {
    name: 'SourceWidget',

    extends: Widget,

    inject: ['$node', '$field'],

    computed: {
        source() {
            return new Source(this.value, this.$node);
        },

        title() {
            return this.source.pathOverview;
        },

        label() {
            if (this.value?.label) {
                return this.value.label;
            }

            if (this.value?.composed) {
                return this.$t('Composed');
            }

            if (!this.source.field) {
                return this.$t('Source');
            }

            return `${this.source.fieldOverview} <span class="uk-text-meta">${this.source.fieldSchemaType.toLowerCase()}</span>`;
        },

        error() {
            return this.source.field && !this.source.isFieldValid;
        }
    },

    methods: {
        edit() {
            if (this.value?.composed) {
                this.openComposedPanel();
            } else {
                this.openSourcePanel();
            }
        },

        async onClick() {
            if (!this.value) {
                return;
            }

            if (this.isActive) {
                this.remap();
                return;
            }

            this.setActive();
        },

        async remap() {
            if (this.remapping) {
                return;
            }

            if (this.value?.composed) {
                this.edit();
                return;
            }

            this.remapping = true;

            const path = await api.uikit.promptDropdown(
                FieldsList,
                {
                    base: this.source.basePath,
                    path: this.source.path.replace(this.source.basePath + '.', ''),
                    maxHeight: 'medium',
                    maxNesting: this.source.queryField ? 0 : 1
                },
                this.$el,
                {
                    flip: false,
                    stretch: false,
                    container: this.$el.closest('.ye-composed-field').parentNode,
                    classes: 'uk-dropdown ye-dropdown ye-cm-dropdown ye-dynamic-dropdown',
                    boundaryX: this.$el.closest('.yo-sidebar-fields > *') || this.$el
                }
            );

            if (path) {
                this.source.queryField = null;
                this.source.field = path;
            }

            this.remapping = false;
        },

        copy() {
            const { sources } = this.view.state.field(composedField);

            if (!sources[this.id]) {
                return;
            }

            const newId = generateId();

            Vue.set(sources, newId, deepCopy(sources[this.id]));

            this.view.dispatch({
                effects: composableCopyEffect.of(this),
                changes: {
                    from: this.to,
                    insert: this.view.state.sliceDoc(this.from, this.to).replace(this.id, newId)
                }
            });
        },

        openComposedPanel() {
            this.$trigger('openDynamicPanel', {
                ...api.customizer.panels['yooessentials-composed'],
                name: `yooessentials-composed-${this.id}`,
                props: {
                    id: this.id,
                    node: this.$node,
                    values: this.value.composed,
                    inject: {
                        $field: this.$field
                    }
                }
            });
        },

        openSourcePanel() {
            this.$trigger('openSourcePanel', {
                ...api.customizer.panels['yooessentials-source'],
                name: `yooessentials-source-composed-${this.id}`,
                props: {
                    node: this.$node,
                    field: this.$field,
                    values: this.source.values
                }
            });
        }
    }
};
</script>
