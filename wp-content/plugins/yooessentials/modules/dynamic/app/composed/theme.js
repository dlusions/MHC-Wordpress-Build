/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { EditorView } from '@yooessentials/codemirror';

export default EditorView.theme({
    '&': {
        height: '100%',
        paddingRight: '55px',
        lineHeight: 'initial'
    },
    '&.cm-focused': {
        borderColor: '#3696f3',
        transition: '.2s ease-in-out',
        transitionProperty: 'color, background-color, border'
    },
    // 'img.cm-widgetBuffer': {
    //     verticalAlign: 'middle'
    // },
    // '.cm-cursor, .cm-dropCursor': {
    //     borderColor: '#444'
    // },
    '.cm-content': {
        padding: '10px 0'
    },
    '.cm-scroller': {
        height: '38px',
        minHeight: '38px'
    },
    // selection
    '.cm-selectionBackground': {
        background: '#5297F7 !important'
    },
    '.cm-line': {
        padding: '0',
        lineHeight: '20px'
    },
    '.cm-line::selection': {
        color: 'white !important'
    },
    // tooltips
    '.cm-tooltip': {
        border: 'none',
        backgroundColor: 'transparent'
    },
    '.cm-tooltip .cm-tooltip-arrow': {
        '&:before': {
            borderTop: 'none',
            borderBottom: 'none'
        },
        '&:after': {
            borderTopColor: 'white',
            borderBottomColor: 'white'
        }
    }
});
