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
                            uk-icon="clock"
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
import fields from '@yootheme/fields';
import api from '@yootheme/api';
import { TimePicker } from '../components';
import set from 'date-fns/set';
import add from 'date-fns/add';
import isValid from 'date-fns/isValid';
import format from 'date-fns/format';
import formatInTimeZone from 'date-fns-tz/formatInTimeZone';
import getTimezoneOffset from 'date-fns-tz/getTimezoneOffset';
import zonedTimeToUtc from 'date-fns-tz/zonedTimeToUtc';

export default {
    name: 'TimeField',

    extends: fields.components.FieldText,

    format: 'HH:mm',

    computed: {
        timezone() {
            return Intl.DateTimeFormat().resolvedOptions().timeZone;
        },

        $displayFormat() {
            return this.field.displayFormat || this.$options.format;
        },

        $valueFormat() {
            return this.field.valueFormat || this.$options.format;
        },

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

                const [hours, minutes = 0] = value.split(':');
                const date = set(new Date(), { hours, minutes });

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
                const [hours, minutes] = this.value?.split(':') || [];

                if (!hours || !minutes) {
                    return undefined;
                }

                const value = set(new Date(), { hours, minutes });

                if (!isValid(value)) {
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
                TimePicker,
                {
                    value: this.val,
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
