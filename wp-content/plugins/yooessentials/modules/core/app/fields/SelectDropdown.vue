<template>
    <input v-if="attributes.disabled" disabled class="uk-select" :value="selectedText" />

    <div v-else>
        <div
            :class="['uk-select uk-cursor-default', attributes.class, { 'uk-form-danger': error }]"
            @click.prevent="open"
            @keydown.down="open"
        >
            <div v-if="selectedText" class="uk-disabled uk-text-nowrap uk-overflow-hidden">
                {{ selectedText }}
            </div>
            <div v-else class="uk-disabled uk-text-muted">{{ field.placeholder }}</div>
        </div>
        <span v-if="error" class="uk-text-danger uk-text-small">{{ error }}</span>
    </div>
</template>

<script>
import api from '@yootheme/api';
import fields from '@yootheme/fields';
import { keyBy } from '../util';
import { isObject, isUndefined } from 'uikit-util';
import { DropdownList } from '../components';
import { stringIncludes } from '../util';

export default {
    name: 'SelectDropdown',

    extends: fields.components.FieldText,

    data: () => ({
        error: '',
        search: '',
        entries: []
    }),

    computed: {
        selected: {
            get() {
                if (!isUndefined(this.value)) {
                    return this.value;
                }

                return this.attributes.multiple ? [] : '';
            },

            set(value) {
                this.value = value;
            }
        },

        selectedText() {
            const entries = keyBy(this.entries, 'value');
            return entries?.[this.selected]?.text;
        },

        optionValues() {
            return this.entries.map(({ value }) => value);
        }

        // invalid() {
        //     return (
        //         !isUndefined(this.value) &&
        //         [].concat(this.selected).some(value => !this.optionValues.includes(value))
        //     );
        // }
    },

    created() {
        if (this.field.options) {
            this.entries = this.flattenOptions(this.field.options);
        }

        if (isUndefined(this.value) && !isUndefined(this.defaultIndex)) {
            this.value = this.optionValues[this.defaultIndex];
        }
    },

    methods: {
        async open() {
            const value = await api.uikit.promptDropdown(
                {
                    functional: true,
                    render: (h, { listeners }) =>
                        h(DropdownList, {
                            props: {
                                search: this.search,
                                selected: this.selected,
                                maxHeight: this.field.dropsize,
                                entries: this.entries.filter((item) =>
                                    stringIncludes(item.text, this.search)
                                )
                            },
                            on: {
                                search: (val) => {
                                    this.search = val;
                                },
                                resolve: (item) => {
                                    listeners.resolve(item);
                                }
                            }
                        })
                },
                {},
                this.$el,
                { classes: 'yo-dropdown', flip: false }
            );

            if (value) {
                this.selected = value;
            }
        },

        flattenOptions(options = {}, group = '') {
            return Object.keys(options).reduce((carry, key) => {
                const option = options[key];
                return [
                    ...carry,
                    ...(isObject(option)
                        ? this.flattenOptions(option, key)
                        : [{ text: key, value: option, group }])
                ];
            }, []);
        }
    }
};
</script>
