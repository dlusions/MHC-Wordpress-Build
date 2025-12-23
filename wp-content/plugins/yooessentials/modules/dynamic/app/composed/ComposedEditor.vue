<template>
    <div></div>
</template>

<script>
import {
    html,
    keymap,
    history,
    historyKeymap,
    EditorView,
    EditorState,
    createEditor,
    syntaxHighlighting,
    defaultHighlightStyle
} from '@yooessentials/codemirror';
import { groupBy } from '@yooessentials/util';
import composables from './composables';
import multiline, { toggleMultiline } from './multiline';
import theme from './theme';
import { findAnnotation, onTransactionWithEffect } from './helper';
import { focusEffect, composedField, silentUpdateAnnotation, freezeEffect } from './state';

export default {
    name: 'ComposedEditor',

    provide() {
        return {
            Editor: this,
            $field: this.field
        };
    },

    props: {
        value: {
            type: String,
            required: true
        },
        sources: {
            type: Object,
            required: true
        },
        conditions: {
            type: Object,
            required: true
        },
        field: {
            type: Object,
            required: true
        },
        multiline: {
            type: Boolean,
            default: false
        }
    },

    data: () => ({
        frozen: false,
        selection: null
    }),

    computed: {
        sourceWidgets: {
            get() {
                const widgets = this.$children.filter((child) =>
                    child.$options.name.startsWith('Widget')
                );

                return groupBy(widgets, 'group');
            },
            cache: false
        }
    },

    created() {
        this.$watch('multiline', () => toggleMultiline(this.cm));
    },

    mounted() {
        this.cm = createEditor({
            parent: this.$el,
            value: this.value,
            selection: { anchor: this.value?.length || 0 },
            onChange: (value, update) => {
                // if no changes do not emit
                if (value === this.value) {
                    return;
                }

                this.$emit('change', update);

                // if silent update do not emit
                if (update.transactions?.some((tr) => findAnnotation(tr, silentUpdateAnnotation))) {
                    return;
                }

                this.$emit('input', value);
            },
            setup: [
                theme,
                html(),
                history(),
                keymap.of(historyKeymap),
                syntaxHighlighting(defaultHighlightStyle, { fallback: true }),
                EditorView.focusChangeEffect.of((state, focus) => {
                    return focusEffect.of(focus);
                }),
                composables,
                composedField.init(() => ({
                    types: [],
                    orphans: [],
                    Editor: this,
                    sources: this.sources,
                    conditions: this.conditions
                })),
                multiline(this.multiline), // must go after Composables
                EditorState.transactionExtender.of((tr) => {
                    this.$emit('transaction', tr);
                }),
                EditorView.updateListener.of((update) => {
                    this.selection = update.state.selection;
                    this.$emit('update', update);
                }),
                onTransactionWithEffect(freezeEffect, (tr, freeze) => {
                    this.frozen = freeze;
                })
            ]
        });
    },

    destroyed() {
        this.cm.destroy();
    }
};
</script>
