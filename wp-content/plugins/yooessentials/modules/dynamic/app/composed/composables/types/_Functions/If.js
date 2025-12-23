/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default {
    name: 'if',
    title: 'If',
    group: 'function',
    description:
        'Returns value 1 if the expression is evaluated to true, otherwise it returns value 2.',
    // validate: (text) => SyntaxValidator(text),
    separator: ';',
    insertable: () => `{{ upper }}{{ /upper }}`,
    regexp: /(?<open>{{ if }})(.*?)(?<close>{{ \/if }})/g
};

// const Matcher = new MatchDecorator({
//     regexp: /{{ max }}.*?{{ \/max }}/g,
//     decorate: (add, from, to, match, view) => {
//         const { sources } = view.state.field(composedField);

//         // const id = 'xxx';
//         const group = uuid(3);
//         const offset = from;
//         // const value = get(sources, id);

//         // if (id !== 'xxxx' && (!value || value.type !== CalculationType.name)) {
//         //     return;
//         // }

//         // calc
//         to = from + match[1].length;
//         add(
//             from,
//             to,
//             Decoration.replace({
//                 widget: new Widget(view, group, SumFunctionType, from, to, {
//                     title: 'max'
//                 })
//             })
//         );

//         // calc end
//         from = offset + match[0].indexOf(match[2]);
//         to = from + match[2].length;
//         add(
//             from,
//             to,
//             Decoration.replace({
//                 widget: new Widget(view, group, SumFunctionType, from, to, {
//                     title: '/'
//                     // icon: false,
//                     // customLabel: false
//                 })
//             })
//         );
//     }
// });

// const validateSyntax = EditorState.transactionFilter.of((tr) => {
//     if (!SyntaxValidator(tr.newDoc.toString())) {
//         return false;
//     }

//     return [tr];
// });

// const handleSourceInsert = onTransactionWithEffect(composableInsertEffect, (tr, { type }) => {
//     if (type.name === CalculationType.name) {
//         return {
//             sequential: true,
//             selection: { anchor: tr.state.selection.main.from + '{{ calc.xxxx }}'.length }
//         };
//     }
// });

// export default ViewPlugin.fromClass(
//     class SourcePlugin extends Plugin {
//         constructor(view) {
//             super(view, CalculationType, Matcher);
//         }
//     },
//     {
//         decorations: (v) => v.decorations,
//         provide: (plugin) => [
//             validateSyntax,
//             handleSourceInsert,
//             EditorView.atomicRanges.of(
//                 (view) => view.plugin(plugin).decorations || Decoration.none
//             ),
//             EditorView.theme({
//                 '.ye-cm-widget-calculation': {
//                     // background: 'rgb(215, 245, 220, 0.9)'
//                     background: 'rgb(222, 222, 222, 0.9)'
//                 }
//             })
//         ]
//     }
// );
