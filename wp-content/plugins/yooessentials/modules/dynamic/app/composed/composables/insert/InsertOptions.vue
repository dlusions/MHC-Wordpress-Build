<template>
    <div class="yo-dropdown-body">
        <ul class="uk-nav uk-dropdown-nav ye-cm-dropdown-nav-hoverless">
            <li
                v-for="(opt, i) in options"
                :key="i"
                class="uk-visible-toggle uk-position-relative"
                :class="{
                    'uk-active': active === opt,
                    'uk-disabled': !validate(opt)
                }"
            >
                <a href class="uk-flex" @pointerenter="active = opt" @click.prevent.stop="resolve">
                    <span
                        v-if="opt.icon"
                        width="16"
                        style="margin: 0 10px 0 5px"
                        :uk-icon="opt.icon"
                        class="uk-flex-none"
                    ></span>
                    <span>
                        <span class="uk-text-nowrap uk-text-truncate">
                            {{ opt.title }}
                        </span>
                        <span class="uk-nav-subtitle uk-text-muted uk-display-block">
                            {{ opt.description }}
                        </span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import { on, isFunction } from 'uikit-util';

export default {
    name: 'InsertOptions',

    props: {
        view: {
            type: Object,
            required: true
        },
        options: {
            type: Array,
            required: true
        }
    },

    data: () => ({
        active: null
    }),

    computed: {
        validOptions() {
            return this.options.filter(this.validate);
        }
    },

    created() {
        this.active = this.findNextOption();

        this.keyEventsOff = on(window, 'keydown', (e) => {
            if (e.key === 'ArrowUp') {
                this.move('up');
            }

            if (e.key === 'ArrowDown') {
                this.move('down');
            }

            if (e.key === 'ArrowLeft') {
                this.$emit('backward');
            }

            if (e.key === 'ArrowRight') {
                this.$emit('forward', this.active);
            }

            if (e.key === 'Enter') {
                this.resolve();
            }

            if (e.key.match(/Arrow|Enter/)) {
                e.preventDefault();
            }
        });
    },

    destroyed() {
        this.keyEventsOff();
    },

    methods: {
        move(dir) {
            this.active = this.findNextOption(this.options.indexOf(this.active), dir);
        },

        findNextOption(currentIndex = -1, dir = 'down') {
            const total = this.options.length;
            const direction = dir === 'up' ? -1 : 1;

            let nextIndex = (currentIndex + direction + total) % total;

            while (!this.validate(this.options[nextIndex])) {
                nextIndex = (nextIndex + direction + total) % total;
                if (nextIndex === currentIndex) break; // Prevent infinite loop if no valid options
            }

            return this.options[nextIndex];
        },

        validate(opt) {
            if (!isFunction(opt.validate)) {
                return true;
            }

            return opt.validate(this.view);
        },

        resolve() {
            this.$emit('resolve', this.active);
        }
    }
};
</script>
