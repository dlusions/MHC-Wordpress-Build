/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

import { linter } from '@codemirror/lint';
import { jsonParseLinter } from '@codemirror/lang-json';

const regexpLinter = linter((view) => {
    const diagnostics = [];
    const lineCount = view.state.doc.lines;

    for (let i = 0; i < lineCount; i++) {
        const line = view.state.doc.line(i + 1);

        if (!line.text.trim()) {
            diagnostics.push({
                from: line.from,
                to: line.to,
                severity: 'warning',
                message: 'Not empty lines allowed'
            });
        }
    }

    return diagnostics;
});

export default [linter(jsonParseLinter()), regexpLinter];
