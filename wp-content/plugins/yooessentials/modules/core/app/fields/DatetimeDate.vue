<template>
    <input v-if="attributes.disabled" disabled class="uk-input" />

    <div v-else>
        <div class="uk-inline uk-width-1-1">
            <div class="uk-position-center-right uk-position-small">
                <ul class="uk-iconnav uk-flex-nowrap">
                    <li>
                        <a
                            tabindex="-1"
                            :title="$t('Pick')"
                            class="uk-icon-link uk-preserve-width"
                            uk-icon="calendar"
                            uk-tooltip="delay: 500"
                            @click.prevent="open"
                        ></a>
                    </li>
                </ul>
            </div>

            <input
                v-model.lazy="inputValue"
                type="text"
                class="uk-input yo-input-iconnav-right"
                v-bind="attributes"
            />
        </div>
    </div>
</template>

<script>
import api from '@yootheme/api';
import { DatePicker } from '../components';
import format from 'date-fns/format';
import isValid from 'date-fns/isValid';
import TimeField from './DatetimeTime.vue';

export default {
    name: 'DateField',

    extends: TimeField,

    format: 'yyyy-MM-dd',

    computed: {
        inputValue: {
            get() {
                if (!this.val) {
                    return '';
                }

                return format(this.val, this.$displayFormat);
            },

            set(value) {
                if (!value) {
                    this.val = null;
                    return;
                }

                const date = new Date(value);

                if (value && !isValid(date)) {
                    this.$forceUpdate();
                    return;
                }

                this.val = date;
                this.$forceUpdate();
            }
        },

        val: {
            get() {
                const value = new Date(this.value);

                if (!this.value || !isValid(value)) {
                    return undefined;
                }

                return value;
            },

            set(value) {
                if (!value) {
                    this.value = undefined;
                    return;
                }

                this.value = format(value, this.$valueFormat);
            }
        }
    },

    methods: {
        async open() {
            const date = await api.uikit.promptDropdown(
                DatePicker,
                {
                    value: this.val,
                    min: this.field?.min ? new Date(this.field.min) : null,
                    max: this.field?.max ? new Date(this.field.max) : null,
                    small: Boolean(this.field.small)
                },
                this.$el,
                { classes: 'yo-dropdown', flip: false }
            );

            if (date) {
                this.val = date;
            }
        }
    }
};
</script>
