<template>
    <div class="cm-tooltip-widget">
        <div v-if="value" v-show="layout === 'label'" class="uk-inline uk-flex">
            <span class="ye-builder-icon-label uk-disabled" style="width: 24px"></span>
            <input
                id="widget-label"
                ref="label"
                v-model="value.label"
                type="text"
                class="uk-input uk-form-small"
                :placeholder="$t('Enter label')"
                style="padding-left: 2px !important; border: none; height: 22px"
                @keydown.enter="layout = ''"
            />
        </div>

        <ul v-show="layout === ''" class="uk-iconnav">
            <li v-if="widget.label">
                <a
                    href=""
                    uk-tooltip="delay: 500"
                    class="ye-builder-icon-label"
                    :title="$t('Label')"
                    @click.prevent="layout = 'label'"
                ></a>
            </li>
            <li v-if="widget.edit">
                <a
                    href=""
                    uk-tooltip="delay: 500"
                    class="yo-builder-icon-edit"
                    :title="$t('Edit')"
                    @click.prevent="widget.edit"
                ></a>
            </li>
            <li>
                <a
                    href=""
                    uk-tooltip="delay: 500"
                    class="yo-builder-icon-copy"
                    :title="$t('Copy')"
                    @click.prevent="widget.copy"
                ></a>
            </li>
            <li>
                <a
                    href=""
                    uk-tooltip="delay: 500"
                    class="yo-builder-icon-delete"
                    :title="$t('Delete')"
                    @click.prevent="widget.delete"
                ></a>
            </li>
        </ul>
    </div>
</template>

<script>
import { on } from 'uikit-util';
import { composableActiveEffect } from '../state';

export default {
    name: 'WidgetTooltip',

    props: {
        view: {
            type: Object,
            required: true
        },
        widget: {
            type: Object,
            required: true
        },
        value: Object
    },

    data: () => ({
        layout: ''
    }),

    watch: {
        layout() {
            if (this.value && this.layout === 'label') {
                this.$nextTick(() => {
                    this.$refs.label.focus();
                });
            }
        }
    },

    created() {
        this.keyEventsOff = on(window, 'keydown', (e) => {
            if (this.layout === 'label') {
                return;
            }

            if (e.key === 'Backspace') {
                this.view.dispatch({
                    selection: {
                        anchor: this.widget.from
                    }
                });

                this.widget.delete();

                setTimeout(() => {
                    this.view.focus();
                }, 1);
            }

            if (e.key === 'c' && (e.metaKey || e.ctrlKey)) {
                this.copyWidgetClipboard();
            }

            if (e.key === 'x' && (e.metaKey || e.ctrlKey)) {
                this.cutWidgetClipboard();
            }

            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                this.view.dispatch({
                    effects: composableActiveEffect.of(null),
                    selection: {
                        anchor: e.key === 'ArrowLeft' ? this.widget.from : this.widget.to
                    }
                });

                setTimeout(() => {
                    this.view.focus();
                }, 1);
            }
        });
    },

    destroyed() {
        this.keyEventsOff();
    },

    methods: {
        copyWidgetClipboard() {
            const doc = this.view.state.doc;
            const selectedText = doc.sliceString(this.widget.from, this.widget.to);

            navigator.clipboard.writeText(selectedText);
        },

        cutWidgetClipboard() {
            const doc = this.view.state.doc;
            const selectedText = doc.sliceString(this.widget.from, this.widget.to);

            navigator.clipboard.writeText(selectedText).then(() => {
                this.view.dispatch({
                    changes: { from: this.widget.from, to: this.widget.to, insert: '' }
                });

                setTimeout(() => {
                    this.view.focus();
                }, 1);
            });
        }
    }
};
</script>
