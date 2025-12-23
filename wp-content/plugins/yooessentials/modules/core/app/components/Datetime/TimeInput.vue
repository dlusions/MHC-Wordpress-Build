<template>
    <div class="uk-flex uk-flex-between">
        <div>
            <a
                href
                class="uk-icon-link"
                :class="small ? 'uk-margin-small-right' : 'uk-margin-right'"
                uk-icon="chevron-left"
                @click.prevent="$emit('input', sub(value, { hours: 1 }))"
            ></a>
        </div>

        <div uk-form-custom>
            <select
                :value="format(value, 'H')"
                @change="$emit('input', set(value, { hours: $event.target.value }))"
            >
                <option v-for="h in range(24)" :key="h" :value="h" v-text="h"></option>
            </select>
            <span class="uk-text-lead uk-text-background uk-text-primary"
                >{{ format(value, 'HH') }}h</span
            >
        </div>

        <div>
            <a
                href
                class="uk-icon-link"
                :class="small ? 'uk-margin-small-left' : 'uk-margin-left'"
                uk-icon="chevron-right"
                @click.prevent="$emit('input', add(value, { hours: 1 }))"
            ></a>
        </div>

        :

        <div>
            <a
                href
                class="uk-icon-link"
                :class="small ? 'uk-margin-small-right' : 'uk-margin-right'"
                uk-icon="chevron-left"
                @click.prevent="$emit('input', sub(value, { minutes: 1 }))"
            ></a>
        </div>

        <div uk-form-custom>
            <select
                :value="format(value, 'm')"
                @change="$emit('input', set(value, { minutes: $event.target.value }))"
            >
                <option v-for="h in range(0, 60, 5)" :key="h" :value="h" v-text="h"></option>
            </select>
            <span class="uk-text-lead uk-text-background uk-text-primary"
                >{{ format(value, 'mm') }}m</span
            >
        </div>

        <div>
            <a
                href
                class="uk-icon-link"
                :class="small ? 'uk-margin-small-left' : 'uk-margin-left'"
                uk-icon="chevron-right"
                @click.prevent="$emit('input', add(value, { minutes: 1 }))"
            ></a>
        </div>
    </div>
</template>

<script>
import range from 'lodash-es/range';
import add from 'date-fns/add';
import format from 'date-fns/format';
import set from 'date-fns/set';
import sub from 'date-fns/sub';

export default {
    name: 'TimeInput',

    props: {
        value: {
            type: Date,
            required: true
        },

        small: {
            type: Boolean
        }
    },

    methods: {
        sub,
        add,
        set,
        format,
        range
    }
};
</script>
