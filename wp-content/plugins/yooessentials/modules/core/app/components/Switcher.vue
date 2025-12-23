<template>
    <div>
        <div class="uk-modal-header uk-flex uk-flex-middle uk-flex-between">
            <ul
                :active="active"
                class="uk-margin-remove-bottom"
                uk-tab="connect: !* +; animation: uk-animation-fade"
            >
                <li v-for="tab in tabs" :key="tab">
                    <a href>{{ $t(tab) }}</a>
                </li>
            </ul>

            <slot name="header-right"></slot>
        </div>

        <ul class="uk-switcher">
            <li v-for="(tab, i) in tabs" :key="tab" @beforeshow.self="selectTab(tab, i)">
                <slot v-if="~shown.indexOf(i)" :name="tab"></slot>
            </li>
        </ul>
    </div>
</template>

<script>
import { storage } from '../util';

export default {
    name: 'Switcher',

    props: {
        tabs: {
            type: Array,
            required: true
        },

        storage: String,

        active: {
            type: Number,
            default() {
                return (this.storage && toInteger(storage[this.storage])) || 0;
            }
        }
    },

    data: () => ({
        shown: []
    }),

    methods: {
        selectTab(tab, i) {
            if (this.storage) {
                storage[this.storage] = i;
            }
            this.shown.splice(this.shown.length, 0, i);
            this.$emit('show', tab, i);

            this.$nextTick(() =>
                this.$el.querySelector(`.uk-switcher li:nth-child(${i + 1}) [autofocus]`)?.focus()
            );
        }
    }
};

function toInteger(val) {
    parseInt(val, 10);
}
</script>
