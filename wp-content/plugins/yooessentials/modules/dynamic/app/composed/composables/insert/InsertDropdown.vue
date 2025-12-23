<template>
    <div tabindex="0" style="outline: none">
        <div class="cm-tooltip-arrow" style="top: 1px; left: -7px"></div>

        <div class="uk-dropdown ye-cm-dropdown" style="margin-left: -14px">
            <!-- select type -->
            <InsertOptions
                v-if="!selectedType"
                key="types"
                :view="view"
                :options="types"
                @resolve="(type) => (selectedType = type)"
                @forward="(type) => (selectedType = type)"
            />

            <!-- single insert -->
            <component
                :is="selectedType.inserts[0].component"
                v-else-if="selectedType.inserts.length === 1 && selectedType.inserts[0].component"
                :node="$node"
                :field="$field"
                max-height="medium"
                @resolve="(data) => resolve(selectedType.inserts[0], data)"
            />

            <!-- multiple inserts -->
            <InsertOptions
                v-else-if="selectedType?.inserts"
                key="inserts"
                :view="view"
                :options="selectedType.inserts"
                @resolve="resolve"
                @backward="selectedType = null"
            />
        </div>
    </div>
</template>

<script>
import { on } from 'uikit-util';
import { composableInsertEffect } from '../state';
import InsertOptions from './InsertOptions.vue';

export default {
    name: 'InsertDropOptions',

    components: {
        InsertOptions
    },

    inject: ['$node', '$field'],

    props: {
        view: Object,
        types: Array
    },

    data: () => ({
        selectedType: null
    }),

    mounted() {
        this.$el.focus();

        this.keyEventsOff = on(window, 'keydown', (e) => {
            if (e.key === 'Escape') {
                this.close();
            }
        });

        this.mouseEventsOff = on(window, 'mousedown', (e) => {
            if (this.$el.contains(e.target) || this.$el === e.target) {
                return;
            }

            this.close();
        });
    },

    destroyed() {
        this.keyEventsOff();
        this.mouseEventsOff();
    },

    methods: {
        resolve(insert, data) {
            this.view.focus();
            this.view.dispatch({
                effects: composableInsertEffect.of({
                    type: this.selectedType,
                    insert,
                    data
                })
            });

            this.close();
        },

        close() {
            this.$emit('close');
        }
    }
};
</script>
