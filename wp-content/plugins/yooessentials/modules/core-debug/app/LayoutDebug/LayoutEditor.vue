<template>
    <div class="yo-preview-iframe uk-margin-auto uk-margin-auto-vertical uk-overflow-auto">
        <button
            class="uk-modal-close-default uk-position-fixed"
            type="button"
            uk-close
            @click="$trigger('toggleLayoutEditor')"
        ></button>
        <div ref="editor" class="uk-animation-fade"></div>
    </div>
</template>

<script>
import {
    json,
    EditorView,
    EditorState,
    lineNumbers,
    defaultHighlightStyle,
    syntaxHighlighting,
    createEditor,
    codeFolding,
    foldEffect
} from '@yooessentials/codemirror';

export default {
    name: 'LayoutEditor',

    props: {
        builder: Object
    },

    data: () => ({
        source: null
    }),

    computed: {
        value() {
            return JSON.stringify(this.source, null, 4);
        }
    },

    watch: {
        source: {
            handler() {
                if (this.editor.state.doc.toString() !== this.value) {
                    this.editor.dispatch({
                        changes: {
                            from: 0,
                            to: this.editor.state.doc.length,
                            insert: this.value || ''
                        }
                    });
                }
            },
            deep: true
        }
    },

    mounted() {
        const theme = EditorView.theme({
            '.cm-scroller': { height: '100vh' }
        });

        this.editor = createEditor({
            parent: this.$refs.editor,
            onChange: () => {
                this.foldJsonAttribute(this.editor, 'children'); // Fold all children
                // this.foldJsonAttribute(this.editor, 'props', true); // Only fold first-level props
            },
            setup: [
                theme,
                json(),
                codeFolding(),
                lineNumbers(),
                EditorState.readOnly.of(true),
                syntaxHighlighting(defaultHighlightStyle, { fallback: true })
            ]
        });
    },

    destroyed() {
        this.editor.destroy();
    },

    methods: {
        foldJsonAttribute(editor, attributeName, onlyFirstLevel = false) {
            const text = editor.state.doc.toString();
            const regex = new RegExp(`"${attributeName}":\\s*([\\[\\{])`, 'g');
            let match;

            const folds = [];

            while ((match = regex.exec(text)) !== null) {
                // If onlyFirstLevel is true, check if we're at the first level
                if (onlyFirstLevel) {
                    const beforeMatch = text.substring(0, match.index);
                    const depth = this.getJsonDepth(beforeMatch);

                    // Skip if not at first level (depth should be 1 for first level properties)
                    if (depth !== 1) {
                        continue;
                    }
                }

                const startPos = match.index + match[0].length; // Position after the opening bracket/brace
                const openChar = match[1]; // Either '[' or '{'
                const closeChar = openChar === '[' ? ']' : '}';

                // Check if the attribute is empty (next character is the closing bracket/brace)
                if (text[startPos] === closeChar) {
                    continue; // Skip empty attributes
                }

                // Find the matching closing bracket/brace
                let count = 1;
                let currentPos = startPos;

                while (currentPos < text.length && count > 0) {
                    if (text[currentPos] === openChar) count++;
                    if (text[currentPos] === closeChar) count--;
                    currentPos++;
                }

                if (count === 0) {
                    const endPos = currentPos - 1; // Position of the closing bracket/brace
                    folds.push({ from: startPos, to: endPos });
                }
            }

            // Apply folds directly using foldEffect
            if (folds.length > 0) {
                const effects = folds.map((fold) => foldEffect.of(fold));
                editor.dispatch({ effects });
            }
        },

        getJsonDepth(text) {
            let depth = 0;
            for (let i = 0; i < text.length; i++) {
                if (text[i] === '{' || text[i] === '[') {
                    depth++;
                } else if (text[i] === '}' || text[i] === ']') {
                    depth--;
                }
            }
            return depth;
        }
    }
};
</script>
