<script>
import Vue from 'vue';
import api from '@yootheme/api';
import { deepCopy } from '@yooessentials/util';
import { generateId } from '@yooessentials/dynamic/composed/helper';
import { composedField } from '@yooessentials/dynamic/composed/state';
import Widget from '../../widget/Widget.vue';
import { composableCopyEffect } from '../../state';

export default {
    name: 'ConditionWidget',

    extends: Widget,

    inject: ['$field'],

    props: {
        satellite: {
            type: String
        }
    },

    computed: {
        label() {
            return this.satellite || this.value?.label || this.type.title;
        }
    },

    methods: {
        onClick() {
            if (!this.value) {
                return;
            }

            if (this.isActive) {
                this.edit();
                return;
            }

            this.setActive();
        },

        edit() {
            this.$trigger('openDynamicPanel', {
                ...api.customizer.panels['yooessentials-composed-condition'],
                name: `yooessentials-composed-condition-${this.id}`,
                composed: {
                    source: this.id,
                    field: this.$field.name
                },
                props: {
                    node: this.$node,
                    values: this.value
                }
            });
        },

        copy() {
            const { conditions } = this.view.state.field(composedField);

            if (!conditions[this.id]) {
                return;
            }

            const newId = generateId();

            Vue.set(conditions, newId, deepCopy(conditions[this.id]));

            this.view.dispatch({
                effects: composableCopyEffect.of(this),
                changes: {
                    from: this.to,
                    insert: this.view.state.sliceDoc(this.from, this.to).replace(this.id, newId)
                }
            });
        }
    }
};
</script>
