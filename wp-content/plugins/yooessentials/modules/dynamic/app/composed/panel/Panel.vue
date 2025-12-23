<template>
    <div class="uk-flex uk-flex-middle uk-flex-between">
        <span class="uk-flex uk-flex-middle uk-flex-between uk-visible-toggle">
            <span
                v-for="type in types"
                :key="type.name"
                :class="['ye-cm-panel-label uk-margin-xsmall-right', `ye-cm-widget-${type.name}`]"
                :title="`${type.title}s`"
                uk-tooltip
                @click_.prevent.stop="$emit('showSourcesDrop', $event.currentTarget, type)"
                @pointerenter="hoverIn(type)"
                @pointerleave="hoverOut"
            >
                <span :uk-icon="type.icon" ratio="0.9"></span>
                <span style="margin-left: 2px; margin-top: 1px" class="uk-text-meta"
                    >x{{ type.countTotal(view) }}</span
                >
            </span>
        </span>
    </div>
</template>

<script>
import { composedField } from '@yooessentials/dynamic/composed/state';
import { composableTypeHoverEffect } from '../composables/state';

export default {
    name: 'BottomPanel',

    props: {
        view: Object,
        types: Array
    },

    data(vm) {
        const composed = vm.view.state.field(composedField);

        // helps triggering updates on change
        return { composed };
    },

    methods: {
        hoverIn(type) {
            this.view.dispatch({
                effects: composableTypeHoverEffect.of(type)
            });
        },

        hoverOut() {
            this.view.dispatch({
                effects: composableTypeHoverEffect.of(null)
            });
        }
    }
};
</script>
