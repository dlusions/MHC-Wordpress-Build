/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import hooks from '@yootheme/hooks';
import { deepCopy } from '@yooessentials/util';

hooks.before('app.init', ({ extend }) => {
    extend({
        events: {
            async transformFormAreaToSublayout(e) {
                const { node, builder } = e.origin.Fields;

                if (!node.type.match(/section|column/)) {
                    throw new Error('The node is not a Section or Column.');
                }

                const sublayout = builder.make('fragment');

                sublayout.name = node.name || 'Form Area';
                sublayout.props['yooessentials_form'] = deepCopy(node.props['yooessentials_form']);
                delete node.props['yooessentials_form'];

                // make row/column nodes
                const row = builder.make('row');
                const column = builder.make('column');

                row.children = [column];

                // when section, replace row/column with fresh instances
                // copy current row/column as sublayout children
                if (node.type === 'section') {
                    sublayout.children = node.children;
                    delete node.children;

                    column.children = [sublayout];
                    builder.replaceChildren(node, [row]);
                }

                // when column, make new sublayout row/column
                // copy current column children as sublayout children
                if (node.type === 'column') {
                    column.children = node.children;
                    delete node.children;

                    sublayout.children = [row];
                    builder.replaceChildren(node, [sublayout]);
                }
            }
        }
    });
});
