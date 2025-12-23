/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { EditorView } from '@yooessentials/codemirror';

export default EditorView.theme({
    '.ye-cm-widget': {
        lineHeight: '16px',
        fontSize: '10px',
        color: '#777',
        padding: '0 4px',
        border: 'none',
        // cursor: 'pointer',
        outline: 'none',
        borderRadius: '3px',
        transform: 'translateY(-1px)',
        transition: 'box-shadow 0.2s',
        '-webkit-user-select': 'none',

        '& .uk-text-meta': {
            fontSize: '9px'
        }

        // '& .uk-icon': {
        //     marginRight: '2px',
        //     display: 'inherit'
        // },
    },
    '.ye-cm-widget-satellite': {
        cursor: 'default'
    },
    '.ye-cm-widget-dragged': { opacity: 0.6 },
    '.ye-cm-widget-selected:not(.ye-cm-widget-dragged)': {
        color: 'white',
        background: '#69b4ff',
        borderRadius: '0px',

        '& .uk-text-meta': {
            color: 'white'
        }
    },
    '.ye-cm-widget-active': {
        borderBottomLeftRadius: 0,
        borderBottomRightRadius: 0,
        boxShadow: '0 1px 0 0 #fff, 0 2px 0 0 #5294ec'
    },
    '.ye-cm-widget-error': {
        borderBottomLeftRadius: 0,
        borderBottomRightRadius: 0,
        boxShadow: '0 1px 0 0 #fff, 0 2px 0 0 #e44e56'
    }
});
