/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { ensureSyntaxTree, foldable, foldEffect } from '@yooessentials/codemirror';

export default (cm) => {
    const effects = [];
    const from = 0;
    const to = cm.state.doc.length;

    ensureSyntaxTree(cm.state, to, 5000)?.iterate({
        from,
        to,
        enter: (node) => {
            const foldRange = foldable(cm.state, node.from, node.to);

            if (foldRange) {
                effects.push(foldEffect.of({ from: foldRange.from, to: foldRange.to }));
            }
        }
    });

    if (effects.length) cm.dispatch({ effects: effects });
};
