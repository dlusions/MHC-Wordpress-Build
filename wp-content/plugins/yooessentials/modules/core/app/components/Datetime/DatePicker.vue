<template>
    <div :class="['uk-padding', { 'uk-padding-small': small }]">
        <Calendar
            :value="value"
            :min="min"
            :max="max"
            :small="small"
            @input="(v) => $emit('resolve', v)"
        />
    </div>
</template>

<script>
import Calendar from './Calendar.vue';

export default {
    name: 'DatePicker',

    components: {
        Calendar
    },

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
    }
};
</script>
