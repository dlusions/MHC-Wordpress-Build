<template>
    <div v-if="isEmpty(value)">
        <button
            class="uk-button uk-button-default uk-margin-small-top uk-width-1-1"
            type="button"
            @click.prevent="add"
        >
            {{ $t(field.txtAdd || 'Add') }}
        </button>
    </div>

    <ul v-else class="uk-list">
        <RepeatableItem
            v-for="(v, i) in value"
            :key="i"
            :values="v"
            :total="value.length"
            :orderable="field.orderable"
            :separator="true"
            :txt-add="field.txtAdd"
            @copy="copy(i)"
            @move="(pos) => move(i, pos)"
            @remove="remove(i)"
            @add="add(i + 1)"
        >
            <template #default="{ values }">
                <fields-panel :panel="{ fields: field.fields }" :values="values" />
            </template>
        </RepeatableItem>
    </ul>
</template>

<script>
import { isArray, isEmpty } from 'uikit-util';
import { deepCopy, get, vueClosestParent } from '../util';
import Repeatable from './Repeatable.vue';

export default {
    name: 'RepeatableMulti',

    extends: Repeatable,

    events: {
        'yooessentials-resolve-field-argument'(e, params) {
            const repeatableItem = vueClosestParent(
                e.origin,
                (instance) => get(instance, '$options._componentTag') === 'RepeatableItem'
            );

            if (!repeatableItem) {
                return;
            }

            for (const key in params) {
                params[key] = params[key].replace(
                    /#__{repeatable_index}/,
                    this.$children.indexOf(repeatableItem)
                );
            }
        }
    },

    methods: {
        isEmpty,

        init() {
            if (!isArray(this.value)) {
                this.value = [];
            }
        },

        add(index) {
            this.value.splice(index, 0, {});
        },

        set(index, name, v) {
            if (!this.value[index]) {
                this.$set(this.value, index, {});
            }

            this.$set(this.value[index], name, v);
        },

        copy(index) {
            this.value.splice(index, 0, deepCopy(this.value[index]));
        }
    }
};
</script>
