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
import add from 'date-fns/add';
import isValid from 'date-fns/isValid';
import formatInTimeZone from 'date-fns-tz/formatInTimeZone';
import getTimezoneOffset from 'date-fns-tz/getTimezoneOffset';
import zonedTimeToUtc from 'date-fns-tz/zonedTimeToUtc';
import { DatetimePicker } from '../components';
import DateField from './DatetimeDate.vue';

export default {
    name: 'DatetimeField',

    extends: DateField,

    format: 'yyyy-MM-dd HH:mm:ss',

    computed: {
        val: {
            get() {
                const value = new Date(this.value);

                if (!this.value || !isValid(value)) {
                    return undefined;
                }

                const offset = getTimezoneOffset(this.timezone, value) / 1000;

                return add(value, { seconds: offset });
            },

            set(value) {
                if (!value) {
                    this.value = undefined;
                    return;
                }

                this.value = formatInTimeZone(
                    zonedTimeToUtc(value, this.timezone),
                    '',
                    this.$valueFormat
                );
            }
        }
    },

    methods: {
        async open() {
            const date = await api.uikit.promptDropdown(
                DatetimePicker,
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
