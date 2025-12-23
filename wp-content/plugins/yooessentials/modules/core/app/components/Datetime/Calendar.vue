<template>
    <div>
        <div class="uk-flex uk-flex-between uk-margin">
            <div>
                <a
                    href
                    class="uk-icon-link uk-margin-right"
                    uk-icon="chevron-left"
                    @click.prevent="date = sub(date, { months: 1 })"
                ></a>
            </div>

            <div class="uk-flex">
                <div uk-form-custom class="uk-margin-small-right">
                    <select
                        :value="month"
                        @change="date = set(date, { month: $event.target.value })"
                    >
                        <option
                            v-for="m in monthsInterval"
                            :key="m.getMonth()"
                            :value="m.getMonth()"
                            v-text="format(m, 'MMMM')"
                        ></option>
                    </select>
                    <span class="uk-text-lead">{{ format(date, small ? 'MMM' : 'MMMM') }}</span>
                </div>

                <div uk-form-custom>
                    <select :value="year" @change="date = set(date, { year: $event.target.value })">
                        <option v-for="y in yearsInterval" :key="y" :value="y" v-text="y"></option>
                    </select>
                    <span class="uk-text-lead">{{ year }}</span>
                </div>
            </div>

            <div>
                <a
                    href
                    class="uk-icon-link uk-margin-left"
                    uk-icon="chevron-right"
                    @click.prevent="date = add(date, { months: 1 })"
                ></a>
            </div>
        </div>

        <div class="uk-grid-collapse uk-child-width-1-7" uk-grid>
            <div
                v-for="day in weekDays"
                :key="day"
                class="uk-text-lead uk-text-center"
                :class="{
                    'uk-margin-bottom': !small,
                    'uk-margin-small-bottom': small
                }"
                :style="dateStyle"
                v-text="day"
            ></div>
            <div
                v-for="day in calendar"
                :key="day.toString()"
                :class="[
                    'uk-card uk-card-body uk-card-hover uk-flex uk-flex-center uk-flex-middle uk-link',
                    {
                        'uk-padding-small': !small,
                        'uk-padding-remove': small,
                        'uk-card-primary': isSameDay(day, value),
                        'uk-card-default': isToday(day),
                        'uk-disabled uk-text-meta': !isWithinInterval(day),
                        'uk-text-meta': !isWithinCalendarMonth(day)
                    }
                ]"
                :style="dateStyle"
                @click.prevent="select(day)"
            >
                <div :class="['uk-align-vertical', {}]" v-text="day.getDate()"></div>
            </div>
        </div>
    </div>
</template>

<script>
import add from 'date-fns/add';
import eachDayOfInterval from 'date-fns/eachDayOfInterval';
import endOfWeek from 'date-fns/endOfWeek';
import format from 'date-fns/format';
import isAfter from 'date-fns/isAfter';
import isBefore from 'date-fns/isBefore';
import isSameDay from 'date-fns/isSameDay';
import isSameMonth from 'date-fns/isSameMonth';
import isToday from 'date-fns/isToday';
import set from 'date-fns/set';
import setDay from 'date-fns/setDay';
import startOfWeek from 'date-fns/startOfWeek';
import sub from 'date-fns/sub';
import range from 'lodash-es/range';

export default {
    name: 'Calendar',

    props: {
        value: {
            type: Date
        },

        min: {
            type: Date
        },

        max: {
            type: Date
        },

        small: {
            type: Boolean
        }
    },

    data: () => ({
        date: null
    }),

    computed: {
        year() {
            return this.date?.getFullYear();
        },

        month() {
            return this.date?.getMonth();
        },

        dateStyle() {
            return {
                width: 'calc(100% * 1 / 7.001)',
                margin: this.small ? '2px 0' : 'inherit'
            };
        },

        calendar() {
            const start = startOfWeek(new Date(this.year, this.month, 1), {
                weekStartsOn: 1
            });

            const end = endOfWeek(new Date(this.year, this.month + 1, 1), {
                weekStartsOn: 1
            });

            return eachDayOfInterval({ start, end });
        },

        weekDays() {
            return range(1, 8).map((day) =>
                format(setDay(new Date(), day), this.small ? 'EEEEEE' : 'E')
            );
        },

        monthsInterval() {
            return range(12).map((month) => new Date(this.year, month));
        },

        yearsInterval() {
            return [
                ...range(60).reduce((carry) => [carry[0] - 1, ...carry], [this.year - 1]),
                this.year,
                ...range(20).reduce(
                    (carry) => [...carry, carry[carry.length - 1] + 1],
                    [this.year + 1]
                )
            ];
        }
    },

    created() {
        this.date = this.value ? this.value : new Date();
    },

    methods: {
        isToday,
        format,
        isSameDay,
        add,
        sub,
        set,

        isWithinCalendarMonth(date) {
            return isSameMonth(date, this.date);
        },

        isWithinInterval(date) {
            if (this.min && isBefore(date, this.min)) {
                return false;
            }

            if (this.max && isAfter(date, this.max)) {
                return false;
            }

            return true;
        },

        select(date) {
            this.$emit(
                'input',
                set(date, { hours: this.value?.getHours(), minutes: this.value?.getMinutes() })
            );
        }
    }
};
</script>
