<template>
    <div :class="['uk-padding', { 'uk-padding-small': small }]">
        <Calendar v-model="selection" :min="min" :max="max" :small="small" />

        <TimeInput v-model="selection" class="uk-margin-top" :small="small" />

        <div class="uk-margin-large-top uk-flex uk-flex-around">
            <button
                class="uk-button"
                :class="{ 'uk-button-small': small }"
                @click="$emit('resolve')"
            >
                {{ $t('Cancel') }}
            </button>
            <button
                class="uk-button"
                :class="{ 'uk-button-small': small }"
                @click="$emit('resolve', selection)"
            >
                {{ $t('Confirm') }}
            </button>
        </div>
    </div>
</template>

<script>
import TimeInput from './TimeInput.vue';
import Calendar from './Calendar.vue';

export default {
    name: 'DatetimePicker',

    components: {
        TimeInput,
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
    },

    data: () => ({
        selection: null
    }),

    created() {
        this.selection = this.value ? this.value : new Date();
    }
};
</script>
