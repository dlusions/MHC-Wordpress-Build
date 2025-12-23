<template>
    <ul class="uk-list uk-margin-remove">
        <RepeatableItem
            v-for="(v, i) in value"
            :key="i"
            :total="value.length"
            :values="{ [i]: v }"
            @copy="copy(i)"
            @move="(pos) => move(i, pos)"
            @remove="remove(i)"
            @add="add(i + 1)"
        >
            <template #default="{ values }">
                <component
                    :is="repeatableField"
                    :values="values"
                    :field="{ ...field, name: [i] }"
                    @change="(v) => set(i, v)"
                />
            </template>
        </RepeatableItem>
    </ul>
</template>

<script>
import fields from '@yootheme/fields';
import { reindex } from '../util';
import { camelize, isArray, ucfirst } from 'uikit-util';
import RepeatableItem from './RepeatableItem.vue';

export default {
    name: 'RepeatableField',

    components: {
        RepeatableItem
    },

    extends: fields.components.FieldText.extends,

    computed: {
        repeatableField() {
            const fieldName = ucfirst(camelize(`field-${this.field.repeat || 'text'}`));
            return fields.components[fieldName];
        }
    },

    watch: {
        value() {
            this.init();
        }
    },

    created() {
        this.init();
    },

    methods: {
        init() {
            if (!isArray(this.value) || this.value.length === 0) {
                this.value = [null];
            }
        },

        add() {
            this.push(null);
        },

        set(index, value) {
            this.value[index] = value;
            this.value = [...this.value];
        },

        remove(index) {
            this.value.splice(index, 1);
            this.value = [...this.value];
        },

        push(value) {
            this.value = [...this.value, value];
        },

        copy(index) {
            this.push(this.value[index]);
        },

        move(index, position) {
            reindex(this.value, index, index + position);
        }
    }
};
</script>
