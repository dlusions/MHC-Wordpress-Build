<template>
    <div class="cm-tooltip-insert-drop">
        <a
            v-show="!showOptions"
            ref="drop"
            :title="$t('Insert Source') + ` (cmd + /)`"
            uk-tooltip="delay: 500"
            class="yo-builder-icon-add-right"
            @click.stop.prevent="showOptions = true"
        ></a>

        <InsertDropdown
            v-if="showOptions"
            class="uk-margin-small-top"
            :types="types"
            :view="view"
            @close="showOptions = false"
        />
    </div>
</template>

<script>
import { on } from 'uikit-util';
import InsertDropdown from './InsertDropdown.vue';

export default {
    name: 'InsertDrop',

    components: {
        InsertDropdown
    },

    props: {
        view: Object,
        types: Array
    },

    data: () => ({
        showOptions: false
    }),

    created() {
        this.keyEventsOff = on(window, 'keydown', (e) => {
            if (e.metaKey && e.key === '/') {
                e.preventDefault();
                e.stopPropagation();

                this.showOptions = true;
                return;
            }
        });

        // Prevent bluring editor when clicking on drop
        this.mouseEventsOff = on(window, 'mousedown', (e) => {
            if (this.$refs.drop.contains(e.target) || this.$refs.drop === e.target) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    },

    destroyed() {
        this.keyEventsOff();
        this.mouseEventsOff();
    }
};
</script>
