<template>
    <div>
        <div
            class="uk-flex uk-flex-middle uk-flex-right uk-margin-right uk-padding uk-padding-remove-bottom"
        >
            <ul
                :active="active"
                class="uk-subnav uk-margin-remove-bottom"
                uk-switcher="connect: !* +; animation: uk-animation-fade"
            >
                <li v-for="tab in tabs" :key="tab">
                    <a href>{{ $t(tab) }}</a>
                </li>
            </ul>
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
    name: 'ModalFormTabs',

    props: {
        tabs: {
            type: Array,
            required: true
        },

        storage: String,

        active: {
            type: Number,
            default() {
                return (this.storage && parseInt(storage[this.storage], 10)) || 0;
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

            this.$nextTick(() => {
                const el = this.$el.querySelector(
                    `.uk-switcher li:nth-child(${i + 1}) [autofocus]`
                );
                el?.focus();
            });
        }
    }
};
</script>
