<template>
    <div class="uk-padding">
        <TimeInput v-model="selection" class="uk-margin-top" />

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

export default {
    name: 'TimePicker',

    components: {
        TimeInput
    },

    props: {
        value: {
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
