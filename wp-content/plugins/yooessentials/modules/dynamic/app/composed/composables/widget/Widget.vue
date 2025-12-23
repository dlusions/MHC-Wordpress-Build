<template>
    <button
        type="button"
        :title="title"
        uk-tooltip="delay: 500"
        :draggable="isDraggable && !satellite"
        :class="[
            'ye-cm-widget',
            `ye-cm-widget-${type.name}`,
            {
                'ye-cm-widget-error': error,
                'ye-cm-widget-active': isActive || isHighlighted,
                'ye-cm-widget-dragged': dragged,
                'ye-cm-widget-selected': isSelected,
                'ye-cm-widget-satellite': satellite
            }
        ]"
        @dragstart="dragged = true"
        @dragend="dragged = false"
        @click.stop="!satellite && onClick()"
        @pointerenter="hoverIn"
        @pointerleave="hoverOut"
    >
        <span class="ye-cm-widget-label" v-html="label"></span>
    </button>
</template>

<script>
import UIkit from 'uikit';
import { on } from 'uikit-util';
import { composableDraggingEffect } from '../dragdrop';
import {
    composableHoverEffect,
    composableActiveEffect,
    composableCopyEffect,
    composableDeleteEffect
} from '../state';

export default {
    name: 'Widget',

    inject: { Editor: {}, $node: {}, $field: {} },

    props: {
        id: {
            type: String,
            required: true
        },
        type: {
            type: [Object, Function],
            required: true
        },
        view: {
            type: Object,
            required: true
        },
        satellite: {
            type: Boolean,
            default: false
        },
        value: Object
    },

    data: () => ({
        active: false,
        dragged: false,
        highlighted: false
    }),

    computed: {
        title() {
            return this.type.title;
        },

        label() {
            return this.value?.label || this.title;
        },

        error() {
            return false;
        },

        isActive() {
            return this.widgets.some((w) => w.active);
        },

        isHighlighted() {
            return this.widgets.some((w) => w.highlighted);
        },

        // only draggable if no selection
        isDraggable() {
            return !this.Editor.selection?.ranges?.some((r) => !r.empty);
        },

        isSelected() {
            return this.Editor.selection?.ranges?.some(
                (r) => r.from <= this.from && r.to >= this.to
            );
        },

        widgets: {
            get() {
                return this.Editor.$children.filter((w) => w.id === this.id);
            },
            cache: false
        },

        innerWidgets: {
            get() {
                return this.Editor.$children.filter(
                    (w) => w.id !== this.id && w.from > this.from && w.to < this.to
                );
            },
            cache: false
        },

        from: {
            get() {
                return this.widgets.at(0).$el?.cmView?.posAtStart;
            },
            cache: false
        },

        to: {
            get() {
                return this.widgets.at(-1).$el?.cmView?.posAtEnd;
            },
            cache: false
        }
    },

    watch: {
        dragged(v) {
            UIkit.tooltip(this.$el).hide();

            if (v) {
                [...this.widgets, ...this.innerWidgets].forEach((w) => (w.dragged = true));

                this.view.dispatch({
                    effects: composableDraggingEffect.of({ from: this.from, to: this.to })
                });
            } else {
                [...this.widgets, ...this.innerWidgets].forEach((w) => (w.dragged = false));

                this.view.dispatch({
                    effects: composableDraggingEffect.of(null)
                });
            }
        }
    },

    created() {
        this.keyEventsOff = on(window, 'keydown', (e) => {
            if (e.key === 'Escape') {
                this.setInactive();
            }

            return false;
        });

        this.onWindowClickOff = on(window, 'click', (e) => {
            if (this.active && !e.target.closest('.cm-tooltip-widget')) {
                this.setInactive();
            }
        });
    },

    beforeDestroy() {
        if (this.active) {
            this.$nextTick(() => {
                this.setInactive();
            });
        }

        this.keyEventsOff?.();
        this.onWindowClickOff?.();
    },

    methods: {
        edit() {},

        onClick() {},

        setActive() {
            UIkit.tooltip(this.$el).hide();

            if (this.active && this.value) {
                this.edit();
                this.setInactive();
                return;
            }

            this.view.dispatch({
                effects: composableActiveEffect.of(this)
            });
        },

        setInactive() {
            UIkit.tooltip(this.$el).hide();

            this.view.dispatch({
                effects: composableActiveEffect.of(null)
            });
        },

        hoverIn() {
            this.view.dispatch({
                effects: composableHoverEffect.of(this)
            });
        },

        hoverOut() {
            this.view.dispatch({
                effects: composableHoverEffect.of(null)
            });
        },

        copy() {
            this.view.dispatch({
                effects: composableCopyEffect.of(this)
            });
        },

        delete() {
            this.view.dispatch({
                effects: composableDeleteEffect.of(this),
                changes: {
                    from: this.from,
                    to: this.to,
                    insert: ''
                }
            });
        }
    }
};
</script>
