/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import formComponent from './component';
import { DownloadAction, MessageAction, RedirectAction } from '../../../form-actions';

onDomReady(() => {
    const UIkit = window.UIkit;

    if (!UIkit || !UIkit?.version?.match(/^3\./)) {
        throw new Error('YOOessentials Form Init Error: UIkit v3.x is required.');
    }

    UIkit.component('yooessentials-form', formComponent(UIkit));

    DownloadAction(UIkit);
    MessageAction(UIkit);
    RedirectAction(UIkit);
});

function onDomReady(fn) {
    if (window.document.readyState !== 'loading') {
        fn();
        return;
    }

    window.document.addEventListener(
        'DOMContentLoaded',
        () => {
            fn();
        },
        {
            once: true
        }
    );
}
