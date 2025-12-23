/* Essentials YOOtheme Pro 2.4.12 build 1202.1125; ZOOlanders https://www.zoolanders.com; Copyright (C) Joolanders, SL; http://www.gnu.org/licenses/gpl.html GNU/GPL */

export default {
    name: 'max',
    title: 'Max',
    description:
        'Returns the largest number in a specified array, or the largest number among numbers entered individually.',
    // validate: (text) => SyntaxValidator(text),
    separator: ';',
    insertable: () => `{{ max }}{{ /max }}`,
    regexp: /(?<open>{{ max }})(.*?)(?<close>{{ \/max }})/g
};
