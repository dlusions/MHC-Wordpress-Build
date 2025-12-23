/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export { html } from '@codemirror/lang-html';
export { json } from '@codemirror/lang-json';
export { createEditor } from './CodeEditor.js';
export { undo, redo, history, isolateHistory, historyKeymap } from '@codemirror/commands';
export {
    Text,
    Facet,
    RangeSet,
    EditorState,
    EditorSelection,
    Annotation,
    Transaction,
    StateField,
    StateEffect,
    Compartment
} from '@codemirror/state';
export {
    indentUnit,
    defaultHighlightStyle,
    syntaxHighlighting,
    foldGutter,
    foldService,
    foldEffect,
    foldable,
    codeFolding,
    syntaxTree,
    ensureSyntaxTree
} from '@codemirror/language';
export {
    keymap,
    WidgetType,
    showPanel,
    ViewPlugin,
    drawSelection,
    dropCursor,
    Decoration,
    lineNumbers,
    EditorView,
    MatchDecorator,
    placeholder,
    tooltips,
    showTooltip,
    hoverTooltip
} from '@codemirror/view';
